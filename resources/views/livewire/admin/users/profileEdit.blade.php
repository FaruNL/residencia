<x-jet-dialog-modal wire:model.defer="showEditModal">
    <x-slot name="title">
        Editar Información
    </x-slot>

    <x-slot name="content">

        <form id="courseForm">
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-x-1.5">
                {{-- Nombre --}}
                <div class="mt-4 flex-1">
                    <x-jet-label for="nombre" value="Nombre" />
                    <x-input.error wire:model.defer="user.name" class="block mt-1 w-full" type="text" id="nombre" name="nombre" for="user.name" required />
                </div>
            </div>
                
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-x-1.5">
                <!-- paterno -->
                <div class="mt-4 flex-1">
                    <x-jet-label for="apellido_paterno" value="Apellido Paterno" />
                    <x-input.error wire:model.defer="user.apellido_paterno" class="block mt-1 w-full" type="text" id="apellido_paterno" name="apellido_paterno" for="user.apellido_paterno" required />
                </div>
                {{-- materno --}}
                <div class="mt-4 flex-1">
                    <x-jet-label for="apellido_materno" value="Apellido Materno" />
                    <x-input.error wire:model.defer="user.apellido_materno" class="block mt-1 w-full" type="text" id="apellido_materno" name="apellido_materno" for="user.apellido_materno" required />
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-x-1.5">
                <!-- rfc -->
                <div class="mt-4 flex-1">
                    <x-jet-label for="rfc" value="RFC" />
                    <x-input.error wire:model.defer="user.rfc" class="block mt-1 w-full" type="text" id="rfc" name="rfc" for="user.rfc" required />
                </div>
                {{-- Curp --}}
                <div class="mt-4 flex-1">
                    <x-jet-label for="curp" value="CURP" />
                    <x-input.error wire:model.defer="user.curp" class="block mt-1 w-full" type="text" id="curp" name="curp" for="user.curp" required />
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-x-1.5">
                {{-- ito --}}
                <div class="mt-4 flex-1">
                    <x-jet-label for="email" value="Correo @itoaxaca.edu.mx" />
                    <x-input.error wire:model.defer="user.email" class="block mt-1 w-full" type="email" id="email" name="email" for="user.email" required />
                </div>
                {{-- tecnm --}}
                <div class="mt-4 flex-1">
                    <x-jet-label for="correo_tecnm" value="Correo @oaxaca.tecnm.mx" />
                    <x-input.error wire:model.defer="user.correo_tecnm" class="block mt-1 w-full" type="email" id="correo_tecnm" name="correo_tecnm" for="user.correo_tecnm" required />
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-x-1.5">
                {{-- estudios maximos --}}
                <div class="mt-4 flex-1">
                    <x-jet-label for="estudio_maximo" value="Estudios Maximos" />
                    <x-input.error wire:model.defer="user.estudio_maximo" class="block mt-1 w-full" type="text" id="estudio_maximo" name="estudio_maximo" for="user.estudio_maximo" required />
                </div>
                {{-- sexo --}}
                <div class="mt-4 flex-1">
                    <x-jet-label for="sexo" value="Sexo" />
                    <x-input.select wire:model.defer="user.sexo" id="sexo" class="mt-1 w-full" name="sexo" for="user.sexo" required>
                        <option value="" disabled>Selecciona Sexo...</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </x-input.select>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-x-1.5">
                {{-- carrera --}}
                <div class="mt-4 flex-1">
                    <x-jet-label for="carrera" value="Carrera" />
                    <x-input.error wire:model.defer="user.carrera" class="block mt-1 w-full" type="text" id="carrera" name="carrera" for="user.carrera" required />
                </div>
            </div>

    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="closeM" wire:loading.attr="disabled">
            Cancelar
        </x-jet-secondary-button>

        <x-jet-button class="ml-3" wire:click.prevent="confirmSave()" wire:loading.attr="disabled">
            Confirmar
        </x-jet-button>
    </x-slot>
</x-jet-dialog-modal>
