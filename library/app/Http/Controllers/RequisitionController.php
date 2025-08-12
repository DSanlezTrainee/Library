<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequisitionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            // Admin vê todas as requisições
            $requisitions = Requisition::with('user', 'book')->latest()->paginate(15);
        } else {
            // Cidadão vê apenas as suas
            $requisitions = Requisition::with('book')->where('user_id', $user->id)->latest()->paginate(15);
        }

        return view('requisitions.index', compact('requisitions'));
    }


    public function create()
    {
        // Listar só livros disponíveis para requisição (sem requisição ativa)
        $booksAvailable = Book::whereDoesntHave('requisitions', function ($query) {
            $query->where('status', 'active');
        })->get();

        return view('requisitions.create', compact('booksAvailable'));
    }


    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // Verificar se o livro está disponível (não tem requisição ativa)
        $activeRequest = Requisition::where('book_id', $request->book_id)
            ->where('status', 'active')
            ->exists();

        if ($activeRequest) {
            return back()->withErrors(['book_id' => 'Este livro já está requisitado no momento.'])->withInput();
        }

        // Verificar limite de 3 livros ativos para o cidadão
        $activeUserRequestsCount = Requisition::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        if ($activeUserRequestsCount >= 3) {
            return back()->withErrors(['limit' => 'Você já tem 3 livros requisitados em simultâneo.'])->withInput();
        }

        Requisition::create([
            'user_id' => $user->id,
            'book_id' => $request->book_id,
            'start_date' => now(),
            'end_date' => now()->addDays(5),
            'status' => 'active',
        ]);

        return redirect()->route('requisitions.index')->with('success', 'Requisição criada com sucesso!');
    }

    public function show(Requisition $requisition)
    {
        $requisition = Requisition::with('user', 'book')->findOrFail($requisition->id);

        // Só pode ver o detalhe se for admin ou dono da requisição
        $user = Auth::user();
        if (!$user->hasRole('admin') && $requisition->user_id !== $user->id) {
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

        return redirect()->route('requisitions.show', $requisition->id)
            ->with('success', 'Requisição atualizada com sucesso.');
    }
}
