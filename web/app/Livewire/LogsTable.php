<?php 
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Activity;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LogsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $searchCode = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'searchCode' => ['except' => ''],
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

    public function exportCsv(): StreamedResponse
    {
        
        $fileName = 'actividades-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () {
            $actividades = Activity::all();
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 para que tildes y caracteres especiales se vean bien en Excel
            fwrite($handle, "\xEF\xBB\xBF");

            // Cabeceras
            fputcsv($handle, ['ID', 'Log Name', 'Description', 'Subject Type', 'Subject ID', 'Evento', 'Causer Type', 'Ci Usuario', 'Attribute Changes', 'Properties', 'Creado', 'Actualizado']);

            // Filas en streaming con lazy()
            foreach ($actividades as $actividad) {
                fputcsv($handle, [
                    $actividad->id,
                    $actividad->log_name,
                    $actividad->description,
                    $actividad->subject_type,
                    $actividad->subject_id,
                    $actividad->event,
                    $actividad->causer_type,
                    $actividad->causer_id,
                    $actividad->attribute_changes,
                    $actividad->properties,
                    $actividad->created_at?->format('Y-m-d H:i'),
                    $actividad->updated_at?->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function render()
    {
        $actividades = Activity::query()
            ->when($this->search, function ($query) {
                $query->where('log_name', 'like', '%' . $this->search . '%');

            })
            ->when($this->searchCode, function ($query) {
                $query->where('causer_id', 'like', '%' . $this->searchCode . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.auditoria-actividad-table', [
            'actividades' => $actividades,
        ]);
    }
}