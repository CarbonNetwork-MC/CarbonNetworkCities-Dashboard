<?php

namespace App\Livewire\Company\Archive;

use App\Models\Company;
use App\Models\CompanySale;
use Livewire\Component;
use Livewire\WithPagination;

class Overview extends Component
{
    use WithPagination;

    public $company;

    public $weeks = [];
    public $selectedWeek;

    public $salesPerPage = 20;

    public function mount($companyId) {
        $this->company = Company::where('id', $companyId)->first();
        $this->weeks = $this->company->sales()
            ->where('week', '!=', now()->weekOfYear)
            ->get()
            ->unique('week')
            ->pluck('week')
            ->values()
            ->toArray();

        $this->selectedWeek = $this->weeks[0] ?? null;
    }

    public function render() {
        $sales = CompanySale::where('company_id', $this->company->id)
            ->where('week', '!=', now()->weekOfYear)
            ->with(['items', 'customer', 'employee', 'salary'])
            ->paginate($this->salesPerPage, ['*'], 'salesPage');

        return view('livewire.company.archive.overview', [
            'sales' => $sales,
        ]);
    }
}
