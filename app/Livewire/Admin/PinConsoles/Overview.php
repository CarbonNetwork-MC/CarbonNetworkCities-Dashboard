<?php

namespace App\Livewire\Admin\PinConsoles;

use App\Models\PinConsole;
use App\Services\RedisService;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Overview extends Component
{
    use WithPagination;

    public $search = '';
    public $pinConsolesPerPage = 10;

    public $selectedPinConsole = null;
    public $deletePinConsoleModal = false;

    // ? Pagination Method
    public function updated($key, $value) {
        if ($key === 'search') {
            $this->resetPage('pinConsolesPage');
        }
    }

    // ? PIN Console Methods
    public function removePinConsole($id) {
        $this->selectedPinConsole = PinConsole::find($id);
        $this->deletePinConsoleModal = true;
    }

    public function destroyPinConsole(RedisService $redisService) {
        if (!$this->selectedPinConsole) return;

        $selectedPinConsole = $this->selectedPinConsole;

        $this->selectedPinConsole->delete();

        $success = $redisService->invalidate('INVALIDATE_PIN_CONSOLE', $selectedPinConsole->id);
        if (!$success) {
            PinConsole::create($selectedPinConsole->toArray());
            Toaster::error(__('admin.toast.pin_consoles.invalidate_pin_console_api_error'));
            return;
        }

        $this->reset([
            'selectedPinConsole',
            'deletePinConsoleModal',
        ]);

        Toaster::success(__('admin.toast.pin_consoles.deleted'));
    }

    public function render()
    {        
        return view('livewire.admin.pin-consoles.overview', [
            'pinConsoles' => PinConsole::with(['company', 'country'])
                ->where(function($query) {
                    $query
                        ->where('city', 'like', '%' . $this->search . '%')
                        ->orWhere('world_id', 'like', '%' . $this->search . '%')
                        ->orWhereHas('company', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('coc_number', 'like', '%' . $this->search . '%');
                        });
                })
                ->paginate($this->pinConsolesPerPage, pageName: 'pinConsolesPage'),
        ]);
    }
}
