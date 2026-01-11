<?php

namespace App\Livewire\Components;

use App\Models\Permission;
use Livewire\Component;

class Sidebar extends Component
{
    public $user;
    public $sidebarItems;

    public $editSidebar;
    public $managePerms;
    public $manageUsers;

    public function mount(): void
    {
        $this->user = auth()->user();

        $this->editSidebar = Permission::where('name', 'edit_sidebar')->first();
        $this->managePerms = Permission::where('name', 'manage_permissions')->first();
        $this->manageUsers = Permission::where('name', 'manage_users')->first();
    }

    public function render()
    {
        return view('livewire.components.sidebar');
    }
}