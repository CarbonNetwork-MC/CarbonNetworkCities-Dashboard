<?php

namespace App\Livewire\Admin\Languages;

use App\Models\Language;
use Livewire\Component;

class NewLanguage extends Component
{
    public $language;
    public $name;
    public $shortCode;
    public $code;
    public $headdbId;

    public function createLanguage() {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:50'],
            'shortCode' => ['required', 'string', 'max:2'],
            'code' => ['required', 'string', 'max:5'],
            'headdbId' => ['numeric', 'nullable', 'string', 'max:11'],
        ]);

        Language::create([
            'name' => $data['name'],
            'short_code' => $data['shortCode'],
            'code' => $data['code'],
            'headdb_id' => $data['headdbId'],
        ]);

        return redirect()->route('admin.languages.render')->success(__('admin.toast.languages.create'));
    }

    public function render()
    {
        return view('livewire.admin.languages.new-language');
    }
}
