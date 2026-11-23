<?php 
namespace App\Livewire;

use App\Models\Informe;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InformesAuditorTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'alerta'],
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
        $informes = Informe::query()
                            ->withTrashed()
                            ->join('asigna', 'informes.id_alerta', '=', 'asigna.id_alerta')
                            ->join('alertas', 'asigna.id_alerta', '=', 'alertas.id')
                            ->when($this->search, function ($query) {
                                $query->where('informes.id_alerta', 'like', '%' . $this->search . '%');
                            })
                            ->select('informes.*',
                            'asigna.*',
                            'alertas.alerta'
                            )
                            ->orderBy('informes.' . $this->sortField, $this->sortDirection)
                            ->paginate($this->perPage);

        return view('livewire.auditoria-informe-table', [
            'informes' => $informes,
        ]);
    }
        public function exportCsv(): StreamedResponse
    {
        
        $fileName = 'informes-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () {
            $informes = Informe::leftJoin('asigna', 'informes.id_alerta', '=', 'asigna.id_alerta')
                        ->withTrashed()
                        ->select('informes.*',
                        'asigna.ci_usuario',
                        'asigna.updated_at as asigna_updated_at',
                        'asigna.created_at as asigna_created_at')
                        ->get();
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 para que tildes y caracteres especiales se vean bien en Excel
            fwrite($handle, "\xEF\xBB\xBF");

            // Cabeceras
            fputcsv($handle, ['ID', 'ID_Alerta', 'CI Usuario', 'Causa', 'Resolucion','deleted_at', 'created_at', 'updated_at', 'asigna_created_at', 'asigna_updated_at']);

            // Filas en streaming con lazy()
            foreach ($informes as $informe) {
                fputcsv($handle, [
                    $informe->id,
                    $informe->id_alerta,
                    $informe->ci_usuario,
                    $informe->causa,
                    $informe->resolucion,
                    $informe->deleted_at?->format('Y-m-d H:i'),
                    $informe->created_at?->format('Y-m-d H:i'),
                    $informe->updated_at?->format('Y-m-d H:i'),
                    $informe->asigna_created_at,
                    $informe->asigna_updated_at,
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}