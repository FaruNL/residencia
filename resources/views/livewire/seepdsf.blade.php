<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
            GENERACIÓN DE CONSTANCIAS
        </h2>

    </x-slot>

    <div class="max-w-7xl mx-auto pt-5 pb-10">
        <div class="space-y-2">

            <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-x-1.5 pb-6">
                <div class="mt-4 flex-1">
                    <x-jet-label value="Seleccione el periodo"/>
                    @livewire('admin.period-select')
                </div>
                <div class="mt-4 flex-1">
                    <x-jet-label value="Seleccione el curso"/>
                    @livewire('admin.course-details-select')
                </div>
            </div>

            {{-- </div> --}}

            <!-- Opciones de tabla -->
            <div class="md:flex md:justify-between space-y-2 md:space-y-0">
                <!-- Parte izquierda -->
                <div class="md:w-1/2 md:flex space-y-2 md:space-y-0 md:space-x-2">
                     <!-- Barra de búsqueda -->
                    <div class="w-full">
                        <x-input.icon wire:model="search" class="w-full" type="text" placeholder="Buscar participante...">
                           <x-icon.search solid class="h-5 w-5 text-gray-400"/>
                       </x-input.icon>
                        <label><p class="text-xs font-bold">Buscar por: Nombre, curso, grupo o calificación</p></label>
                    </div>

                    <!-- Filtros -->
                    <x-dropdown width="w-full" align="right" dropdownClasses="md:w-72" content-classes="py-4 bg-white divide-y">
                        <x-slot name="trigger">
                            <button class="inline-flex justify-center w-full rounded-md border hover:border-sky-400 shadow-sm px-2.5 py-2.5 bg-white font-medium focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" :class="open ? 'text-indigo-400 hover:text-indigo-500 border-sky-800' : 'text-gray-400 hover:text-gray-500 border-sky-800'">
                                @if(in_array(true, $filters))
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                @endif
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Reiniciar filtros -->
                            <div class="block px-4 py-2 space-y-1">
                                <button wire:click="resetFilters2()" @click="open = false" type="button" title="Reiniciar fitros"
                                        class="inline-flex justify-center w-full rounded-md border hover:border-red-400 shadow-sm px-2 py-2 bg-white text-gray-400 hover:text-red-400 font-medium focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                    <x-icon.trash class="h-5 w-5"/>
                                </button>
                            </div>
                            <!-- Departamento -->
                            <div class="block px-4 py-2 space-y-1">
                                <div>
                                    <x-jet-label for="departamento_filter" value="Departamento"/>
                                    <x-input.select wire:model="filters.departamento" id="departamento_filter" class="text-sm block mt-1 w-full" name="departamento_filter" required>
                                        <option value="" disabled>Selecciona departamento...</option>
                                        @foreach(\App\Models\Area::all() as $area)
                                                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                        @endforeach
                                    </x-input.select>
                                </div>
                            </div>

                             <!-- Grupo -->
                             <div class="block px-4 py-2 space-y-1">
                                <div>
                                    <x-jet-label for="grupo_filter" value="Grupo"/>
                                    <x-input.select wire:model="filters.grupo" id="grupo_filter" class="text-sm block mt-1 w-full" name="grupo_filter" required>
                                        <option value="" disabled>Selecciona grupo...</option>
                                        @foreach(\App\Models\CourseDetail::join('groups','groups.id','=','course_details.group_id')
                                            ->join('periods','periods.id','=', 'course_details.period_id')
                                            ->join('courses','courses.id','=', 'course_details.course_id')
                                            ->where('course_details.period_id','=',$classification['periodo'])
                                            ->where('course_details.course_id','=',$classification['curso'])
                                            ->select('course_details.group_id as id','groups.nombre')
                                            ->distinct()
                                            ->get() as $group)
                                            <option value="{{ $group->id }}">{{$group->nombre}}</option>
                                        @endforeach
                                    </x-input.select>
                                </div>
                            </div>

                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Parte derecha -->
                <div class="md:flex md:items-center space-y-2 md:space-y-0 md:space-x-2">
                    <!-- Selección de paginación -->
                    <div>
                        <x-input.select wire:model="perPage" class="block w-full border-sky-800 text-sky-700 hover:text-sky-500 active:text-sky-800 active:bg-sky-50">
                            <option value=5>5 por página</option>
                            <option value=10>10 por página</option>
                            <option value=25>25 por página</option>
                            <option value=50>50 por página</option>
                            <option value=100>100 por página</option>
                        </x-input.select>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="flex flex-col space-y-2">
                <x-table>
                    <x-slot name="head">
                        <x-table.header class="text-center">Participante</x-table.header>
                        <x-table.header class="text-center">curso</x-table.header>
                        <x-table.header class="text-center">grupo</x-table.header>
                        <x-table.header class="text-center">ver pdf</x-table.header>
                    </x-slot>

                    @forelse($calificaciones as $g)
                        <tr wire:key="calificaciones-{{$loop->index}}" wire:loading.class.delay="opacity-50">
                            <x-table.cell>
                                {{ $g->name }}{{' '}}{{ $g->apellido_paterno }}{{' '}}{{ $g->apellido_materno }}
                            </x-table.cell>
                            <x-table.cell class="text-center">{{ $g->curso }}</x-table.cell>
                            <x-table.cell class="text-center">{{ $g->grupo }}</x-table.cell>

                            <x-table.cell>
                                {{-- <button onclick="showFile({{ $item->id }})" type="button" class="text-amber-600 hover:text-amber-900">
                                    <x-icon.pencil alt class="h-6 w-6"/>
                                </button> --}}
                                {{-- <button type="button" class="btn btn-info" >Ver Cédula</button> --}}
                                <a href={{route('see-pdf', $g->url)}} class="text-gray-700 font-bold py-2 px-6">
                                    ver pdf
                                </a>
                            </x-table.cell>
                        </tr>
                    @empty
                        <tr>
                            <x-table.cell colspan="7">
                                <div class="flex justify-center items-center space-x-2">
                                    <!-- Icono -->
                                    <svg class="inline-block h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <!-- Texto -->
                                    <span class="py-4 text-xl text-gray-400 font-medium">
                                    No se encontraron registros ...
                                </span>
                                </div>
                            </x-table.cell>
                        </tr>
                    @endforelse
                </x-table>
                <div>
                    {{ $calificaciones->links()}}
                </div>

            </div>
        </div>
    </div>
</div>
