<?php 
namespace App\Livewire;

use App\Models\Usuario;
use Livewire\Component;
use Livewire\WithPagination;

class UsuarioTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'ci';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'ci'],
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
            $usuarios = Usuario::query()
                ->where('rol', '!=', 'Administrador')
                ->where('rol', '!=', 'Recovery')
                ->when($this->search, function ($query) {
                    $query->where('email', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);
        } else if (auth()->user()->rol === 'Recovery') {
            $usuarios = Usuario::query()
                ->where('rol', '!=', 'Recovery')
                ->where('rol', '!=', 'Operador')
                ->where('rol', '!=', 'Auditor')
                ->when($this->search, function ($query) {
                    $query->where('email', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);
        }

        return view('livewire.usuario-table', [
            'usuarios' => $usuarios,
        ]);
    }
}