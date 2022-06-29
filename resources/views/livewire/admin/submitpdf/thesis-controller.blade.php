<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
            pdfs
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto pt-5 pb-10">
        <div class="space-y-2">
            <!-- Botón de nuevo -->
            <div>
                <x-jet-secondary-button wire:click="create()" class="border-sky-800 text-sky-700 hover:text-sky-500 active:text-sky-800 active:bg-sky-50">
                    <x-icon.plus solid alt="sm" class="inline-block h-5 w-5"/>
                    Subir archivo
                </x-jet-secondary-button>
            </div>
            <div class="mt-1 md:w-1/5">
                <x-jet-label for="periodo" value="Periodo"/>
                <x-input.select wire:model="classification.periodo" id="periodo" class="text-sm block mt-1 w-full" name="periodo" required>
                    <option   value="" disabled>Selecciona el periodo...</option>
                    @foreach(\App\Models\Period::all() as $period)
                            <option value="{{ $period->id }}">{{$period->clave}}</option>
                    @endforeach
                </x-input.select>
            </div>

            <!-- Curso -->
            <div class="mt-1 w-1/2 ">
                <x-jet-label for="curso_classification" value="Curso"/>
                <x-input.select wire:model="classification.curso" id="curso" class="text-sm block mt-1 w-full" name="curso" required>
                    <option value="">Selecciona el curso...</option>
                    @foreach(\App\Models\CourseDetail::join('courses','courses.id','=','course_details.course_id')
                        ->join('periods','periods.id','=', 'course_details.period_id')
                        ->where('course_details.period_id','=',$classification['periodo'])
                        ->select('course_details.course_id as id','courses.nombre')
                        ->distinct()
                        ->get() as $course)
                        <option value="{{ $course->id }}">{{$course->nombre}}</option>
                    @endforeach
                </x-input.select>
            </div>

            <!-- Opciones de tabla -->
            <div class="md:flex md:justify-between space-y-2 md:space-y-0">
                <!-- Parte izquierda -->
                <div class="md:w-1/2 md:flex space-y-2 md:space-y-0 md:space-x-2">
                    <!-- Barra de búsqueda -->
                    {{-- <x-input.icon wire:model="search" class="w-full" type="text" placeholder="Buscar usuario...">
                        <x-icon.search solid class="h-5 w-5 text-gray-400"/>
                    </x-input.icon> --}}
                </div>

                <!-- Parte derecha -->
                <div class="md:flex md:items-center space-y-2 md:space-y-0 md:space-x-2">
                    <!-- Selección de paginación -->
                    <div>
                        <x-input.select wire:model="perPage" class="block w-full">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                        </x-input.select>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="flex flex-col space-y-2">
                <x-table>
                    <x-slot name="head">
                        {{-- <x-table.header>Nombre del Participante</x-table.header> --}}
                        <x-table.header>curso</x-table.header>
                        <x-table.header>Titulo</x-table.header>
                        <x-table.header>Estado</x-table.header>
                    </x-slot>

                    @forelse($thesis as $item)
                        {{-- <tr wire:key="user-{{ $item->id }}" wire:loading.class.delay="opacity-50"> --}}
                            {{-- <x-table.cell>{{ $item->nombre }}</x-table.cell> --}}
                            <x-table.cell>{{ $item->curso }}</x-table.cell>
                            <x-table.cell>{{ $item->title }}</x-table.cell>
                            <x-table.cell>{{ $item->state }}</x-table.cell>
                            <x-table.cell>
                                {{-- <button onclick="showFile({{ $item->id }})" type="button" class="text-amber-600 hover:text-amber-900">
                                    <x-icon.pencil alt class="h-6 w-6"/>
                                </button> --}}
                                <button type="button" class="btn btn-info" onclick="showFile('{{ $item->id }}')">Ver Cédula</button>
                            </x-table.cell>
                        </tr>
                    @empty
                        <tr>
                            {{-- Cambia el número según el numero de columnas --}}
                            <x-table.cell colspan="3">
                                <div class="flex justify-center items-center space-x-2">
                                    <!-- Icono -->
                                    <svg class="inline-block h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <!-- Texto -->
                                    <span class="py-4 text-xl text-gray-400 font-medium">
                                    No se encontraron resultados ...
                                </span>
                                </div>
                            </x-table.cell>
                        </tr>
                    @endforelse
                </x-table>
                <div>
                    {{ $thesis->links() }}
                </div>

            </div>
        </div>
        @if($create)
        @include('livewire.admin.submitpdf.create')
        @endif
    </div>

<!-- Modal -->
<form enctype="multipart/form-data" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @csrf
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Nueva Tesis</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="title">Titulo</label>
                    <input type="text" class="form-control" id="title" name="title">

                </div>
                <div class="form-group">
                    <label for="exampleFormControlFile1">Archivo</label>
                    <input type="file" class="form-control-file" id="exampleFormControlFile1" name="file">
                  </div>
                <div class="form-group form-check">
                    <input type="checkbox" value="1" checked class="form-check-input" id="exampleCheck1" name="state">
                    <label class="form-check-label" for="exampleCheck1">Activo</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btn-register">Guardar</button>
            </div>
        </div>
    </div>
</form>
</div>


<script>
    function modalEdit(id,tit,est,cod){
        $('#title-edit').val(tit);
        $('#state-edit').val(est);
        $('#thesis_id').val(id);
        $('#thesis_code').val(cod);
    }
</script>
{{-- <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> --}}
    <script>
        $("#btn-register" ).click(function() {
            var formData = new FormData(document.getElementById("exampleModal"));
            $.ajax({
                url: "{{ route('thesis_register') }}",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(res){
                msg = JSON.parse(res).response.msg
                alert(msg);
                location.reload();
            }).fail(function(res){
                console.log(res)
            });
        });
        function showFile(id){
            $.ajax({
                url: "{{ asset('/thesis/file/') }}/"+id,
                type: "get",
                dataType: "html",
                contentType: false,
                processData: false
            }).done(function(res){
                url = JSON.parse(res).response.url
                window.open('storage/'+url,'_blank');
            }).fail(function(res){
                console.log(res)
            });
        }
        $( "#btn-update" ).click(function() {
            var formData = new FormData(document.getElementById("exampleModalEdit"));
            $.ajax({
                url: "{{ route('thesis_update') }}",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(res){
                msg = JSON.parse(res).response.msg
                alert(msg);
                location.reload();
            }).fail(function(res){
                console.log(res)
            });
        });
        // function deleteThesis(id){
        //     $.ajax({
        //         url: "{{ asset('/thesis/delete/') }}/"+id,
        //         type: "get",
        //         dataType: "html",
        //         contentType: false,
        //         processData: false
        //     }).done(function(res){
        //         msg = JSON.parse(res).response.msg
        //         alert(msg);
        //         location.reload();
        //     }).fail(function(res){
        //         console.log(res)
        //     });
        // }
    </script>
