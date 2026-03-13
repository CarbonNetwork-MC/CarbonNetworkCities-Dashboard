<?php

namespace App\Livewire\Admin\CityRegions;

use Livewire\Component;
use App\Models\CityRegion;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use App\Services\RedisService;

class Overview extends Component
{
    use WithPagination;

    public $search = '';
    public $cityRegionsPerPage = 10;

    public $selectedCityRegion = null;
    public $deleteCityRegionModal = false;

    // ? Pagination Method
    public function updated($key, $value) {
        if ($key === 'search') {
            $this->resetPage('cityRegionsPage');
        }
    }

    // ? City Region Methods
    public function removeCityRegion($id) {
        $this->selectedCityRegion = CityRegion::find($id);
        $this->deleteCityRegionModal = true;
    }

    public function destroyCityRegion(RedisService $redisService) {
        if (!$this->selectedCityRegion) return;

        $selectedCityRegion = $this->selectedCityRegion;

        $this->selectedCityRegion->delete();

        $success = $redisService->invalidate('RELOAD_REGIONS', 'NULL');
        if (!$success) {
            CityRegion::create($selectedCityRegion->toArray());
            Toaster::error(__('admin.toast.city_regions.reload_regions_api_error'));
            return;
        }

        $this->reset([
            'selectedCityRegion',
            'deleteCityRegionModal',
        ]);

        Toaster::success(__('admin.toasts.city_regions.deleted'));
    }
    
    public function render()
    {
        return view('livewire.admin.city-regions.overview', [
            'cityRegions' => CityRegion::with('country')
                ->where(function($query) {
                    $query
                        ->where('internal_name', 'like', '%' . $this->search . '%')
                        ->orWhere('display_name', 'like', '%' . $this->search . '%')
                        ->orWhere('city', 'like', '%' . $this->search . '%')
                        ->orWhere('world_id', 'like', '%' . $this->search . '%');
                })
                ->paginate($this->cityRegionsPerPage, pageName: 'cityRegionsPage'),
        ]);
    }
}
