<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewRequisitionUserNotification;
use App\Mail\NewRequisitionAdminNotification;

class RequisitionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Requisition::class, 'requisition');
    }


    public function index()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');
        $thirtyDaysAgo = now()->subDays(30)->format('Y-m-d');

        $activeRequisitionsCount = Requisition::where('status', 'active')->count();
        $last30DaysRequisitionsCount = Requisition::where('start_date', '>=', $thirtyDaysAgo)->count();
        $returnedTodayCount = Requisition::where('actual_return_date', 'like', $today . '%')->count();

        if ($user->isAdmin()) {
            // Admin vê todas as requisições
            $requisitions = Requisition::with('user', 'book')->orderByRaw('actual_return_date IS NULL DESC') // Ativas primeiro
                ->orderBy('actual_return_date', 'desc')->paginate(10);
        } else {
            // Cidadão vê apenas as suas
            $requisitions = Requisition::with('book')->where('user_id', $user->id)->orderByRaw('actual_return_date IS NULL DESC') // Ativas primeiro
                ->orderBy('actual_return_date', 'desc')->paginate(10);
        }

        $activeUserRequestsCount = Requisition::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        return view('requisitions.index', compact(
            'requisitions',
            'activeUserRequestsCount',
            'activeRequisitionsCount',
            'last30DaysRequisitionsCount',
            'returnedTodayCount'
        ));
    }


    public function create(Request $request, $bookId = null)
    {
        $user = Auth::user();

        // Se o usuário não for admin, verificar se já tem 3 requisições ativas
        if (!$user->isAdmin()) {
            $activeRequestsCount = Requisition::where('user_id', $user->id)
                ->where('status', 'active')
                ->count();

            if ($activeRequestsCount >= 3) {
                return redirect()->route('requisitions.index')
                    ->with('error', 'You have reached the maximum number of requisitions.');
            }
        }

        // Listar só livros disponíveis para requisição (sem requisição ativa)
        $booksAvailable = Book::whereDoesntHave('requisitions', function ($query) {
            $query->where('status', 'active');
        })->get();

        $selectedBookId = $request->book_id ?? $bookId;


        // Se for admin, buscar a lista de usuários cidadãos para o select
        $users = [];
        if (Auth::user()->isAdmin()) {
            $userIds = Requisition::where('status', 'active')
                ->whereNull('actual_return_date')
                ->select('user_id')
                ->groupBy('user_id')
                ->havingRaw('COUNT(*) >= 3')
                ->pluck('user_id')
                ->toArray();

            $users = User::whereIn('role', ['citizen', 'admin'])
                ->whereNotIn('id', $userIds)
                ->orderBy('name')
                ->get();
        }

        return view('requisitions.create', compact('booksAvailable', 'users', 'selectedBookId'));
    }


    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'book_id' => 'required|exists:books,id',
            'citizen_photo' => 'required|image|mimes:jpg,png|max:2048',
        ];

        if ($user->isAdmin()) {
            // admin pode criar para outro user
            $rules['user_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        // Verificar se o livro está disponível (não tem requisição ativa)
        $activeRequest = Requisition::where('book_id', $validated['book_id'])
            ->where('status', 'active')
            ->exists();

        if ($activeRequest) {
            return back()->withErrors(['book_id' => 'Este livro já está requisitado no momento.'])->withInput();
        }
        // Definir qual usuário será o dono da requisição
        $userId = $user->isAdmin() ? $validated['user_id'] : $user->id;

        // Verificar limite de 3 livros ativos para o cidadão
        $activeUserRequestsCount = Requisition::where('user_id', $userId)
            ->where('status', 'active')
            ->count();

        if ($activeUserRequestsCount >= 3) {
            return back()->withErrors(['limit' => 'Você já tem 3 livros requisitados em simultâneo.'])->withInput();
        }
        $path = $request->file('citizen_photo')->store('requisitions/photos', 'public');

        $requisition = Requisition::create([
            'user_id' => $userId,
            'book_id' => $request->book_id,
            'citizen_photo' => $path,
            'start_date' => now(),
            'end_date' => now()->addDays(5),
            'status' => 'active',
        ]);

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NewRequisitionAdminNotification($requisition));
        }

        Mail::to($requisition->user->email)->send(new NewRequisitionUserNotification($requisition));

        return redirect()->route('requisitions.index')->with('success', 'Requisição criada com sucesso!');
    }

    public function show(Requisition $requisition)
    {
        $requisition = Requisition::with('user', 'book')->findOrFail($requisition->id);

        // Só pode ver o detalhe se for admin ou dono da requisição
        $user = Auth::user();
        if (!$user->isAdmin() && $requisition->user_id !== $user->id) {
            abort(403);
        }

        return view('requisitions.show', compact('requisition'));
    }

    public function edit(Requisition $requisition)
    {
        $requisition = Requisition::with('user', 'book')->findOrFail($requisition->id);

        return view('requisitions.edit', compact('requisition'));
    }

    public function update(Request $request, Requisition $requisition)
    {
        $requisition = Requisition::findOrFail($requisition->id);

        $request->validate([
            'actual_return_date' => 'nullable|date|after_or_equal:start_date',
            'admin_confirmed' => 'required|boolean',
        ]);

        $requisition->actual_return_date = $request->actual_return_date;
        $requisition->admin_confirmed = $request->admin_confirmed;

        // Atualiza o status para 'returned' se confirmado e data preenchida
        if ($request->admin_confirmed && $request->actual_return_date) {
            $requisition->status = 'returned';
        }

        $requisition->save();

        return redirect()->route('requisitions.index', $requisition->id)
            ->with('success', 'Request updated successfully.');
    }
}
