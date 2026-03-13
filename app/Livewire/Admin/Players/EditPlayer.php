<?php

namespace App\Livewire\Admin\Players;

use App\Models\ChatColor;
use App\Models\CityRegion;
use App\Models\Company;
use App\Models\Country;
use App\Models\Language;
use App\Models\PersonalBankaccount;
use App\Models\Player;
use App\Models\PlayerChatColor;
use App\Models\PlayerPastUsername;
use App\Models\PlayerPrefix;
use App\Models\Plot;
use App\Services\RedisService;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class EditPlayer extends Component
{
    use WithPagination;

    private $defaultPrefixColor = "<white>";
    private $defaultLevelColor = "<white>";
    private $defaultChatColor = "<gray>";

    public $player;

    public string $uuid;
    public string $username;
    public int $level;
    public ?int $selectedCountry;
    public ?int $selectedLanguage;
    public ?int $playtime;
    public ?int $lastRegion;
    public bool $updatePlaytime = false;

    public $selectedChatColor = null;

    public $allChatColors;
    public $countries;
    public $languages;
    public $regions;

    public $searchPrefixes = '';
    public $searchChatColors = '';
    public $searchBankAccounts = '';
    public $searchPlots = '';
    public $searchCompanies = '';
    public $searchPastUsernames = '';

    public $prefixesPerPage = 5;
    public $chatColorsPerPage = 10;
    public $bankAccountsPerPage = 5;
    public $plotsPerPage = 5;
    public $companiesPerPage = 5;
    public $pastUsernamesPerPage = 5;

    public $prefixToRemove = null;
    public $chatColorToRemove = null;
    public $bankAccountToRemove = null;
    public $plotToRemove = null;
    public $companyToRemove = null;
    public $pastUsernameToRemove = null;

    public $showRemovePrefixModal = false;
    public $showRemoveChatColorModal = false;
    public $showRemoveBankAccountModal = false;
    public $showRemovePlotModal = false;
    public $showRemoveCompanyModal = false;
    public $showRemovePastUsernameModal = false;

    public function mount($uuid) {
        $this->player = Player::where('uuid', $uuid)->first();

        $this->uuid = $this->player->uuid;
        $this->username = $this->player->username;
        $this->level = $this->player->level;
        $this->selectedCountry = $this->player->nationality;
        $this->selectedLanguage = $this->player->selected_language;
        $this->playtime = $this->player->playtime;
        $this->lastRegion = $this->player->last_region_id;

        $this->allChatColors = ChatColor::all();
        $this->countries = Country::all();
        $this->languages = Language::all();
        $this->regions = CityRegion::all();
    }

    public function updated($key, $value) {
        if ($key === 'searchPrefixes') {
            $this->resetPage('prefixes');
        }

        if ($key === 'searchChatColors') {
            $this->resetPage('chatColors');
        }

        if ($key === 'searchBankAccounts') {
            $this->resetPage('bankAccounts');
        }

        if ($key === 'searchPlots') {
            $this->resetPage('plots');
        }

        if ($key === 'searchCompanies') {
            $this->resetPage('companies');
        }

        if ($key === 'searchPastUsernames') {
            $this->resetPage('pastUsernames');
        }
    }

    // ! Player
    public function updatePlayer(RedisService $redisService) {
        // Store the original player for rollback in case of failure
        $originalPlayer = $this->player;

        // 1. Validate input
        $data = $this->validate([
            'uuid' => ['required', 'string', 'max:36', Rule::unique('players', 'uuid')->ignore($this->player?->uuid, 'uuid')],
            'username' => ['required', 'string', 'max:16'],
            'level' => ['required', 'integer', 'min:0'],
            'selectedCountry' => ['nullable', 'integer', 'exists:countries,id'],
            'selectedLanguage' => ['nullable', 'integer', 'exists:languages,id'],
            'playtime' => ['nullable', 'integer', 'min:0'],
            'lastRegion' => ['nullable', 'integer', 'exists:city_regions,id'],
        ]);

        // 2. Optimistic update
        if ($this->player->uuid !== $data['uuid']) {
            $this->player->uuid = $data['uuid'];
        }
        $this->player->username = $data['username'];
        $this->player->level = $data['level'];
        $this->player->nationality = $data['selectedCountry'];
        $this->player->selected_language = $data['selectedLanguage'];
        if ($this->updatePlaytime) {
            $this->player->playtime = $data['playtime'];
        }
        $this->player->last_region_id = $data['lastRegion'];
        
        $updated = Player::where('uuid', $originalPlayer->uuid)
            ->where('playtime', '<=', $originalPlayer->playtime)
            ->update([
                'uuid' => $this->player->uuid,
                'username' => $this->player->username,
                'level' => $this->player->level,
                'nationality' => $this->player->nationality,
                'selected_language' => $this->player->selected_language,
                'playtime' => $this->updatePlaytime ? $this->player->playtime : $originalPlayer->playtime,
                'last_region_id' => $this->player->last_region_id,
            ]);

        if ($updated === 0) {
            Toaster::error(__('admin.toast.players.playtime_conflict'));
            return;
        }

        // 3. Send invalidate request to the Minecraft servers
        $success = $redisService->invalidate('INVALIDATE_PLAYER', $originalPlayer->uuid);
        if (!$success) {
            $this->rollbackPlayer($originalPlayer);
            Toaster::error(__('admin.toast.players.update_failed'));
            return;
        }
        
        // 4. Success
        Toaster::success(__('admin.toast.players.update_success'));
    }

    // ! Prefix
    public function assignPrefix($id, RedisService $redisService) {
        // Store the current selected prefix and the original values for rollback in case of failure
        $originalSelectedPrefix = $this->player->prefixes()->where('selected', true)->first();
        $newSelectedPrefix = PlayerPrefix::find($id);

        // 1. Optimistic update
        $newSelectedPrefix->update(['selected' => true]);
        $this->player->prefixes()->where('id', '!=', $newSelectedPrefix->id)->update(['selected' => false]);

        // 2. Send invalidate request to Velocity
        $success = $redisService->invalidate('INVALIDATE_PLAYER', $this->player->uuid);
        if (!$success) {
            $this->rollbackPrefix($newSelectedPrefix, $originalSelectedPrefix);
            Toaster::error(__('admin.toast.players.prefix_assign_failed'));
            return;
        }

        // 3. Success
        $this->resetPage('prefixes');

        Toaster::success(__('admin.toast.players.prefix_assign_success'));
    }

    public function removePrefix($id) {
        $this->prefixToRemove = PlayerPrefix::find($id);
        $this->showRemovePrefixModal = true;
    }

    public function destroyPrefix(RedisService $redisService) {
        // Store the original prefix for rollback in case of failure
        $originalPrefix = $this->prefixToRemove;
        $defaultPrefix = PlayerPrefix::where('player_uuid', $this->player->uuid)
                                    ->where('prefix', 'Citizen')->first();

        if ($this->prefixToRemove->id === $defaultPrefix->id) {
            Toaster::error(__('admin.toast.players.cannot_remove_default_prefix'));
            return;
        }

        // 1. Optimistic delete & unselect if needed
        $this->prefixToRemove->delete();
        if ($originalPrefix->selected && $defaultPrefix && $defaultPrefix->id !== $originalPrefix->id) {
            $defaultPrefix->selected = true;
            $defaultPrefix->save();
        }

        // 2. Send invalidate request to Velocity
        $success = $redisService->invalidate('INVALIDATE_PLAYER', $this->player->uuid);
        if (!$success) {
            $this->rollbackPrefix($originalPrefix, $defaultPrefix);
            Toaster::error(__('admin.toast.players.prefix_remove_failed'));
            return;
        }

        // 3. Success
        $this->showRemovePrefixModal = false;
        $this->prefixToRemove = null;

        Toaster::success(__('admin.toast.players.prefix_remove_success'));
    }

    // ! Chat Colors
    public function selectChatColor($id, $type, RedisService $redisService) {
        // Store the current selected chat color and the original values for rollback in case of failure
        $originalSelectedChatColor = $this->player->chatColors()->where('selected', true)->where('type', $type)->first();
        $newSelectedChatColor = PlayerChatColor::find($id);

        // 1. Optimistic update
        $newSelectedChatColor->update(['selected' => true]);
        $this->player->chatColors()->where('id', '!=', $newSelectedChatColor->id)->where('type', $type)->update(['selected' => false]);

        // 2. Send invalidate request to Velocity
        $success = $redisService->invalidate('INVALIDATE_PLAYER', $this->player->uuid);
        if (!$success) {
            $this->rollbackChatColorSelect($newSelectedChatColor, $originalSelectedChatColor);
            Toaster::error(__('admin.toast.players.chat_color_select_failed'));
            return;
        }

        // 4. Success
        $this->resetPage('chatColors');

        Toaster::success(__('admin.toast.players.chat_color_select_success'));
    }

    public function removeChatColor($id) {
        $this->chatColorToRemove = PlayerChatColor::find($id);
        $this->showRemoveChatColorModal = true;
    }

    public function destroyColor(RedisService $redisService) {
        // Store the original chat color for rollback in case of failure
        $originalChatColor = $this->chatColorToRemove;

        $playerDefaultPrefixColor = ChatColor::where('code', $this->defaultPrefixColor)->first();
        $playerDefaultLevelColor = ChatColor::where('code', $this->defaultLevelColor)->first();
        $playerDefaultChatColor = ChatColor::where('code', $this->defaultChatColor)->first();

        $defaultColor = null; 

        if (
            ($this->chatColorToRemove->type === 'prefix' && $this->chatColorToRemove->color_id === $playerDefaultPrefixColor->id) ||
            ($this->chatColorToRemove->type === 'level' && $this->chatColorToRemove->color_id === $playerDefaultLevelColor->id) ||
            ($this->chatColorToRemove->type === 'chat' && $this->chatColorToRemove->color_id === $playerDefaultChatColor->id)
        ) {
            $this->showRemoveChatColorModal = false;
            $this->chatColorToRemove = null;
            Toaster::error(__('admin.toast.players.cannot_remove_default_chat_color'));
            return;
        }

        // 1. Optimistic delete
        $this->chatColorToRemove->delete();
        // If it was selected, select the default color of that type
        if ($originalChatColor->selected) {
            if ($originalChatColor->type === 'prefix') {
                $defaultColor = $playerDefaultPrefixColor;
            } elseif ($originalChatColor->type === 'level') {
                $defaultColor = $playerDefaultLevelColor;
            } elseif ($originalChatColor->type === 'chat') {
                $defaultColor = $playerDefaultChatColor;
            }
        }

        // 2. Send invalidate request to Velocity
        $success = $redisService->invalidate('INVALIDATE_PLAYER', $this->player->uuid);
        if (!$success) {
            $this->rollbackChatColor($originalChatColor, $defaultColor);
            Toaster::error(__('admin.toast.players.chat_color_remove_failed'));
            return;
        }

        // 3. Success
        $this->showRemoveChatColorModal = false;
        $this->chatColorToRemove = null;

        Toaster::success(__('admin.toast.players.chat_color_remove_success'));
    }

    // ! Bank Accounts
    public function removeBankAccount($id) {
        $this->bankAccountToRemove = PersonalBankaccount::find($id);
        $this->showRemoveBankAccountModal = true;
    }

    public function destroyBankAccount(RedisService $redisService) {
        // Store the original bank account for rollback in case of failure
        $originalBankAccount = $this->bankAccountToRemove;

        // 1. Optimistic delete
        $this->bankAccountToRemove->delete();

        // 2. Send invalidate request to Velocity
        $success = $redisService->invalidate('INVALIDATE_PLAYER', $this->player->uuid);
        if (!$success) {
            $this->rollbackBankAccount($originalBankAccount);
            Toaster::error(__('admin.toast.players.bank_account_remove_failed'));
            return;
        }

        // 3. Success
        $this->showRemoveBankAccountModal = false;
        $this->bankAccountToRemove = null;

        Toaster::success(__('admin.toast.players.bank_account_remove_success'));
    }

    // ! Plots
    public function removePlot($id) {
        $this->plotToRemove = Plot::find($id);
        $this->showRemovePlotModal = true;
    }

    public function unlinkPlot(RedisService $redisService) {
        // Store the original plot for rollback in case of failure
        $originalPlot = $this->plotToRemove;

        // 1. Optimistic unlink
        Plot::where('id', $this->plotToRemove->id)->where('owner_uuid', $this->player->uuid)
            ->update(['owner_uuid' => null]);

        // 2. Send invalidate request to Velocity
        $success = $redisService->invalidate('INVALIDATE_PLOT', $this->plotToRemove->plot_id);
        if (!$success) {
            $this->rollbackPlot($originalPlot);
            Toaster::error(__('admin.toast.players.plot_unlink_failed'));
            return;
        }

        // 3. Success
        $this->showRemovePlotModal = false;
        $this->plotToRemove = null;

        Toaster::success(__('admin.toast.players.plot_unlink_success'));
    }

    // ! Companies
    public function removeCompany($id) {
        $this->companyToRemove = Company::find($id);
        $this->showRemoveCompanyModal = true;
    }

    public function unlinkCompany(RedisService $redisService) {
        // Store the original company for rollback in case of failure
        $originalCompany = $this->companyToRemove;

        // 1. Optimistic unlink
        Company::where('id', $this->companyToRemove->id)->where('owner_uuid', $this->player->uuid)
            ->update(['owner_uuid' => null]);

        // 2. Send invalidate request to Velocity
        $success = $redisService->invalidate('INVALIDATE_COMPANY', $this->companyToRemove->id);
        if (!$success) {
            $this->rollbackCompany($originalCompany);
            Toaster::error(__('admin.toast.players.company_unlink_failed'));
            return;
        }

        // 3. Success
        $this->showRemoveCompanyModal = false;
        $this->companyToRemove = null;

        Toaster::success(__('admin.toast.players.company_unlink_success'));
    }

    // ! Past Usernames
    public function removePastUsername($id) {
        $this->pastUsernameToRemove = PlayerPastUsername::find($id);
        $this->showRemovePastUsernameModal = true;
    }

    public function destroyPastUsername(RedisService $redisService) {
        // Store the original past username for rollback in case of failure
        $originalPastUsername = $this->pastUsernameToRemove;

        // 1. Optimistic delete
        $this->pastUsernameToRemove->delete();

        // 2. Send invalidate request to Velocity
        $success = $redisService->invalidate('INVALIDATE_PLAYER', $this->player->uuid);
        if (!$success) {
            $this->rollbackPastUsername($originalPastUsername);
            Toaster::error(__('admin.toast.players.past_username_remove_failed'));
            return;
        }

        // 4. Success
        $this->showRemovePastUsernameModal = false;
        $this->pastUsernameToRemove = null;

        Toaster::success(__('admin.toast.players.past_username_remove_success'));
    }

    public function render()
    {
        if ($this->getErrorBag()->isNotEmpty()) {
            logger()->debug('Validation errors:', $this->getErrorBag()->toArray());
        }

        return view('livewire.admin.players.edit-player', [
            'prefixes' => $this->player->prefixes()->where('prefix', 'like', '%' . $this->searchPrefixes . '%')->paginate($this->prefixesPerPage, ['*'], 'prefixes'),
            'chatColors' => $this->player->chatColors()->paginate($this->chatColorsPerPage, ['*'], 'chatColors'),
            'bankAccounts' => $this->player->bankAccounts()->where('id', 'like', '%' . $this->searchBankAccounts . '%')->paginate($this->bankAccountsPerPage, ['*'], 'bankAccounts'),
            'plots' => $this->player->plots()->where('plot_id', 'like', '%' . $this->searchPlots . '%')->where('name', 'like', '%' . $this->searchPlots . '%')->paginate($this->plotsPerPage, ['*'], 'plots'),
            'companies' => $this->player->companies()->where('name', 'like', '%' . $this->searchCompanies . '%')->where('coc_number', 'like', '%' . $this->searchCompanies . '%')->paginate($this->companiesPerPage, ['*'], 'companies'),
            'pastUsernames' => $this->player->pastUsernames()->where('username', 'like', '%' . $this->searchPastUsernames . '%')->paginate($this->pastUsernamesPerPage, ['*'], 'pastUsernames'),
        ]);
    }

    // ! Rollback methods
    private function rollbackPlayer($originalPlayer) {
        $this->player->uuid = $originalPlayer->uuid;
        $this->player->username = $originalPlayer->username;
        $this->player->level = $originalPlayer->level;
        $this->player->nationality = $originalPlayer->nationality;
        $this->player->selected_language = $originalPlayer->selected_language;
        $this->player->playtime = $originalPlayer->playtime;
        $this->player->last_region_id = $originalPlayer->last_region_id;
        $this->player->save();
    }

    private function rollbackPrefix($originalPrefix, $defaultPrefix) {
        PlayerPrefix::create([
            'player_uuid' => $originalPrefix->player_uuid,
            'prefix' => $originalPrefix->prefix,
            'selected' => $originalPrefix->selected,
        ]);
    
        // Select or unselect the default prefix as needed
        if ($originalPrefix->selected && $defaultPrefix && $defaultPrefix->id !== $originalPrefix->id) {
            $defaultPrefix->selected = false;
            $defaultPrefix->save();
        }
    }

    private function rollbackChatColor($originalChatColor, $defaultColor) {
        PlayerChatColor::create([
            'player_uuid' => $originalChatColor->player_uuid,
            'color_id' => $originalChatColor->color_id,
            'type' => $originalChatColor->type,
            'selected' => $originalChatColor->selected,
        ]);
    
        // Select or unselect the default chat color as needed
        if ($originalChatColor->selected && $defaultColor && $defaultColor->id !== $originalChatColor->id) {
            $defaultColor->selected = false;
            $defaultColor->save();
        }
    }

    private function rollbackChatColorSelect($newSelectedChatColor, $originalSelectedChatColor) {
        // Rollback the updated chat color
        $newSelectedChatColor->update([
            'selected' => false,
        ]);

        // Rollback the selected chat color if it was changed
        if ($originalSelectedChatColor) {
            $this->player->chatColors()->where('id', '!=', $newSelectedChatColor->id)->where('type', $newSelectedChatColor->type)->update(['selected' => false]);
            $originalSelectedChatColor->update(['selected' => true]);
        }
    }

    private function rollbackBankAccount($originalBankAccount) {
        PersonalBankaccount::create([
            'player_uuid' => $originalBankAccount->player_uuid,
            'balance' => $originalBankAccount->balance,
            'type' => $originalBankAccount->type,
            'currency' => $originalBankAccount->currency,
        ]);
    }

    private function rollbackPlot($originalPlot) {
        Plot::where('id', $originalPlot->id)->where('owner_uuid', null)
            ->update(['owner_uuid' => $this->player->uuid]);
    }

    private function rollbackCompany($originalCompany) {
        Company::where('id', $originalCompany->id)->where('owner_uuid', null)
            ->update(['owner_uuid' => $this->player->uuid]);
    }

    private function rollbackPastUsername($originalPastUsername) {
        PlayerPastUsername::create([
            'player_uuid' => $originalPastUsername->player_uuid,
            'username' => $originalPastUsername->username,
        ]);
    }
}
