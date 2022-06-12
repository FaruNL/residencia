<x-jet-dialog-modal wire:ignore.self wire:model.defer="showViewModal">
    <x-slot name="title">
        <strong>Notificación {{$title}} enviada.</strong>
    </x-slot>
    <x-slot name="content">
        {{-- <form  id="courseForm"> --}}
            <div class="mt-4">
                <x-jet-label value="Titulo"/>
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
        <x-jet-button wire:click="$toggle('showViewModal')" wire:loading.attr="disabled" >
            Hecho
        </x-jet-button>
    </x-slot>
</x-jet-dialog-modal>