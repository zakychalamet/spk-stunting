<?php

namespace App\Livewire\IbuHamil;

use App\Models\IbuHamil;
use Livewire\Component;

class Show extends Component
{
    public IbuHamil $ibuHamil;

    public function mount(IbuHamil $ibuHamil)
    {
        $this->ibuHamil = $ibuHamil;
    }

    public function render()
    {
        return view('livewire.ibu-hamil.show')->layout('layouts.app', [
            'title' => 'Detail Ibu Hamil',
            'breadcrumb' => 'Detail Ibu Hamil',
        ]);
    }
}
