<?php

namespace App\Livewire\Admin\Languages;

use Livewire\Component;
use App\Models\Language;
use App\Services\PluginAPI\ApiService;
use Masmerise\Toaster\Toaster;

class NewLanguage extends Component
{
    public $language;
    public $name;
    public $shortCode;
    public $code;
    public $headdbId;

    public function createLanguage(ApiService $apiService) {
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

        [$status, $success] = $apiService->post("api/reload/languages");

        if (!$success) {
            $newLanguage->delete();
            return Toaster::error(__('admin.toast.reload_languages_api_error'));
        }

        return redirect()->route('admin.languages.render')->success(__('admin.toast.languages.create'));
    }

    public function render()
    {
        return view('livewire.admin.languages.new-language');
    }
}
