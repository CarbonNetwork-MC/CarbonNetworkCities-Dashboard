<?php

namespace App\Livewire\Admin\CoC;

use App\Models\CoCType;
use Livewire\Component;
use Livewire\WithPagination;

class Overview extends Component
{
    use WithPagination;

    public $search = '';

    public $itemsPerPage = 10;

    public function render()
    {
        return view('livewire.admin.coc.overview', [
            'cocTypes' => CoCType::where('name', 'like', '%' . $this->search . '%')
                ->paginate($this->itemsPerPage),
        ]);
    }
}
