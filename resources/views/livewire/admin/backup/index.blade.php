<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#1b396a] leading-tight mb-4">
            IMPORTAR/EXPORTAR BD
        </h2>
    </x-slot>

    {{-- <div class="pb-10">
        <x-jet-button wire:click="import()" type="button" class="bg-[#1b396a]">
            IMPORT
        </x-jet-button> 
        <x-jet-button wire:click="export()" type="button" class="bg-[#1b396a]">
            EXPORT
        </x-jet-button> 
    </div> --}}
    {{-- <x-jet-button wire:click="comando()" type="button" class="bg-[#1b396a]">
            Generar copia de seguridad
        </x-jet-button> --}}

    <div class="grid grid-cols-2">
        <div>
            <label class="">Copia de seguridad</label>
            <x-jet-button wire:click="back()" type="button" class="bg-amber-600 ">
                Generar copia de seguridad
            </x-jet-button>
        </div>
        <div>
            <label class="">Selecciona un punto de restauración</label>
            <x-input.select wire:model="aux" id="perfil" class="" name="perfil" required>
                <option value="" selected="">Selecciona un punto de restauración...</option>
                <?php
                    $ruta = '../backup/';
                    if (is_dir($ruta)) {
                        if ($aux = opendir($ruta)) {
                            while (($archivo = readdir($aux)) !== false) {
                                if ($archivo != '.' && $archivo != '..') {
                                    $nombrearchivo = str_replace('.sql', '', $archivo);
                                    $nombrearchivo = str_replace('-', ':', $nombrearchivo);
                                    $ruta_completa = $ruta . $archivo;
                                    if (is_dir($ruta_completa)) {
                                    } else {
                                        echo '<option value="' . $ruta_completa . '">' . $nombrearchivo . '</option>';
                                    }
                                }   
                            }
                            closedir($aux);
                        }
                    } else {
                        echo $ruta . ' No es ruta válida';
                    }
                ?>
            </x-input.select>
            <x-jet-button wire:click="restore()" type="button" class="bg-amber-600">
                Restaurar copia de seguridad
            </x-jet-button>
        </div>



    </div>
</div>
