<?php

namespace App\Livewire\Admin\Plots;

use App\Models\Plot;
use App\Models\Player;
use App\Models\Company;
use App\Models\Country;
use App\Services\RedisService;
use Livewire\Component;

class NewPlot extends Component
{
    public $countries;
    public $companies;
    public $players;

    public $plotId;
    public $name;
    public $description;
    public $city;
    public $countryId;
    public $worldId;
    public $companyId;
    public $ownerUuid;
    public $minX;
    public $minY;
    public $minZ;
    public $maxX;
    public $maxY;
    public $maxZ;
    public $forSale;
    public $price;
    public $type;
    public $tpX;
    public $tpY;
    public $tpZ;
    public $tpYaw;
    public $tpPitch;

    public function mount() {
        $this->countries = Country::get(['id', 'name']);
        $this->companies = Company::get(['id', 'name']);
        $this->players = Player::get(['uuid', 'username']);
    }

    public function createPlot(RedisService $redisService) {
        $types = config('plots.types');

        $data = $this->validate([
            'plotId' => ['required', 'string', 'max:10', 'unique:plots,plot_id'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'city' => ['required', 'string', 'max:50'],
            'countryId' => ['required', 'integer'],
            'worldId' => ['required', 'string', 'max:50'],
            'companyId' => ['nullable', 'integer'],
            'ownerUuid' => ['nullable', 'string', 'max:36'],
            'minX' => ['required', 'numeric'],
            'minY' => ['required', 'numeric'],
            'minZ' => ['required', 'numeric'],
            'maxX' => ['required', 'numeric'],
            'maxY' => ['required', 'numeric'],
            'maxZ' => ['required', 'numeric'],
            'forSale' => ['boolean'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'type' => ['required', 'string', 'in:' . implode(',', $types)],
            'tpX' => ['nullable', 'numeric'],
            'tpY' => ['nullable', 'numeric'],
            'tpZ' => ['nullable', 'numeric'],
            'tpYaw' => ['nullable', 'numeric'],
            'tpPitch' => ['nullable', 'numeric'],
        ]);

        $plot = Plot::create([
            'plot_id' => $data['plotId'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'city' => $data['city'],
            'country_id' => $data['countryId'],
            'world_id' => $data['worldId'],
            'company_id' => $data['companyId'] ?? null,
            'owner_uuid' => $data['ownerUuid'] ?? null,
            'min_x' => $data['minX'],
            'min_y' => $data['minY'],
            'min_z' => $data['minZ'],
            'max_x' => $data['maxX'],
            'max_y' => $data['maxY'],
            'max_z' => $data['maxZ'],
            'for_sale' => $data['forSale'] ?? false,
            'price' => $data['price'] ?? null,
            'type' => $data['type'],
            'tp_x' => $data['tpX'] ?? null,
            'tp_y' => $data['tpY'] ?? null,
            'tp_z' => $data['tpZ'] ?? null,
            'tp_yaw' => $data['tpYaw'] ?? null,
            'tp_pitch' => $data['tpPitch'] ?? null,
        ]);

        $success = $redisService->invalidate('INVALIDATE_PLOT', $this->plotId);
        if (!$success) {
            $plot->delete();
            return redirect()->route('admin.plots.new')->error(__('admin.toast.plots.invalidate_plot_api_error'));
        }

        return redirect()->route('admin.plots.render')->success(__('admin.toast.plots.created'));
    }

    public function render()
    {
        return view('livewire.admin.plots.new-plot');
    }
}
