<?php

namespace App\Livewire\Admin\Languages;

use App\Models\Language;
use App\Services\RedisService;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Edit extends Component
{
    public $language;
    public $name;
    public $shortCode;
    public $code;
    public $headdbId;

    public function mount($id) {
        $this->language = Language::where('id', $id)->firstOrFail();
        $this->name = $this->language->name;
        $this->shortCode = $this->language->short_code;
        $this->code = $this->language->code;
        $this->headdbId = $this->language->headdb_id;
    }

    public function updateLanguage(RedisService $redisService) {
        if (!$this->language) return;

        $language = $this->language;

        $data = $this->validate([
            'name' => [
                'required', 'string', 'max:50',
                Rule::unique('languages', 'name')
                    ->ignore($this->language?->id, 'id'),
            ],
            'shortCode' => [
                'required', 'string', 'max:2',
                Rule::unique('languages', 'short_code')
                    ->ignore($this->language?->id, 'id'),
            ],
            'code' => [
                'required', 'string', 'max:5',
                Rule::unique('languages', 'code')
                    ->ignore($this->language?->id, 'id'),
            ],
            'headdbId' => ['numeric', 'nullable', 'string', 'max:11'],
        ]);

        $this->language->name = $data['name'];
        $this->language->short_code = $data['shortCode'];
        $this->language->code = $data['code'];
        $this->language->headdb_id = $data['headdbId'];
        $this->language->save();

        $success = $redisService->invalidate('RELOAD_LANGUAGES', 'NULL');
        if (!$success) {
            $this->language->name = $language['name'];
            $this->language->short_code = $language['shortCode'];
            $this->language->code = $language['code'];
            $this->language->headdb_id = $language['headdbId'];
            $this->language->save();
            Toaster::error(__('admin.toast.languages.reload_api_error'));
            return;
        }

        return redirect()->route('admin.languages.render')->success(__('admin.toast.languages.updated'));
    }

    public function render()
    {
        return view('livewire.admin.languages.edit');
    }
}
