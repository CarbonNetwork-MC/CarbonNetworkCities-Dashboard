<?php

namespace App\Livewire\Admin\Languages;

use App\Models\Language;
use Livewire\Component;

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

    public function updateLanguage() {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:50'],
            'shortCode' => ['required', 'string', 'max:2'],
            'code' => ['required', 'string', 'max:5'],
            'headdbId' => ['numeric', 'nullable', 'string', 'max:11'],
        ]);

        $this->language->name = $data['name'];
        $this->language->short_code = $data['shortCode'];
        $this->language->code = $data['code'];
        $this->language->headdb_id = $data['headdbId'];
        $this->language->save();

        return redirect()->route('admin.languages.render')->success(__('admin.toast.languages.updated'));
    }

    public function render()
    {
        return view('livewire.admin.languages.edit');
    }
}
