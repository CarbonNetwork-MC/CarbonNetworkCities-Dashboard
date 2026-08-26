<?php

namespace App\Livewire\Admin\Wholesalers;

use App\Models\Wholesaler;
use Livewire\Component;
use Livewire\WithPagination;

class Overview extends Component
{
    use WithPagination;
    
    public $wholesalersPerPage = 10;
    
    public function render()
    {

        $wholesalers = Wholesaler::paginate($this->wholesalersPerPage, ['*'], 'wholesalersPage');

        return view('livewire.admin.wholesalers.overview', [
            'wholesalers' => $wholesalers,
        ]);
    }
}
