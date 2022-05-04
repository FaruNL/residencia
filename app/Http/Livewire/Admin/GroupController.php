<?php

namespace App\Http\Livewire\Admin;

namespace App\Http\Livewire\Admin;

use App\Models\Group;
use Livewire\Component;
use Livewire\WithPagination;

class GroupController extends Component
{
    use WithPagination;

    public $group;
    public $perPage = '5';
    public $search = '';
    public $group_id;
    public $nombre;
    public $capacidad;
    // public $curso='Atque pariatur eveniet.',$grupo=24;

    public $edit = false;
    public $create = false;

    // variable para confimacion de eliminacion de registro
    public $isOpen = false;
    public $confirmingGroupDeletion = false;
    public $confirmingSaveGroup = false;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
        'perPage' => ['except' => 1, 'as' => 'p'],
    ];

    private function validateInputs()
    {
        $this->validate([
            'capacidad' => ['required', 'regex:/^[0-9]+$/'],
            'nombre' => ['required', 'regex:/^[a-zA-Z\sñáéíóúÁÉÍÓÚ]+$/'],

        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('search');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->edit = false;
        $this->confirmingSaveGroup = false;
        $this->create = true;
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->group_id = '';
        $this->nombre = '';
        $this->capacidad = '';
    }

    public function store()
    {
        $this->validateInputs();
        Group::updateOrCreate(['id' => $this->group_id], [
            'nombre' => $this->nombre,
            'capacidad' => $this->capacidad,
        ]);

        $this->dispatchBrowserEvent('notify', [
            'icon' => $this->edit ? 'pencil' : 'success',
            'message' =>  $this->edit ? 'Grupo actualizado exitosamente' : 'Grupo creado exitosamente',
        ]);

        $this->edit = false;
        $this->create = false;
        $this->closeModal();
        $this->resetInputFields();
    }

    public function updateGroup()
    {
        $this->validateInputs();
        $this->confirmingSaveGroup = true;
    }

    public function edit($id)
    {
        $group = Group::findOrFail($id);
        $this->nombre = $group->nombre;
        $this->capacidad = $group->capacidad;
        $this->edit = true;
        $this->create = false;
        $this->openModal();
        $this->confirmingSaveGroup = false;
        $this->validateInputs();
    }

    public function deleteGroup($id)
    {
        $this->group = Group::findOrFail($id);
        $this->confirmingGroupDeletion = true;
    }

    public function delete()
    {
        $this->group->delete();

        $this->confirmingGroupDeletion = false;

        $this->dispatchBrowserEvent('notify', [
            'icon' => 'trash',
            'message' =>  'Grupo eliminado exitosamente',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.groups.index', [
            'groups' => Group::where('nombre', 'like', '%'.$this->search.'%')
                     ->orWhere('capacidad', 'like', '%'.$this->search.'%')
                     ->paginate($this->perPage),
        ]);
    }
}