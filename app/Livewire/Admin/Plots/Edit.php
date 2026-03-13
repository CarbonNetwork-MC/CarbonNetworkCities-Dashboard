<?php

namespace App\Livewire\Admin\Plots;

use App\Models\Company;
use App\Models\Country;
use App\Models\Fridge;
use App\Models\Player;
use App\Models\Plot;
use App\Models\PlotMember;
use App\Services\RedisService;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Edit extends Component
{
    public $plot;

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

    public $countries;
    public $companies;
    public $players;

    public $searchMembers = '';
    public $searchFridges = '';

    public $membersPerPage = 10;
    public $fridgesPerPage = 5;
    
    public $selectedMember = null;
    public $selectedFridge = null;

    public $showRemoveMemberModal = false;
    public $showRemoveFridgeModal = false;

    public function mount($id) {
        $this->plot = Plot::findOrFail($id);

        $this->plotId = $this->plot->plot_id;
        $this->name = $this->plot->name;
        $this->description = $this->plot->description;
        $this->city = $this->plot->city;
        $this->countryId = $this->plot->country_id;
        $this->worldId = $this->plot->world_id;
        $this->companyId = $this->plot->company_id;
        $this->ownerUuid = $this->plot->owner_uuid;
        $this->minX = $this->plot->min_x;
        $this->minY = $this->plot->min_y;
        $this->minZ = $this->plot->min_z;
        $this->maxX = $this->plot->max_x;
        $this->maxY = $this->plot->max_y;
        $this->maxZ = $this->plot->max_z;
        $this->forSale = $this->plot->for_sale;
        $this->price = $this->plot->price;
        $this->type = $this->plot->type;
        $this->tpX = $this->plot->tp_x;
        $this->tpY = $this->plot->tp_y;
        $this->tpZ = $this->plot->tp_z;
        $this->tpYaw = $this->plot->tp_yaw;
        $this->tpPitch = $this->plot->tp_pitch;

        $this->countries = Country::get(['id', 'name']);
        $this->companies = Company::get(['id', 'name']);
        $this->players = Player::get(['uuid', 'username']);
    }

    public function updated($key, $value) {
        if ($key === 'searchMembers') {
            $this->resetPage('members');
        }
    }

    // ! Plot
    public function updatePlot(RedisService $redisService) {
        if (!$this->plot) return;

        $originalData = $this->plot->toArray();

        $types = config('plots.types');

        $data = $this->validate([
            'plotId' => ['required', 'string', 'max:10', 
            Rule::unique('plots', 'plot_id')
                    ->ignore($this->plot?->plot_id, 'plot_id')],
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

        $this->plot->plot_id = $data['plotId'];
        $this->plot->name = $data['name'];
        $this->plot->description = $data['description'] ?? null;
        $this->plot->city = $data['city'];
        $this->plot->country_id = $data['countryId'];
        $this->plot->world_id = $data['worldId'];
        $this->plot->company_id = $data['companyId'] ?? null;
        $this->plot->owner_uuid = $data['ownerUuid'] ?? null;
        $this->plot->min_x = $data['minX'];
        $this->plot->min_y = $data['minY'];
        $this->plot->min_z = $data['minZ'];
        $this->plot->max_x = $data['maxX'];
        $this->plot->max_y = $data['maxY'];
        $this->plot->max_z = $data['maxZ'];
        $this->plot->for_sale = $data['forSale'] ?? false;
        $this->plot->price = $data['price'] ?? null;
        $this->plot->type = $data['type'];
        $this->plot->tp_x = $data['tpX'] ?? null;
        $this->plot->tp_y = $data['tpY'] ?? null;
        $this->plot->tp_z = $data['tpZ'] ?? null;
        $this->plot->tp_yaw = $data['tpYaw'] ?? null;
        $this->plot->tp_pitch = $data['tpPitch'] ?? null;
        $this->plot->save();

        $success = $redisService->invalidate('INVALIDATE_PLOT', $this->plot->plot_id);
        if (!$success) {
            $this->rollbackPlot($originalData);
            return redirect()->route('admin.plots.edit', ['id' => $this->plot->id])->error(__('admin.toasts.plots.invalidate_plot_api_error'));
        }

        return redirect()->route('admin.plots.edit', ['id' => $this->plot->id])->success(__('admin.toasts.plots.updated'));
    }

    // ! Members
    public function removeMember($uuid) {
        $this->selectedMember = PlotMember::where('player_uuid', $uuid)->first();
        $this->showRemoveMemberModal = true;
    }

    public function destroyMember(RedisService $redisService) {
        $originalData = $this->selectedMember;

        $this->selectedMember->delete();

        $success = $redisService->invalidate('INVALIDATE_PLOT', $this->plot->plot_id);
        if (!$success) {
            PlotMember::create($originalData->toArray());
            return redirect()->route('admin.plots.edit', ['id' => $this->plot->id])->error(__('admin.toasts.plots.invalidate_plot_api_error'));
        }

        $this->reset([
            'selectedMember',
            'showRemoveMemberModal',
        ]);

        Toaster::success(__('admin.toasts.plots.member_delete_success'));
    }

    // ! Fridges
    public function removeFridge($id) {
        $this->selectedFridge = Fridge::findOrFail($id);
        $this->showRemoveFridgeModal = true;
    }

    public function destroyFridge(RedisService $redisService) {
        $originalData = $this->selectedFridge;

        $this->selectedFridge->delete();

        $success = $redisService->invalidate('INVALIDATE_FRIDGE', $this->selectedFridge->id);
        if (!$success) {
            Fridge::create($originalData->toArray());
            return redirect()->route('admin.plots.edit', ['id' => $this->plot->id])->error(__('admin.toasts.plots.invalidate_fridge_api_error'));
        }

        $this->reset([
            'selectedFridge',
            'showRemoveFridgeModal',
        ]);

        Toaster::success(__('admin.toasts.plots.fridge_delete_success'));
    }

    public function render()
    {
        return view('livewire.admin.plots.edit', [
            'members' => $this->plot->members()->with('player')
                ->where(function($query) {
                    $query->where('username', 'like', '%' . $this->searchMembers . '%')
                          ->orWhere('player_uuid', 'like', '%' . $this->searchMembers . '%');
                })
                ->paginate($this->membersPerPage, ['*'], 'members'),

            'fridges' => $this->plot->fridges()
                ->paginate($this->fridgesPerPage, ['*'], 'fridges'),
        ]);
    }

    private function rollbackPlot($originalData) {
        $this->plot->plot_id = $originalData['plot_id'];
        $this->plot->name = $originalData['name'];
        $this->plot->description = $originalData['description'];
        $this->plot->city = $originalData['city'];
        $this->plot->country_id = $originalData['country_id'];
        $this->plot->world_id = $originalData['world_id'];
        $this->plot->company_id = $originalData['company_id'];
        $this->plot->owner_uuid = $originalData['owner_uuid'];
        $this->plot->min_x = $originalData['min_x'];
        $this->plot->min_y = $originalData['min_y'];
        $this->plot->min_z = $originalData['min_z'];
        $this->plot->max_x = $originalData['max_x'];
        $this->plot->max_y = $originalData['max_y'];
        $this->plot->max_z = $originalData['max_z'];
        $this->plot->for_sale = $originalData['for_sale'];
        $this->plot->price = $originalData['price'];
        $this->plot->type = $originalData['type'];
        $this->plot->tp_x = $originalData['tp_x'];
        $this->plot->tp_y = $originalData['tp_y'];
        $this->plot->tp_z = $originalData['tp_z'];
        $this->plot->tp_yaw = $originalData['tp_yaw'];
        $this->plot->tp_pitch = $originalData['tp_pitch'];
        $this->plot->save();
    }
}
