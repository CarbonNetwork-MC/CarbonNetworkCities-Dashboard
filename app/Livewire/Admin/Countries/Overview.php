<?php

namespace App\Livewire\Admin\Countries;

use App\Models\Country;
use App\Services\RedisService;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Overview extends Component
{
    use WithPagination;

    public $search = '';
    public $countriesPerPage = 10;

    public $selectedCountry = null;
    public $deleteCountryModal = false;

    // ? Pagination Method
    public function updated($key, $value) {
        if ($key === 'search') {
            $this->resetPage('countriesPage');
        }
    }

    // ? Country Methods
    public function removeCountry($id) {
        $this->selectedCountry = Country::find($id);
        $this->deleteCountryModal = true;
    }

    public function destroyCountry(RedisService $redisService) {
        if (!$this->selectedCountry) return;

        $selectedCountry = $this->selectedCountry;

        $this->selectedCountry->delete();

        $success = $redisService->invalidate('RELOAD_COUNTRIES', 'NULL');

        if (!$success) {
            Country::create($selectedCountry->toArray());
            Toaster::error(__('admin.toast.countries.reload_countries_api_error'));
            return;
        }

        $this->reset([
            'selectedCountry',
            'deleteCountryModal',
        ]);

        Toaster::success(__('admin.toast.countries.deleted'));
    }
    
    public function render()
    {        
        return view('livewire.admin.countries.overview', [
            'countries' => Country::where(function($query) {
                $query
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('iso', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%')
                    ->orWhere('currency', 'like', '%' . $this->search . '%')
                    ->orWhere('currency_symbol', 'like', '%' . $this->search . '%');
            })
            ->paginate($this->countriesPerPage, pageName: 'countriesPage'),
        ]);
    }
}
