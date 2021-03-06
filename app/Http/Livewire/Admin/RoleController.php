<?php

namespace App\Http\Livewire\Admin;

use App\Http\Traits\WithSearching;
use App\Http\Traits\WithSorting;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class RoleController extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    use WithSearching;
    use WithSorting;

    public Role $role;
    public array $permissions = [];

    public int $perPage = 5;

    public bool $showEditCreateModal = false;
    public bool $showViewModal = false;
    public bool $showConfirmationModal = false;
    public bool $edit = false;
    public bool $delete = false;

    protected $queryString = [
        'perPage' => ['except' => 5, 'as' => 'p'],
    ];

    protected array $rules = [
        'role.name' => ['required'],
    ];

    public function mount()
    {
        $this->blankRole();
    }

    public function blankRole()
    {
        $this->role = Role::make();
        $this->reset('permissions');
    }

    /**
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('role.create');

        /* Reinicia los errores */
        $this->resetErrorBag();
        $this->resetValidation();

        $this->blankRole();

        $this->edit = false;
        $this->delete = false;
        $this->showEditCreateModal = true;
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Role $role)
    {
        $this->authorize('role.edit');

        /* Reinicia los errores */
        $this->resetErrorBag();
        $this->resetValidation();

        $this->role = $role;
        $this->permissions = $this->getPermissionsIds();

        $this->edit = true;
        $this->delete = false;
        $this->showEditCreateModal = true;
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(Role $role)
    {
        $this->authorize('role.delete');

        $this->role = $role;

        $this->edit = false;
        $this->delete = true;
        $this->showConfirmationModal = true;
    }

    public function confirmSave()
    {
        $this->validate();
        $this->showConfirmationModal = true;
    }

    public function save()
    {
        $this->role->syncPermissions($this->permissions);
        $this->role->save();

        $this->showConfirmationModal = false;
        $this->showEditCreateModal = false;

        $this->dispatchBrowserEvent('notify', [
            'icon' => $this->edit ? 'pencil' : 'success',
            'message' => $this->edit ? 'Rol actualizado exitosamente' : 'Rol creado exitosamente',
        ]);
    }

    public function destroy()
    {
        $this->role->delete();
        $this->showConfirmationModal = false;

        $this->dispatchBrowserEvent('notify', [
            'icon' => 'trash',
            'message' => 'Rol eliminado exitosamente',
        ]);
    }

    private function getPermissionsIds(): array
    {
        return $this->role->getAllPermissions()->pluck('id')->toArray();
    }

    public function render()
    {
        return view('livewire.admin.roles.index', [
            'roles' => Role::query()
                ->when($this->search, fn ($query, $search) => $query->where('name', 'like', "%$this->search%"))
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}
