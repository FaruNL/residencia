<x-jet-dialog-modal wire:ignore.self wire:model.defer="showEditCreateModal">
    <x-slot name="title">
        {{ $edit ? 'Editar usuario' : 'Crear usuario' }}
    </x-slot>
    <x-slot name="content">
        <form wire:submit.prevent="confirmSave()" id="userForm">
            <!-- Nombre y Apellidos -->
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-x-1.5">
                <!-- Nombre -->
                <div class="sm:flex-1">
                    <x-jet-label for="nombre" value="Nombre"/>
                    <x-input.error wire:model.defer="user.name" class="block mt-1 w-full" type="text" id="nombre" name="nombre" for="user.name" required/>
                </div>

                <!-- Apellido paterno -->
                <div class="mt-4 sm:mt-0 sm:flex-1">
                    <x-jet-label for="apellido_paterno" value="Apellido paterno"/>
                    <x-input.error wire:model.defer="user.apellido_paterno" class="block mt-1 w-full" type="text" id="apellido_paterno" name="apellido_paterno" for="user.apellido_paterno"/>
                </div>

                <!-- Apellido materno -->
                <div class="mt-4 sm:mt-0 sm:flex-1">
                    <x-jet-label for="apellido_materno" value="Apellido materno"/>
                    <x-input.error wire:model.defer="user.apellido_materno" class="block mt-1 w-full" type="text" id="apellido_materno" name="apellido_materno" for="user.apellido_materno"/>
                </div>
            </div>

            <!-- Correo y rol -->
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-x-1.5">
                <!-- Correo -->
                <div class="mt-4 sm:flex-1">
                    <x-jet-label for="email" value="Correo"/>
                    <x-input.error wire:model.defer="user.email" class="block mt-1 w-full" type="email" id="email" name="email" for="user.email" required/>
                </div>

                <!-- Rol -->
                <div class="mt-4 sm:flex-1">
                    <x-jet-label for="rol" value="Rol"/>
                    <x-input.select wire:model.defer="role" id="rol" class="mt-1 w-full" name="rol">
                        <option value="" disabled>Selecciona rol...</option>
                        @foreach(\Spatie\Permission\Models\Role::all() as $role)
                            <option value="{{ $role->name }}">{{ ucwords($role->name) }}</option>
                        @endforeach
                    </x-input.select>
                </div>
            </div>

            <!-- Contrase??as -->
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-x-1.5">
                <!-- Contrase??a -->
                <div class="mt-4 sm:flex-1">
                    <x-jet-label for="contrase??a" :value="$edit ? 'Contrase??a nueva' : 'Contrase??a'"/>
                    <x-input.error wire:model.defer="password" class="block mt-1 w-full" type="password" id="contrase??a" name="contrase??a" for="password"/>
                </div>

                <!-- Confirmaci??n de contrase??a -->
                <div class="mt-4 sm:flex-1">
                    <x-jet-label for="contrase??a_confirmation" value="Confirmaci??n de contrase??a"/>
                    <x-input.error wire:model.defer="password_confirmation" class="block mt-1 w-full" type="password" id="contrase??a_confirmation" name="contrase??a_confirmation" for="password_confirmation"/>
                </div>
            </div>
        </form>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('showEditCreateModal')" wire:loading.attr="disabled">
            Cancelar
        </x-jet-secondary-button>

        <x-jet-button class="ml-3" wire:loading.attr="disabled" form="userForm">
            {{ $edit ? 'Editar' : 'Crear' }}
        </x-jet-button>
    </x-slot>
</x-jet-dialog-modal>
