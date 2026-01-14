<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use App\Models\Plot;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

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

        $plot = Plot::where('plot_id', $data['plotId'])->firstOrFail();

        // Guard: plot already linked
        if ($plot->company_id !== null) {
            Toaster::error(__('admin.toast.company_plot_already_assigned'));
            return;
        }

        $previousCompanyId = $plot->company_id;
        $plotId = $plot->plot_id;

        // 1. Optimistic link
        $plot->company_id = $this->company->id;
        $plot->save();

        // 2. Send invalidate request to Velocity
        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/invalidate/plot/{$plotId}");

        // Immediate failure (request not accepted)
        if ($response->status() !== 202) {
            $this->rollbackPlot($plot, $previousCompanyId);
            Toaster::error(__('admin.toast.company_plot_add_failed'));
            return;
        }

        $requestId = $response->json('requestId');

        // 3. Poll for result
        $success = $this->waitForInvalidationResult($requestId);

        if (!$success) {
            $this->rollbackPlot($plot, $previousCompanyId);
            Toaster::error(__('admin.toast.company_plot_add_failed'));
            return;
        }

        // 4. Success
        Toaster::success(__('admin.toast.company_plot_added'));

        return redirect()->route(
            'admin.companies.edit',
            ['id' => $this->company->id]
        );
    }

    public function render()
    {
        return view('livewire.admin.companies.add-plot');
    }

    private function waitForInvalidationResult(string $requestId): bool {
        $statusUrl = config('services.plugin-api.url')
            . "api/invalidate/status/{$requestId}";

        $timeoutSeconds = 3;
        $pollIntervalMs = 300;

        $start = microtime(true);

        while ((microtime(true) - $start) < $timeoutSeconds) {
            $response = Http::withToken(config('services.plugin-api.key'))
                ->get($statusUrl);

            if ($response->failed()) {
                return false;
            }

            $state = $response->json('state');
            $results = $response->json('responses', []);

            if ($state === 'COMPLETED') {
                // Success if at least one server reloaded the plot
                foreach ($results as $server => $status) {
                    if ($status === 'RELOADED') {
                        return true;
                    }
                }

                // All responded, none succeeded
                return false;
            }

            usleep($pollIntervalMs * 1000);
        }

        // Timeout
        return false;
    }

    private function rollbackPlot(Plot $plot, int $companyId): void {
        $plot->company_id = $companyId;
        $plot->save();
    }
}
