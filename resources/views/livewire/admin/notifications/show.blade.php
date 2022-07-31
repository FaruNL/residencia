<x-jet-dialog-modal wire:ignore.self wire:model.defer="show_view_modal">
    <x-slot name="title">
        <strong>Notificación enviada.</strong>
    </x-slot>
    <x-slot name="content">
        {{-- <form  id="courseForm"> --}}
            <div class="mt-4">
                <x-jet-label value="Título"/>
                <x-jet-input wire:model.defer="title" class="block mt-1 w-full" type="text" disabled/>
            </div>

            <!-- Descripcion -->
            <div class="mt-4">
                <x-jet-label for="description" value="{{ __('Descripción') }}"/>
                <x-input.textarea wire:model.defer="description" id="arr.description" class="block mt-1 w-full" name="description" disabled/>
            </div>
            <div class="mt-4">
                <x-jet-label value="Para"/>
                <x-jet-input wire:model.defer="role" class="block mt-1 w-full" type="text" disabled/>
            </div>
        {{-- </form> --}}
    </x-slot>

    <x-slot name="footer">
        <x-jet-button wire:click="$toggle('show_view_modal')" wire:loading.attr="disabled" >
            Hecho
        </x-jet-button>
    </x-slot>
</x-jet-dialog-modal>
