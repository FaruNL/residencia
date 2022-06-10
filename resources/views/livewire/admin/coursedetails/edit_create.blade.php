<x-jet-dialog-modal wire:ignore.self wire:model.defer="showEditCreateModal">
    <x-slot name="title">
        {{$modo}} detalles de curso
    </x-slot>
    <x-slot name="content">
        <form wire:submit.prevent="updateDetails()" id="courseForm">

            <!-- Nombre  Curso-->
            <div class="mt-4">
                <x-jet-label for="nombrec" value="Curso"/>
                <x-input.icon wire:model="busq" class="w-full" type="text" placeholder="Buscar Curso...">
                    <x-icon.search solid class="h-5 w-5 text-gray-400"/>
                </x-input.icon>
                <x-input.select wire:model="curso" name="curso" id="curso" class="text-sm block mt-1 w-full" required>
                    <option value="">Selecciones un Curso...</option>
                    @foreach($busqueda as $c)
                      <option value="{{$c->id}}">{{$c->nombre}}</option>
                    @endforeach
                </x-input.select>
            </div>

            <!-- Periodo y Hora inicio y Hora fin -->
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-x-1.5">
                <!-- Periodo -->
                <div class="mt-4 sm:flex-1">
                    <x-jet-label for="periodo" value="Periodo"/>
                    <x-input.select wire:model="period" name="fecha_inicio" id="fecha_inicio" class="text-sm block mt-1 w-full" required>
                        <option value="" disabled>Periodos</option>
                        @foreach(\App\Models\Period::all() as $period)
                          <option value="{{$period->id}}">{{date('d/m/Y', strtotime($period->fecha_inicio))}} a {{date('d/m/Y', strtotime($period->fecha_fin))}}</option>
                        @endforeach
                    </x-input.select>
                </div>

                <!-- Hora inicio -->
                <div class="mt-4 sm:flex-1">
                    <x-jet-label for="hora_inicio" value="Hora inicio"/>
                    <x-jet-input wire:model.defer="hora_inicio" class="block mt-1 w-full" type="time" id="hora_inicio" name="hora_inicio"/>
                    <x-jet-input-error for="hora_inicio"/>
                </div>

                <!-- Hora fin -->
                <div class="mt-4 sm:flex-1">
                    <x-jet-label for="hora_fin" value="Hora fin"/>
                    <x-jet-input wire:model.defer="hora_fin" class="block mt-1 w-full" type="time" id="hora_fin" name="hora_fin"/>
                    <x-jet-input-error for="hora_fin"/>
                </div>
            </div>

            <!-- Lugar -->
            <div class="mt-4">
                <x-jet-label for="lugar" value="Lugar"/>
                <x-input.error wire:model.defer="lugar" class="block mt-1 w-full" type="text" id="lugar" name="lugar" for="lugar" required/>
            </div>

            <!-- Capacidad y Grupo -->
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-x-1.5">

                <!-- Capacidad -->
                <div class="mt-4">
                    <x-jet-label for="capacidad" value="Capacidad" />
                    <x-input.error wire:model.defer="capacidad" class="block mt-1 w-full" type="number" id="capacidad" name="capacidad" for="capacidad" maxlength="2" required/>
                </div>

                <!-- Grupo -->
                <div class="mt-4 sm:flex-1">
                    <x-jet-label for="grupo" value="Grupo"/>
                    <x-input.select wire:model.defer="grupo_id" class="mt-1 w-full" id="grupo_id" name="grupo_id" required>
                        <option value="" disabled>Selecciona el grupo</option>
                        @foreach(\App\Models\Group::all() as $group)
                            <option value="{{ $group->id }}">{{ $group->nombre }}</option>
                        @endforeach
                    </x-input.select>
                </div>
            </div>
        </form>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('showEditCreateModal')" wire:loading.attr="disabled">
            Cancelar
        </x-jet-secondary-button>

        <x-jet-button class="ml-3" wire:loading.attr="disabled" form="courseForm">
            {{$modo}} Datos
        </x-jet-button>
        @if($confirmingSaveDetails)
                    @include('livewire.admin.coursedetails.confirmation')
        @endif
    </x-slot>
</x-jet-dialog-modal>
