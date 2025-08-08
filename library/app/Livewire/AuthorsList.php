<?php

namespace App\Livewire;

use App\Models\Author;
use Livewire\Component;
use Livewire\WithPagination;

class AuthorsList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function render()
    {
        // Buscar todos os autores
        if (!empty($this->search)) {
            $authorsCollection = Author::whereLikeEncrypted('name', $this->search);
        } else {
            $authorsCollection = Author::all();
        }

        // Ordenar dinamicamente pelo campo e direção escolhidos
        $sortedCollection = $authorsCollection->sortBy(function ($author) {
            return $author->{$this->sortField} ?? '';
        }, SORT_NATURAL | SORT_FLAG_CASE, $this->sortDirection === 'desc');

        // Paginar manualmente
        $authors = Author::paginateCollection($sortedCollection, 10);

        return view('livewire.authors-list', [
            'authors' => $authors,
        ]);
    }
}
