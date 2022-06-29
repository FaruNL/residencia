<x-jet-dialog-modal wire:ignore.self wire:model.defer="showEditModal">
    <x-slot name="title">
       Subor archivo
    </x-slot>
    <x-slot name="content">
        <x-jet-label for="x"  value="{{_('Los campos con * son obligatorios')}}" />
        <form  enctype="multipart/form-data" class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

            <!-- Nombre -->

            <div class="mt-4">
                <x-jet-label for="title"  value="{{ __('Título*') }}" />
                {{-- <x-input.error wire:model="arr.title" class="block mt-1 w-full" type="text" id="title" name="title" for="title" required/> --}}

                 <input type="text" class="form-control" id="title" name="title">
                {{-- <x-jet-input-error for="arr.title"/> --}}
            </div>

            <div class="form-group form-check">
                <input type="checkbox" value="1" checked class="form-check-input" id="exampleCheck1" name="state">
                <label class="form-check-label" for="exampleCheck1">Activo</label>
            </div>
            <!-- Descripcion -->
            <div class="mt-4">
                <x-jet-label for="description" value="{{ __('Archivo*') }}"/>
                {{-- <x-input.textarea wire:model="arr.description" id="arr.description" class="block mt-1 w-full" name="arr.description" required/> --}}
                <input type="file" class="form-control-file" id="exampleFormControlFile1" name="file">
            </div>


        </form>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('showEditModal')" wire:loading.attr="disabled">
            Cancelar
        </x-jet-secondary-button>

        {{-- <x-jet-button class="ml-3" id="btn-register" wire:loading.attr="disabled" form="courseForm">
            enviar
        </x-jet-button> --}}
        <button type="button" class="btn btn-primary" id="btn-register">Guardar</button>
        {{-- @if($confirmingSaveNotificacion)
                @include('livewire.admin.notifications.confirSenNotif')
        @endif --}}
    </x-slot>
</x-jet-dialog-modal>
<script>
    function modalEdit(id,tit,est,cod){
        $('#title-edit').val(tit);
        $('#state-edit').val(est);
        $('#thesis_id').val(id);
        $('#thesis_code').val(cod);
    }
</script>
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
