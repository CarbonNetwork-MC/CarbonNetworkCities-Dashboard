<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use App\Models\Plot;
use Livewire\Component;

class AddPlot extends Component
{
    public $company;

    public $plotId;

    public function mount($id) {
        $this->company = Company::where('id', $id)->firstOrFail();
    }

    public function addPlot() {
        $data = $this->validate([
            'plotId' => ['required', 'string', 'max:255', 'exists:plots,plot_id'],
        ],
        [
            'plotId.required' => __('admin.validation.company.plot_id_required'),
            'plotId.exists' => __('admin.validation.company.plot_id_exists'),
        ]);

        $plot = Plot::where('plot_id', $data['plotId'])->first();
        $plot->company_id = $this->company->id;
        $plot->save();

        return redirect()->route('admin.companies.edit', ['id' => $this->company->id]);
    }

    public function render()
    {
        return view('livewire.admin.companies.add-plot');
    }
}
