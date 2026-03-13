<?php

namespace App\Livewire\Admin\Plots;

use App\Models\Plot;
use App\Services\RedisService;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Overview extends Component
{
    use WithPagination;

    public $search = '';

    public $plotsPerPage = 10;

    public $selectedPlot = null;

    public $deletePlotModal = null;

    // ? Pagination Method
    public function updated($key, $value) {
        if ($key === 'search') {
            $this->resetPage('plotsPage');
        }
    }

    // ? Plot Methods
    public function removePlot($id) {
        $this->selectedPlot = Plot::find($id);
        $this->deletePlotModal = true;
    }

    public function destroyPlot(RedisService $redisService) {
        if (!$this->selectedPlot) return;

        $selectedPlot = $this->selectedPlot;

        $this->selectedPlot->delete();

        $success = $redisService->invalidate('INVALIDATE_PLOT', $selectedPlot->plot_id);
        if (!$success) {
            Plot::create($selectedPlot->toArray());
            Toaster::error(__('admin.toast.plots.invalidate_plot_api_error'));
            return;
        }

        $this->reset([
            'selectedPlot',
            'deletePlotModal',
        ]);

        Toaster::success(__('admin.toasts.plots.delete_success'));
    }

    public function render()
    {
        return view('livewire.admin.plots.overview', [
            'plots' => Plot::with(['country', 'company', 'owner', 'members', 'fridges'])
            ->whereHas('country', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('company', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('owner', function ($q) {
                $q->where('username', 'like', '%' . $this->search . '%');
            })
            ->orWhere('plot_id', 'like', '%' . $this->search . '%')
            ->orWhere('name', 'like', '%' . $this->search . '%')
            ->orWhere('description', 'like', '%' . $this->search . '%')
            ->orWhere('city', 'like', '%' . $this->search . '%')
            ->orWhere('world_id', 'like', '%' . $this->search . '%')
            ->orWhere('type', 'like', '%' . $this->search . '%')
            ->orderBy('plot_id')
            ->paginate($this->plotsPerPage, ['*'], 'plotsPage'),
        ]);
    }
}
