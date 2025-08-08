<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Publisher;
use Livewire\WithPagination;

class PublishersList extends Component
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
        if (!empty($this->search)) {
            // Para dados cifrados, usamos busca especial
            $publishersCollection = Publisher::whereLikeEncrypted('name', $this->search);
            // Converter para paginação compatível com links()

        } else {
            $publishersCollection = Publisher::all();
        }

        $sortedCollection = $publishersCollection->sortBy(function ($publisher) {
            return $publisher->{$this->sortField} ?? '';
        }, SORT_NATURAL | SORT_FLAG_CASE, $this->sortDirection === 'desc');

        $publishers = Publisher::paginateCollection($sortedCollection, 10);

        return view('livewire.publishers-list', [
            'publishers' => $publishers,
        ]);
    }
}
