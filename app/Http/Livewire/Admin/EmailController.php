<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Traits\WithSearching;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarEmailCurso;
use App\Mail\OrderShipped;

Use App\Models\CourseDetail;
use Illuminate\Support\Arr;
use App\Models\Email;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EmailController extends Component
{
    use WithPagination;
    use WithSearching;

    public Email $correo;

    public $posts;
    // public $title='hola';
    // public $description;

    public $post;
    public $edit = false;
    public $create = false;
    public $show_edit_modal = 0;
    public $show_view_modal =false;
    public $confirming_part_deletion = false;
    public $confirming_save_parti = false;
    public $confirmin_notificacion=false;
    public $delete_todas_notifi= false;
    public $confirming_save_email=false;
    public $perPage = '8';
    public $arreglo=[];
    // public $search = '';
    protected array $cleanStringsExcept = ['search'];

    //atributos del mensaje
    public $arr=[
        'title' => '',
        'description' =>'',
        'role' =>'',
    ];

    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
        'perPage' => ['except' => 1, 'as' => 'p'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('search');
    }

    public function cursos($user,$unionarreglos){
        $count =0;
        foreach($unionarreglos as $curso){
            $algo= CourseDetail::join('courses','courses.id','=','course_details.course_id')
                    ->join('groups', 'groups.id', '=', 'course_details.group_id')
                    ->join('periods','periods.id','=','course_details.period_id')
                    ->select('courses.nombre as name', 'course_details.hora_inicio as horaini','course_details.lugar as lugar', 'course_details.hora_fin as horafin','groups.nombre as grupo', 'periods.clave as clave','periods.fecha_inicio as fi','periods.fecha_fin as ff')
                    ->where('course_details.id','=',$curso)->get();
            $this->arreglo[$count]=$algo;
            $count++;
        }

        $this->send($user);
    }

    public function send($user){

        Mail::to($user->email)
            ->send(new EnviarEmailCurso($this->arreglo, $user));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->open_modal();
        $this->edit = false;
        $this->create = true;
    }

    public function open_modal()
    {
        $this->show_edit_modal = true;
    }

    public function close_modal()
    {
        $this->show_edit_modal = false;
    }

    public function resetInputFields()
    {
        $this->reset('arr');
    }

    public function confirmation(){
        $this->confirming_save_email=true;
    }

    public function view($id)
    {
        $email = Email::findOrFail($id);
        $this->title= $email->title;
        $this->description= $email->description;
        // $this->role= $post->role;
        $this->show_view_modal = true;
    }

    protected $rules = [
        'arr.title' => ['required', 'regex:/^[A-Z,Ñ,a-z,1-9][A-Z,a-z, ,,1-9,ñ,Ñ,á,é,í,ó,ú,Á,É,Í,Ó,Ú]+$/', 'max:40'],
        'arr.description' => ['required', 'regex:/^[A-Z,Ñ,a-z,1-9][A-Z,a-z, ,,1-9,ñ,Ñ,á,é,í,ó,ú,Á,É,Í,Ó,Ú]+$/', 'max:100'],
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function consulta($destinatario)
    {
        if ($destinatario == 'Participante' || $destinatario == 'Instructor') {
            $user = User::where('users.estado', '=', '1')
            ->whereRelation('roles', 'name', '=', $destinatario)->get();
        }
        if ($destinatario == 'Todos') {
            $user = User::where('users.estado', '=', '1')
            ->whereHas('roles', function ($query) {
                $query->where('name', '=', 'Instructor')->orWhere('name', '=', 'Participante');
            })->get();
        }
        return $user;
    }

    public function store()
    {
        $this->validate();
        // $users=User::all();
        $iduser = Auth::id();
        $correo = Email::create($this->arr);
        $users = $this->consulta($this->arr['role']);

        // dd($users);
        foreach ($users as $u) {
            if ($u->id != $iduser) {
                Mail::to($u->email)
                    ->send(new OrderShipped($correo, $u));
            }
        }

        $this->dispatchBrowserEvent('notify', [
            'icon' => $this->edit ? 'pencil' : 'success',
            'message' => $this->edit ? 'Mensaje Enviado exitosamente' : 'Mensaje Enviado exitosamente',
        ]);

        $this->edit = false;
        $this->create = false;
        $this->confirming_save_email = false;
        $this->close_modal();
        $this->resetInputFields();
    }

    //eliminar todas las notificaciones enviadas
    public function delete_noti()
    {
        $this->confirmin_notificacion= true;
    }

    public function delete_notifications(){
        Email::all()->each->delete();
        $this->dispatchBrowserEvent('notify', [
            'icon' => 'trash',
            'message' =>  'Notificaciones eliminadas exitosamente!!!',
        ]);
        $this->confirmin_notificacion= false;
    }

    public function render()
    {
        return view('livewire.admin.emailss.index', [
            'emailss' => Email::query()
                            ->select('id','title','description','role','created_at',)
                            ->when($this->search, function ($query, $b) {
                                return $query->where(function ($q) {
                                    $q->Where('title', 'like', '%'.$this->search.'%')
                                      ->orWhere('description', 'like', '%'.$this->search.'%');
                                });
                            })
                            ->orderBy('emails.created_at', 'DESC')
                            ->paginate($this->perPage),
        ]);
    }

}
