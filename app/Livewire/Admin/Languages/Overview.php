<?php

namespace App\Livewire\Admin\Languages;

use Livewire\Component;
use App\Models\Language;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Overview extends Component
{
    use WithPagination;

    public $searchLanguage = '';

    public $deleteLanguageModal = false;

    public $selectedLanguage = null;

    // ? Language Methods
    public function removeLanguage($id) {
        $this->selectedLanguage = Language::find($id);
        $this->deleteLanguageModal = true;
    }

    public function destroyLanguage() {
        if ($this->selectedLanguage) {
            Language::where('id', $this->selectedLanguage->id)->delete();
        }

        $this->reset([
            'selectedLanguage',
            'deleteLanguageModal',
        ]);

        Toaster::success(__('admin.toast.language_deleted'));
    }
    
    public function render()
    {
        return view('livewire.admin.languages.overview', [
            'languages' => Language::where('name', 'like', '%' . $this->searchLanguage . '%')
                ->orWhere('code', 'like', '%' . $this->searchLanguage . '%')
                ->paginate(10),
        ]);
    }
}
