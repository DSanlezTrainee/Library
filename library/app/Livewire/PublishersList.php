<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Publisher;

class PublishersList extends Component
{
    public string $search = '';

    public function render()
    {
        if (!empty($this->search)) {
            // Para dados cifrados, usamos busca especial
            $publishersCollection = Publisher::whereLikeEncrypted('name', $this->search);
            $sortedCollection = $publishersCollection->sortBy('name');

            // Converter para paginação compatível com links()
            $publishers = Publisher::paginateCollection($sortedCollection, 10);
        } else {
            $publishers = Publisher::orderBy('name')->paginate(10);
        }

        return view('livewire.publishers-list', [
            'publishers' => $publishers,
        ]);
    }
}
