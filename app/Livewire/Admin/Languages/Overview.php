<?php

namespace App\Livewire\Admin\Languages;

use App\Models\Language;
use App\Services\RedisService;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

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

    public function destroyLanguage(RedisService $redisService) {
        if (!$this->selectedLanguage) return;

        $selectedLanguage = $this->selectedLanguage;

        $this->selectedLanguage->delete();

        $success = $redisService->invalidate('RELOAD_LANGUAGES', 'NULL');
        if (!$success) {
            Language::create($selectedLanguage->toArray());
            Toaster::error(__('admin.toast.languages.reload_api_error'));
            return;
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
