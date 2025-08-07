<?php

namespace App\Livewire;

use App\Models\Author;
use Livewire\Component;

class AuthorsList extends Component
{
    public function render()
    {
        // Fetch authors filtered by search, paginate 10 per page (optional)
        if (!empty($this->search)) {
            // Para dados cifrados, usamos busca especial (menos eficiente)
            $authorsCollection = Author::whereLikeEncrypted('name', $this->search);
            $sortedCollection = $authorsCollection->sortBy('name');

            // Converter para paginação compatível com links()
            $authors = Author::paginateCollection($sortedCollection, 10);
        } else {
            $authors = Author::orderBy('name')->paginate(10);
        }

        return view('livewire.authors-list', [
            'authors' => $authors,
        ]);
    }
}
