<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Hash;
use App\Services\PluginAPI\ApiService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class Overview extends Component
{
    use WithFileUploads;

    public $user;

    public $username;
    public $email;
    public $profileImage;
    public $currentProfileImage;

    public $currentPassword = '';
    public $newPassword = '';
    public $newPassword_confirmation = '';

    public $playerToUnlink = null;
    public $accountToDelete = null;

    public $unlinkPlayerModal = false;
    public $deleteAccountModal = false;

    public function mount()
    {
        $this->user = auth()->user();

        $this->username = $this->user->name;
        $this->email = $this->user->email;
        $this->currentProfileImage = $this->user->profile_photo_path
            ? asset('storage/' . $this->user->profile_photo_path)
            : null;
    }

    public function updatedProfileImage() {
        $this->validate([
            'profileImage' => 'image|max:2048', // 2MB Max
        ]);

        $path = $this->profileImage->store(path: 'profile-images', options: 'public');

        // Delete old profile image if exists
        if ($this->user->profile_photo_path) {
            Storage::disk('public')->delete($this->user->profile_photo_path);
        }

        // Update user's profile image path
        $this->user->profile_photo_path = $path;
        $this->user->save();

        $this->currentProfileImage = asset('storage/' . $path);

        Toaster::success(__('profile.toast.profile_image_updated'));
    }

    public function saveChanges() {
        $data = $this->validate([
            'username' => ['required', 'string', 'max:255'],
             // Ensure email is unique except for the current user's email
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->uuid . ',uuid'],
        ]);

        $this->user->name = $data['username'];
        $this->user->email = $data['email'];
        $this->user->save();

        Toaster::success(__('profile.toast.profile_updated'));
    }

    public function updatePassword() {
        $data = $this->validate([
            'currentPassword' => ['required', 'current_password'],
            'newPassword' => ['required', 'confirmed', Password::defaults()],
        ]);

        $this->user->password = Hash::make($data['newPassword']);
        $this->user->save();

        $this->reset(['currentPassword', 'newPassword', 'newPassword_confirmation']);

        Toaster::success(__('profile.toast.password_updated'));
    }

    public function unlinkPlayer() {
        $this->playerToUnlink = $this->user->load('player', 'accountLink');
        $this->unlinkPlayerModal = true;
    }

    public function unlink(ApiService $apiService) {
        if (!$this->playerToUnlink || !$this->playerToUnlink->accountLink) return;

        $originalAccountLink = $this->playerToUnlink->accountLink;

        $this->playerToUnlink->accountLink()->delete();

        [$status, $success] = $apiService->post("api/invalidate/player/{$this->playerToUnlink->player->uuid}");

        if (!$success) {
            $this->playerToUnlink->accountLink()->save($originalAccountLink);
            Toaster::error(__('profile.toast.account_unlink_api_error'));
            return;
        }

        $this->reset('playerToUnlink', 'unlinkPlayerModal');

        Toaster::success(__('profile.toast.account_unlinked'));
    }

    public function deleteAccount() {
        $this->accountToDelete = $this->user->load('player', 'accountLink');
        $this->deleteAccountModal = true;
    }

    public function destroyAccount(ApiService $apiService) {
        if (!$this->accountToDelete) return;

        $user = $this->accountToDelete;

        // Force-load relations once
        $user->load(['player', 'accountLink']);

        $playerUuid = $user->player?->uuid;

        if (!$playerUuid) {
            Toaster::error(__('profile.toast.account_delete_no_player'));
            return;
        }

        $user->accountLink()->delete();

        [$status, $success] = $apiService->post("api/invalidate/player/{$playerUuid}");

        if (!$success) {
            $user->accountLink()->save($user->accountLink);
            Toaster::error(__('profile.toast.account_delete_api_error'));
            return;
        }
    
        if ($this->user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
        $user->delete();

        auth()->logout();

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.profile.overview');
    }
}
