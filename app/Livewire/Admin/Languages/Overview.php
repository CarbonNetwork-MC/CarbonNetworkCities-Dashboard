<?php

namespace App\Livewire\Admin\Languages;

use Livewire\Component;
use App\Models\Language;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Http;
use App\Http\Livewire\Concerns\WithInvalidation;

class Overview extends Component
{
    use WithPagination;
    use WithInvalidation;

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

    public function destroyLanguage() {
        if (!$this->selectedLanguage) return;

        $selectedLanguage = $this->selectedLanguage;

        $this->selectedLanguage->delete();

        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/reload/languages");

        $requestId = $response->json('requestId');
            
        $success = $this->waitForInvalidationResult($requestId);
        if (!$success) {
            Language::create($selectedLanguage->toArray());
            return Toaster::error(__('admin.toast.reload_languages_api_error'));
        }

        $this->reset([
            'selectedLanguage',
            'deleteLanguageModal',
        ]);

        Toaster::success(__('admin.toast.languages.deleted'));
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
