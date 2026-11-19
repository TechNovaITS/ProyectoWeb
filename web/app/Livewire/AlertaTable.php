<?php 
namespace App\Livewire;

use App\Models\Alerta;
use Livewire\Component;
use Livewire\WithPagination;

class AlertaTable extends Component
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
        if (auth()->user()->rol === 'Administrador') {
            $alertas = Alerta::query()
            ->where('estado_seguimiento', 'Pendiente')
            ->when($this->search, function ($query) {
                $query->where('alerta', 'like', '%' . $this->search . '%'); 
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        } else {
            $departamento = auth()->user()->belongstoDepartamento->nom_departamento;
            $alertas = Alerta::query()
            ->where('estado_seguimiento', 'Pendiente')
            ->where('departamento', $departamento)
            ->when($this->search, function ($query) {
                $query->where('alerta', 'like', '%' . $this->search . '%'); 
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        }
        

        return view('livewire.alerta-table', [
            'alertas' => $alertas,
        ]);
    }
}