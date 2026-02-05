<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Storage;

class Overview extends Component
{
    use WithFileUploads;

    public $user;

    public $username;
    public $email;
    public $profileImage;
    public $currentProfileImage;

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

        Toaster::success(__('profile.toast.profile-image-updated'));
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

        Toaster::success(__('profile.toast.profile-updated'));
    }

    public function render()
    {
        return view('livewire.profile.overview');
    }
}
