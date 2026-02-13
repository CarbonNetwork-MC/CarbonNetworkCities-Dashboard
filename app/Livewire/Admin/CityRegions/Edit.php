<?php

namespace App\Livewire\Admin\CityRegions;

use App\Models\Country;
use Livewire\Component;
use App\Models\CityRegion;
use Masmerise\Toaster\Toaster;
use Illuminate\Validation\Rule;
use App\Services\PluginAPI\ApiService;

class Edit extends Component
{
    public $cityRegion;
    public $internalName;
    public $displayName;
    public $selectedCountry;
    public $city;
    public $worldId;
    public $minX;
    public $minY;
    public $minZ;
    public $maxX;
    public $maxY;
    public $maxZ;
    
    public function mount($id) {
        $this->cityRegion = CityRegion::findOrFail($id);

        $this->internalName = $this->cityRegion->internal_name;
        $this->displayName = $this->cityRegion->display_name;
        $this->selectedCountry = $this->cityRegion->country_id;
        $this->city = $this->cityRegion->city;
        $this->worldId = $this->cityRegion->world_id;
        $this->minX = $this->cityRegion->min_x;
        $this->minY = $this->cityRegion->min_y;
        $this->minZ = $this->cityRegion->min_z;
        $this->maxX = $this->cityRegion->max_x;
        $this->maxY = $this->cityRegion->max_y;
        $this->maxZ = $this->cityRegion->max_z;
    }

    public function updateCityRegion(ApiService $apiService) {
        if (!$this->cityRegion) return;

        $originalData = $this->cityRegion->toArray();

        $data = $this->validate([
            'internalName' => [
                'required', 'string', 'max:255',
                Rule::unique('city_regions', 'internal_name')
                    ->ignore($this->cityRegion?->id, 'id'),
            ],
            'displayName' => ['required', 'string', 'max:255'],
            'selectedCountry' => ['nullable', 'integer', 'exists:countries,id'],
            'city' => ['nullable', 'string', 'max:100'],
            'worldId' => ['required', 'string', 'max:255'],
            'minX' => ['required', 'integer'],
            'minY' => ['required', 'integer'],
            'minZ' => ['required', 'integer'],
            'maxX' => ['required', 'integer'],
            'maxY' => ['required', 'integer'],
            'maxZ' => ['required', 'integer'],
        ]);

        $internalName = strtolower(str_replace(' ', '_', $data['internalName']));

        $this->cityRegion->internal_name = $internalName;
        $this->cityRegion->display_name = $data['displayName'];
        $this->cityRegion->country_id = $data['selectedCountry'];
        $this->cityRegion->city = $data['city'];
        $this->cityRegion->world_id = $data['worldId'];
        $this->cityRegion->min_x = $data['minX'];
        $this->cityRegion->min_y = $data['minY'];
        $this->cityRegion->min_z = $data['minZ'];
        $this->cityRegion->max_x = $data['maxX'];
        $this->cityRegion->max_y = $data['maxY'];
        $this->cityRegion->max_z = $data['maxZ'];
        $this->cityRegion->save();

        [$status, $success] = $apiService->post("api/reload/regions");

        if ($status !== 202) {
            $this->rollbackCityRegion($originalData);
            Toaster::error(__('admin.toasts.city_regions.reload_regions_api_error'));
            return;
        }

        if (!$success) {
            $this->rollbackCityRegion($originalData);
            Toaster::error(__('admin.toasts.city_regions.reload_regions_api_error'));
            return;
        }

        return redirect()->route('admin.city-regions.render')->success(__('admin.toasts.city_regions.updated'));
    }

    public function render()
    {
        return view('livewire.admin.city-regions.edit', [
            'countries' => Country::get(['id', 'name']),
        ]);
    }

    private function rollbackCityRegion(array $originalData): void {
        $this->cityRegion->internal_name = $originalData['internal_name'];
        $this->cityRegion->display_name = $originalData['display_name'];
        $this->cityRegion->country_id = $originalData['country_id'];
        $this->cityRegion->city = $originalData['city'];
        $this->cityRegion->world_id = $originalData['world_id'];
        $this->cityRegion->min_x = $originalData['min_x'];
        $this->cityRegion->min_y = $originalData['min_y'];
        $this->cityRegion->min_z = $originalData['min_z'];
        $this->cityRegion->max_x = $originalData['max_x'];
        $this->cityRegion->max_y = $originalData['max_y'];
        $this->cityRegion->max_z = $originalData['max_z'];
        $this->cityRegion->save();
    }
}
