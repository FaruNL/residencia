<x-jet-dialog-modal wire:model="is_open">
    <x-slot name="title">
        Actualizar Calificación
    </x-slot>
    <x-slot name="content">
        <x-jet-label for="x"  value="Los campos con * son obligatorios" />
        <form  id="courseForm">
            <!-- Clave y Grupo -->
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-x-1.5">
                <!-- Curso -->
                <div class="mt-4 sm:flex-1">
                    <x-jet-label for="curso" value="Curso" />
                    <x-input.error readonly  wire:model="curso" class="block mt-1 w-full" type="text" id="curso" name="curso" for="curso"/>
                </div>
                <!-- Grupo -->
                <div class="mt-4">
                    <x-jet-label for="grupo" value="Grupo" />
                    <x-input.error readonly  wire:model="grupo" class="block mt-1 w-full" type="text" id="grupo" name="grupo" for="grupo"/>
                </div>
            </div>
                <!-- Participante -->
                <div class="mt-4 ">
                    <x-jet-label for="nombre" value="Participante" />
                    <x-input.error readonly wire:model="participante" class="block mt-1 w-full" type="text" id="participante" name="participante" for="participante" />
                </div>

                <!-- Calificación -->
                <div class="mt-4">
                    <x-jet-label for="calificacion" value="Calificación" />
                    <x-input.error wire:model.defer="calificacion" class="block mt-1 w-full" type="number" id="calificacion"
                    name="calificacion" for="calificacion" min="0" max="100" required/>
                </div>

                <!-- Calificación -->
                <div class="mt-4">
                    <x-jet-label for="asistencia" value="¿Cumple con el número de asistencias mínimas?" />
                    <label for="asistencias">Sí</label>
                    <input type="radio" wire:model="asistencias_minimas" value="1" id="opcion" name="opcion" required>
                    <label for="asistencias">No</label>
                    <input type="radio" wire:model="asistencias_minimas" value="0" id="opcion" name="opcion" required>
                </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('is_open')" wire:loading.attr="disabled">
            Cancelar
        </x-jet-secondary-button>

        <x-jet-button wire:click.prevent="update_grade()" class="ml-3"  wire:loading.attr="disabled" form="courseForm">
           Actualizar Datos
        </x-jet-button>
        @if($confirming_save_grade)
                    @include('livewire.admin.grades.confirmation')
        @endif
    </x-slot>
</x-jet-dialog-modal>
