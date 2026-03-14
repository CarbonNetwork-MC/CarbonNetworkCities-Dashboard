<?php

namespace App\Livewire\Components;

use App\Models\Company;
use App\Models\Permission;
use Livewire\Component;

class Sidebar extends Component
{
    public $user;
    public $userProfilePicture;

    public $selectedCompany = null;
    public $isCompanyOwnerOrManager = false;
    public $hasCompanies = false;

    public $editSidebar;
    public $managePerms;
    public $manageUsers;

    public function mount(): void {
        $this->user = auth()->user();
        $this->userProfilePicture = $this->user->profile_photo_path
            ? asset('storage/' . $this->user->profile_photo_path)
            : null;

        $this->selectedCompany = request()->route('companyId') && request()->routeIs('company.*') ? Company::find(request()->route('companyId')) : null;
        if ($this->selectedCompany && ($this->selectedCompany->owner->uuid == $this->user->player->uuid || $this->selectedCompany->employees()->where('player_uuid', $this->user->player->uuid)->where('role', 'manager')->exists())) {
            $this->isCompanyOwnerOrManager = true;
        }

        $this->hasCompanies = $this->user->player->amountOfCompanies() > 0;

        $this->editSidebar = Permission::where('name', 'edit_sidebar')->first();
        $this->managePerms = Permission::where('name', 'manage_permissions')->first();
        $this->manageUsers = Permission::where('name', 'manage_users')->first();
    }

    public function render()
    {
        return view('livewire.components.sidebar');
    }
}