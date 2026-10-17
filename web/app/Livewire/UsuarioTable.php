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
        $usuarios = Usuario::query()
            ->when($this->search, function ($query) {
                $query->where('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.usuario-table', [
            'usuarios' => $usuarios,
        ]);
    }
}