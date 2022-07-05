<?php

namespace App\Http\Livewire;

use App\Http\Traits\WithFilters;
use App\Http\Traits\WithSearching;
use App\Http\Traits\WithSorting;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Seepdsf extends Component
{
    use WithFilters;
    use WithPagination;
    use WithSearching;
    use WithSorting;

    public array $classification = [
        'curso' => '',
        'periodo' => '',
    ];

    public $perPage = '5';
    public array $cleanStringsExcept = ['search'];
    public array $filters = [
        'grupo' => '',
        'departamento' => '',
        'filtro_calificacion' => '',
    ];

    public $queryString = [
        'perPage' => ['except' => 5, 'as' => 'p'],
    ];

    public function render()
    {
        return view('livewire.seepdsf', [
            'calificaciones' => User::join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
                    ->join('areas', 'areas.id', '=', 'users.area_id')
                    ->join('course_details', 'course_details.id', 'inscriptions.course_detail_id')
                    ->join('courses', 'courses.id', '=', 'course_details.course_id')
                    ->join('groups', 'groups.id', '=', 'course_details.group_id')
                    ->join('archives', 'archives.user_id', '=', 'users.id')
                    ->join('periods', 'periods.id', '=', 'course_details.period_id')
                    ->where('inscriptions.estatus_participante', '=', 'Participante')
                    ->where('course_details.period_id', '=', $this->classification['periodo'])
                    ->where('course_details.course_id', '=', $this->classification['curso'])
                    ->select(['inscriptions.id','users.id as iduser','course_details.id as idcurse',  DB::raw("concat(users.name,' ',users.apellido_paterno,' ', users.apellido_materno) as nombre"),'users.name', 'users.apellido_paterno', 'users.apellido_materno',
                        'courses.nombre as curso', 'groups.nombre as grupo', 'inscriptions.calificacion', 'areas.nombre as area','periods.fecha_inicio as fi', 'periods.fecha_fin as ff','courses.duracion as duracion','archives.url as url'])
                    ->when($this->search, fn ($query, $search) => $query->where(DB::raw("concat(users.name,' ',users.apellido_paterno,' ', users.apellido_materno)"), 'like', "%$search%")
                        ->orWhere('courses.nombre', 'like', '%'.$this->search.'%')
                        ->orWhere('groups.nombre', 'like', '%'.$this->search.'%')
                        ->orWhere('inscriptions.calificacion', 'like', '%'.$this->search.'%'))
                    ->when($this->filters['grupo'], fn ($query, $grupo) => $query->where('course_details.group_id', '=', $grupo))
                    ->when($this->filters['departamento'], fn ($query, $depto) => $query->where('users.area_id', '=', $depto))

                    ->orderBy($this->sortField, $this->sortDirection)
                    ->paginate($this->perPage),
        ]);
    }

    public function resetFilters2()
    {
        $this->reset('filters');
    }


    protected $listeners = [
        'per_send',
        'data_send',
    ];
    public function per_send($valor){
        $this->classification['periodo'] = $valor;
    }
    public function data_send($valor){
        $this->classification['curso'] = $valor;
    }



    private function consultaBase()
    {
        return User::join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
            ->join('areas', 'areas.id', '=', 'users.area_id')
            ->join('course_details', 'course_details.id', 'inscriptions.course_detail_id')
            ->join('courses', 'courses.id', '=', 'course_details.course_id')
            ->join('groups', 'groups.id', '=', 'course_details.group_id')
            ->join('periods', 'periods.id', '=', 'course_details.period_id')
            ->where('inscriptions.estatus_participante', '=', 'Participante')
            ->where('course_details.period_id', '=', $this->classification['periodo'])
            ->where('course_details.course_id', '=', $this->classification['curso'])
            ->select(['inscriptions.id','users.id as iduser','course_details.id as idcurse',  DB::raw("concat(users.name,' ',users.apellido_paterno,' ', users.apellido_materno) as nombre"),'users.name', 'users.apellido_paterno', 'users.apellido_materno',
                'courses.nombre as curso', 'groups.nombre as grupo', 'inscriptions.calificacion', 'areas.nombre as area','periods.fecha_inicio as fi', 'periods.fecha_fin as ff','courses.duracion as duracion']);
    }

    public function consulta($iduser, $idcurse){
        app(\App\Http\Livewire\FileUpload::class)->buscar($iduser, $idcurse);
    }
}
