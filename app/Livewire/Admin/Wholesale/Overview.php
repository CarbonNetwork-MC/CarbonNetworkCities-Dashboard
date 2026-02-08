<?php

namespace App\Livewire\Admin\Wholesale;

use App\Models\WholesaleItem;
use Livewire\Component;

class Overview extends Component
{
    public $search = '';
    public $itemsPerPage = 25;

    public $selectedItem = null;
    public $deleteItemModal = false;

    public function render()
    {
        return view('livewire.admin.wholesale.overview', [
            'items' => WholesaleItem::with('item')
                ->whereHas('item', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->paginate($this->itemsPerPage),
        ]);
    }
}
