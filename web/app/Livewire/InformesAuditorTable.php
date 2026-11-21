<?php 
namespace App\Livewire;

use App\Models\Informe;
use Livewire\Component;
use Livewire\WithPagination;

class InformesTable extends Component
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
                            'asigna.*')
                            ->orderBy('informes.' . $this->sortField, $this->sortDirection)
                            ->paginate($this->perPage);

        return view('livewire.informes-table', [
            'informes' => $informes,
        ]);
    }
}