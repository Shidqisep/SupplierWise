<?php

namespace App\Livewire;

use Livewire\Component;

class AdminDashboard extends Component
{
    public string $tab = 'suppliers';

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
