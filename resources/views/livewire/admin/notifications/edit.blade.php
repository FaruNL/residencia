<x-jet-dialog-modal wire:ignore.self wire:model.defer="showEditModal">
    <x-slot name="title">
       Enviar Notificación
    </x-slot>
    <x-slot name="content">
        <x-jet-label for="x"  value="{{_('Los campos con * son obligatorios')}}" />
        <form  id="courseForm">

            <!-- Nombre -->
            <div class="mt-4">
                <x-jet-label for="title"  value="{{ __('Título*') }}" />
                <x-input.error wire:model="arr.title" class="block mt-1 w-full" type="text" id="title" name="title" for="title" required/>
                <x-jet-input-error for="arr.title"/>
            </div>

            <!-- Descripcion -->
            <div class="mt-4">
                <x-jet-label for="description" value="{{ __('Descripción*') }}"/>
                <x-input.textarea wire:model="arr.description" id="arr.description" class="block mt-1 w-full" name="arr.description" required/>
                <x-jet-input-error for="arr.description"/>
            </div>



        </form>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('showEditModal')" wire:loading.attr="disabled">
            Cancelar
        </x-jet-secondary-button>

        <x-jet-button class="ml-3" wire:click.prevent="confirmation()" wire:loading.attr="disabled" form="courseForm">
            enviar
        </x-jet-button>
        {{-- @if($confirmingSaveNotificacion)
                @include('livewire.admin.notifications.confirSenNotif')
        @endif --}}
    </x-slot>
</x-jet-dialog-modal>
