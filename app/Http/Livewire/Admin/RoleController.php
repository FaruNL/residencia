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

    public int $perPage = 8;

    public bool $show_edit_createModal = false;
    public bool $show_view_modal = false;
    public bool $show_confirmation_modal = false;
    public bool $edit = false;
    public bool $delete = false;

    protected $queryString = [
        'perPage' => ['except' => 8, 'as' => 'p'],
    ];

    protected array $rules = [
        'role.name' => ['required', 'regex:/^[A-Z,Ñ,a-z,0-9][A-Z,a-z, ,,0-9,ñ,Ñ,.,á,é,í,ó,ú,Á,É,Í,Ó,Ú]+$/', 'max:40'],
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount()
    {
        $this->blank_role();
    }

    public function blank_role()
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

        $this->blank_role();

        $this->edit = false;
        $this->delete = false;
        $this->show_edit_createModal = true;
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
        $this->permissions = $this->get_permissions_ids();

        $this->edit = true;
        $this->delete = false;
        $this->show_edit_createModal = true;
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
        $this->show_confirmation_modal = true;
    }

    public function confirm_save()
    {
        $this->validate();
        $this->show_confirmation_modal = true;
    }

    public function save()
    {
        $this->role->syncPermissions($this->permissions);
        $this->role->save();

        $this->show_confirmation_modal = false;
        $this->show_edit_createModal = false;

        $this->dispatchBrowserEvent('notify', [
            'icon' => $this->edit ? 'pencil' : 'success',
            'message' => $this->edit ? 'Rol actualizado exitosamente' : 'Rol creado exitosamente',
        ]);
    }

    public function destroy()
    {
        $this->role->delete();
        $this->show_confirmation_modal = false;

        $this->dispatchBrowserEvent('notify', [
            'icon' => 'trash',
            'message' => 'Rol eliminado exitosamente',
        ]);
    }

    private function get_permissions_ids(): array
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
