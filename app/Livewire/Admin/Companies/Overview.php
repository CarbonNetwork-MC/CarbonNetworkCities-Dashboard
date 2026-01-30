<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Overview extends Component
{
    use WithPagination;

    public $search = '';
    public $companiesPerPage = 10;

    public $selectedCompany = null;
    public $deleteCompanyModal = false;

    public function removeCompany($id) {
        $this->selectedCompany = Company::find($id);
        $this->deleteCompanyModal = true;
    }

    public function destroyCompany() {
        if ($this->selectedCompany) {
            Company::where('id', $this->selectedCompany->id)->delete();
        }

        $this->reset([
            'selectedCompany',
            'deleteCompanyModal',
        ]);

        Toaster::success(__('admin.toast.companies.deleted'));
    }

    public function render()
    {
        return view('livewire.admin.companies.overview', [
            'companies' => Company::with('owner')
                ->where(function ($query) {
                    $query
                        ->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('world_id', 'like', '%' . $this->search . '%')
                        ->orWhere('coc_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('owner', function ($q) {
                            $q->where('username', 'like', '%' . $this->search . '%')
                            ->orWhere('uuid', 'like', '%' . $this->search . '%');
                        });
                })
                ->paginate($this->companiesPerPage, pageName: 'companiesPage'),
        ]);
    }
}
