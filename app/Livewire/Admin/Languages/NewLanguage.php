<?php

namespace App\Livewire\Admin\Languages;

use App\Models\Language;
use App\Services\RedisService;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class NewLanguage extends Component
{
    public $language;
    public $name;
    public $shortCode;
    public $code;
    public $headdbId;

    public function createLanguage(RedisService $redisService) {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:50', 'unique:languages,name'],
            'shortCode' => ['required', 'string', 'max:2', 'unique:languages,short_code'],
            'code' => ['required', 'string', 'max:5', 'unique:languages,code'],
            'headdbId' => ['numeric', 'nullable', 'string', 'max:11'],
        ]);

        $newLanguage = Language::create([
            'name' => $data['name'],
            'short_code' => $data['shortCode'],
            'code' => $data['code'],
            'headdb_id' => $data['headdbId'],
        ]);

        $success = $redisService->invalidate('RELOAD_LANGUAGES', 'NULL');
        if (!$success) {
            $newLanguage->delete();
            Toaster::error(__('admin.toast.languages.reload_api_error'));
            return;
        }

        return redirect()->route('admin.languages.render')->success(__('admin.toasts.languages.create'));
    }

    public function render()
    {
        return view('livewire.admin.languages.new-language');
    }
}
