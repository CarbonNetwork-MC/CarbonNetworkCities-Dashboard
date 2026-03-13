<?php
namespace App\Services;

use App\Models\Player;

class PlayerPermissionService
{
    public function syncWholesaleOrderPermission(Player $player): void {
        $hasCompanies = $player->companies->count() > 0;
        $isManager = $player->managerAt->count() > 0;
        $hasPermission = $player->user->hasPermissionTo('wholesale_order');

        if (!$hasCompanies || !$isManager && !$hasPermission) {
            $player->user->givePermissionTo('wholesale_order');
        } 
        
        if (!$hasCompanies && !$isManager && $hasPermission) {
            $player->user->revokePermissionTo('wholesale_order');
        }
    }
}