<?php

namespace App\Livewire\Admin\Languages;

use Livewire\Component;
use App\Models\Language;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use App\Services\PluginAPI\ApiService;

class Overview extends Component
{
    use WithPagination;

    public $searchLanguage = '';

    public $deleteLanguageModal = false;

    public $selectedLanguage = null;

    // ? Pagination Method
    public function updated($key, $value) {
        if ($key === 'searchLanguage') {
            $this->resetPage('page');
        }
    }

    // ? Language Methods
    public function removeLanguage($id) {
        $this->selectedLanguage = Language::find($id);
        $this->deleteLanguageModal = true;
    }

    public function destroyLanguage(ApiService $apiService) {
        if (!$this->selectedLanguage) return;

        $selectedLanguage = $this->selectedLanguage;

        $this->selectedLanguage->delete();

        [$status, $success] = $apiService->post("api/reload/languages");

        if (!$success) {
            Language::create($selectedLanguage->toArray());
            return Toaster::error(__('admin.toasts.languages.reload_api_error'));
        }

        $this->reset([
            'selectedLanguage',
            'deleteLanguageModal',
        ]);

        Toaster::success(__('admin.toasts.languages.deleted'));
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
