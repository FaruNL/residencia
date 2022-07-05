<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ejemplo de subir PDF
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto pt-5 pb-10">
        <div class="space-y-2">

            <form wire:submit.prevent="savePdf()">
                <div class="flex justify-center items-center w-full">
                    <label for="file-input" class="flex flex-col justify-center items-center w-full bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed cursor-pointer">
                        <div class="flex flex-col justify-center items-center pt-5 pb-6">
                            <svg class="mb-3 w-10 h-10 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="mb-2 text-sm text-sky-800"><span class="font-semibold">Subir cédula de inscripción firmada</span></p>
                            @if(isset($pdf))
                                <span class="mb-2 text-sm text-sky-600">{{ $pdf->getClientOriginalName() }}</span>
                            @endif
                            <p class="text-xs text-gray-500">PDF <span class="italic">(max 2 MB)</span></p>
                        </div>
                        <x-jet-input-error for="pdf"/>
                        <input wire:model="pdf" id="file-input" type="file" class="hidden" />
                    </label>
                </div>

                <div class="flex justify-center">
                    <x-jet-button class="mt-3" wire:loading.attr="disabled">
                        Subir
                    </x-jet-button>
                </div>
            </form>

        </div>
    </div>
</div>
