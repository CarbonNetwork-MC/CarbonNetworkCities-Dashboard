<?php

namespace App\Livewire\Admin\CoC;

use App\Models\CoCType;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Overview extends Component
{
    use WithPagination;

    public $search = '';

    public $itemsPerPage = 10;

    public $typeToDelete = null;
    public $showDeleteModal = false;

    public function removeCoCType($id) {
        $this->typeToDelete = CoCType::find($id);
        $this->showDeleteModal = true;
    }

    public function confirmRemoveCoCType() {
        if (!$this->typeToDelete) return;

        $this->typeToDelete->delete();
        $this->typeToDelete = null;
        $this->showDeleteModal = false;

        Toaster::success(__('admin.toasts.coc.deleted'));
    }

    public function render()
    {
        return view('livewire.admin.coc.overview', [
            'cocTypes' => CoCType::where('name', 'like', '%' . $this->search . '%')
                ->paginate($this->itemsPerPage),
        ]);
    }
}