<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UsersList extends Component
{
     public $search = '';

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);
            
        return view('livewire.users-list', ['users' => $users]);
    }
}
