<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Plot;
use App\Models\Company;
use App\Services\PluginAPI\ApiService;
use Livewire\Component;

class AddPlot extends Component
{    
    public $company;

    public $plotId;

    public function mount($id) {
        $this->company = Company::where('id', $id)->firstOrFail();
    }

    public function addPlot(ApiService $apiService) {
        $data = $this->validate([
            'plotId' => ['required', 'string', 'max:255', 'exists:plots,plot_id'],
        ],
        [
            'plotId.required' => __('admin.validation.company.plot_id_required'),
            'plotId.exists' => __('admin.validation.company.plot_id_exists'),
        ]);

        $plot = Plot::where('plot_id', $data['plotId'])->firstOrFail();

        // Guard: plot already linked
        if ($plot->company_id !== null) {
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toast.company_plot_already_assigned'));
        }

        $previousCompanyId = $plot->company_id;
        $plotId = $plot->plot_id;

        // 1. Optimistic link
        $plot->company_id = $this->company->id;
        $plot->save();

        // 2. Send invalidate request to Velocity
        [$status, $success] = $apiService->post("api/invalidate/plot/{$plotId}");

        // Immediate failure (request not accepted)
        if ($status !== 202) {
            $this->rollbackPlot($plot, $previousCompanyId);
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toast.company_plot_add_failed'));
        }

        // 3. Poll for result
        if (!$success) {
            $this->rollbackPlot($plot, $previousCompanyId);
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toast.company_plot_add_failed'));
        }

        // 4. Success
        return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->success(__('admin.toast.company_plot_added'));
    }

    public function render()
    {
        return view('livewire.admin.companies.add-plot');
    }

    private function rollbackPlot(Plot $plot, int $companyId): void {
        $plot->company_id = $companyId;
        $plot->save();
    }
}
