<?php

namespace App\Livewire\Admin\CityRegions;

use App\Models\Country;
use Livewire\Component;
use App\Models\CityRegion;
use App\Services\RedisService;

class NewCityRegion extends Component
{
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

    public function createCityRegion(RedisService $redisService) {
        $data = $this->validate([
            'internalName' => ['required', 'string', 'max:255', 'unique:city_regions,internal_name'],
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

        $cityRegion = CityRegion::create([
            'internal_name' => $internalName,
            'display_name' => $data['displayName'],
            'country_id' => $data['selectedCountry'],
            'city' => $data['city'],
            'world_id' => $data['worldId'],
            'min_x' => $data['minX'],
            'min_y' => $data['minY'],
            'min_z' => $data['minZ'],
            'max_x' => $data['maxX'],
            'max_y' => $data['maxY'],
            'max_z' => $data['maxZ'],
        ]);

        $success = $redisService->invalidate('RELOAD_REGIONS', 'NULL');
        if (!$success) {
            $cityRegion->delete();
            return redirect()->route('admin.city-regions.new')->error(__('admin.toast.city_regions.reload_regions_api_error'));
        }

        return redirect()->route('admin.city-regions.render')->success(__('admin.toast.city_regions.created'));
    }

    public function render()
    {
        return view('livewire.admin.city-regions.new-city-region', [
            'countries' => Country::get(['id', 'name']),
        ]);
    }
}
