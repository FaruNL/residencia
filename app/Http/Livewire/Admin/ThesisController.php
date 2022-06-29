<?php

namespace App\Http\Livewire\Admin;

use App\Models\ThesisFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Traits\WithSearching;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Models\Thesis;

class ThesisController extends Component
{
    use WithPagination;
    use WithSearching;

    public $edit = false;
    public $create = false;
    public $showEditModal = false;

    public int $perPage = 5;

    protected $queryString = [
        'perPage' => ['except' => 5, 'as' => 'p'],
    ];

    public array $classification = [
        'curso' => '',
        'periodo' => '',
    ];

    public function render()
    {
        // return view('livewire.admin.submitpdf.thesis-controller',[
        //     'thesis' => Thesis::join('users','users.id','=','theses.user_id')
        //     ->join('inscriptions', 'inscriptions.user_id', '=', 'users.id')
        //     ->join('course_details as dt','dt.id','=','inscriptions.course_detail_id')
        //     ->join('courses', 'courses.id', '=', 'dt.course_id')
        //     ->join('periods', 'periods.id', '=', 'dt.period_id')
        //     ->where('inscriptions.estatus_participante', '=', 'Participante')
        //     ->where('dt.period_id', '=', $this->classification['periodo'])
        //     ->where('dt.course_id', '=', $this->classification['curso'])
        //     ->select('theses.id','theses.title','theses.state',DB::raw("concat(users.name,' ',users.apellido_paterno,
        //     ' ', users.apellido_materno) as nombre"),'users.name','users.apellido_paterno','users.apellido_materno','courses.nombre as curso')
        //     // ->get()
        //     // ->orderBy('posts.created_at', 'DESC')
        //     ->paginate($this->perPage),
        // ]);
        return view('livewire.admin.submitpdf.thesis-controller',[
        'thesis' => Thesis::join('course_details','course_details.id','=','theses.course_detail_id')
        ->join('courses', 'courses.id', '=', 'course_details.course_id')
        ->join('groups', 'groups.id', '=', 'course_details.group_id')
        ->join('periods', 'periods.id', '=', 'course_details.period_id')
        // ->join('inscriptions', 'inscriptions.course_detail_id','=', 'course_details.id')
        // ->join('users','users.id', '=', 'inscriptions.user_id')
        // ->where('inscriptions.estatus_participante', '=', 'Participante')
        // ->where('users.id', '=', 'theses.user_id')
        ->where('course_details.period_id', '=', $this->classification['periodo'])
        ->where('course_details.course_id', '=', $this->classification['curso'])
        ->select('theses.id','theses.title','theses.state','courses.nombre as curso')
        ->paginate($this->perPage),
    ]);

    }

    public function openModal()
    {
        $this->showEditModal = true;
    }

    public function create()
    {
        // $this->resetInputFields();
        $this->openModal();
        $this->edit = false;
        $this->create = true;
    }

    public function store(Request $request){
        $id = Auth::user()->id;
        $max_code = Thesis::select(
            DB::raw(' (IFNULL(MAX(RIGHT(thesis_code,7)),0)) AS number_max')
        )->first();

        $year = date('Y');
        $code = 'DOC'.$year.'-'.str_pad($max_code->number_max +1, 7, "0", STR_PAD_LEFT);



        $file = $request->file('file');

        if($file){
            $filename = $file->getClientOriginalName();
            $foo = \File::extension($filename);
            if($foo == 'pdf'){
                $route_file = $code.DIRECTORY_SEPARATOR.date('Ymdhmi').'.'.$foo;
                Storage::disk('public')->put($route_file,\File::get($file));
                $thesis = Thesis::create([
                    'thesis_code' => $code,
                    'title' => $request->input('title'),
                    'state' => ($request->input('state')?$request->input('state'):0),
                    'user_id' =>$id,
                    'course_detail_id'=> 3

                ]);
                ThesisFile::create([
                    'thesis_id' => $thesis->id,
                    'url' => $route_file,
                    'name' => $filename
                ]);
                return response()->json(['response' => [
                        'msg' => 'Registro Completado',
                        ]
                    ], 201);
            }else{
                return response()->json(['response' => [
                    'msg' => 'Solo Archivos PDF',
                    ]
                ], 201);
            }
        }

    }
    public function urlfile($thesis_id){
        $file = ThesisFile::where('thesis_id',$thesis_id)->where('state',1)->first();
        return response()->json(['response' => [
            'url' => $file->url,
            'name' => $file->name,
            ]
        ], 201);
    }

    public function update(Request $request){
        $id1 = Auth::user()->id;
        $id = $request->input('thesis_id');
        $code = $request->input('thesis_code');
        Thesis::where('id',$id)->update([
            'title' => $request->input('title'),
            'state' => ($request->input('state')?$request->input('state'):0),
            'user_id' => $id1,
            'course_detail_id'=> 3
        ]);

        ThesisFile::where('thesis_id',$id)->update(['state'=>0]);

        $file = $request->file('file');
        if($file){
            $filename = $file->getClientOriginalName();
            $foo = \File::extension($filename);
            if($foo == 'pdf'){
                $route_file = $code.DIRECTORY_SEPARATOR.date('Ymdhmi').'.'.$foo;
                Storage::disk('public')->put($route_file,\File::get($file));
                ThesisFile::create([
                    'thesis_id' => $id,
                    'url' => $route_file,
                    'name' => $filename
                ]);
                return response()->json(['response' => [
                        'msg' => 'Se actualizo Correctamente',
                        ]
                    ], 201);
            }else{
                return response()->json(['response' => [
                    'msg' => 'Solo Archivos PDF',
                    ]
                ], 201);
            }
        }

    }

    public function destroy($id){
        Thesis::where('id',$id)->delete();
        return response()->json(['response' => [
            'msg' => 'Eliminado correctamnete',
            ]
        ], 201);
    }
}
