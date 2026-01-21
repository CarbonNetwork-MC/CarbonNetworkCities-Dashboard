<?php

namespace App\Livewire\Admin\Languages;

use Livewire\Component;
use App\Models\Language;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Http;
use App\Services\PluginAPI\InvalidationService;

class NewLanguage extends Component
{
    public $language;
    public $name;
    public $shortCode;
    public $code;
    public $headdbId;

    public function createLanguage() {
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

        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/reload/languages");

        $requestId = $response->json('requestId');
            
        $success = $this->waitForInvalidationResult($requestId);
        if (!$success) {
            $newLanguage->delete();
            return Toaster::error(__('admin.toast.reload_languages_api_error'));
        }

        return redirect()->route('admin.languages.render')->success(__('admin.toast.language_created'));
    }

    public function render()
    {
        return view('livewire.admin.languages.new-language');
    }

    private function waitForInvalidationResult(string $requestId): bool {
        return app(InvalidationService::class)->waitForInvalidationResult($requestId);
    }
}
