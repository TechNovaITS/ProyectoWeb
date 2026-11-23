<?php 
namespace App\Livewire;

use App\Models\Alerta;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AlertaAuditoriaTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
            $alertas = Alerta::query()
            ->withTrashed()
            ->when($this->search, function ($query) {
                $query->where('alerta', 'like', '%' . $this->search . '%'); 
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);


        return view('livewire.auditoria-alertas-table', [
            'alertas' => $alertas,
        ]);
    }
    public function exportCsv(): StreamedResponse
    {
        
        $fileName = 'alertas-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () {
            $alertas = Alerta::withTrashed()->get();
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 para que tildes y caracteres especiales se vean bien en Excel
            fwrite($handle, "\xEF\xBB\xBF");

            // Cabeceras
            fputcsv($handle, ['ID', 'Estado', 'Titulo', 'Alerta', 'Dispositivo ID', 'JOB', 'Departamento', 'Valor Actual', 'Sumario', 'Descripcion', 'Manual', 'Iniciado En', 'Finalizado En', 'URL Grafana', 'Estado Seguimiento', 'Creado En', 'Actualizado En','Finalizado En', 'URL Grafana', 'estado_seguimiento', 'deleted_at', 'created_at', 'updated_at']);

            // Filas en streaming con lazy()
            foreach ($alertas as $alerta) {
                fputcsv($handle, [
                    $alerta->id,
                    $alerta->estado,
                    $alerta->titulo,
                    $alerta->alerta,
                    $alerta->dispositivo_id,
                    $alerta->job,
                    $alerta->departamento,
                    $alerta->valor_actual,
                    $alerta->sumario,
                    $alerta->descripcion,
                    $alerta->manual,
                    $alerta->iniciado_en,
                    $alerta->finalizado_en,
                    $alerta->url_grafana,
                    $alerta->estado_seguimiento,
                    $alerta->created_at?->format('Y-m-d H:i'),
                    $alerta->updated_at?->format('Y-m-d H:i'),
                    $alerta->deleted_at?->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}