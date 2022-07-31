<?php

namespace App\Http\Livewire\Admin;

use App\Events\PostEvent;
use App\notifications\PostNotification;
use Livewire\Component;
use App\Models\Post;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Http\Traits\WithSearching;

class PostController extends Component
{
    use WithPagination;
    use WithSearching;
    // public Post $post;


    public $posts;
    public $title;
    public Post $postt;

    //variables de modales
    public $edit = false;
    public $create = false;
    public $show_edit_modal = 0;
    public $show_view_modal =false;
    public $confirming_part_deletion = false;
    public $confirming_save_parti = false;
    public $confirmin_notificacion=false;
    public $delete_todas_notifi= false;
    public $confirming_save_notificacion=false;

    //Variables de busqueda y paginación
    public int $perPage = 8;
    protected array $cleanStringsExcept = ['search'];

    //atributos del mensaje
    public $arr=[
        'title' => '',
        'description' =>'',
        'post_id' =>'',
        'role' =>'',
    ];

    protected $queryString = [
        'perPage' => ['except' => 8, 'as' => 'p'],
    ];

    public function render()
    {
        return view('livewire.admin.notifications.index', [
            'postss' => Post::query()
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->select('posts.id','posts.title', 'posts.description','posts.role','posts.created_at')
            ->when($this->search, function ($query, $b) {
                return $query->where(function ($q) {
                    $q->Where('posts.title', 'like', '%'.$this->search.'%')
                    ->orWhere('posts.description', 'like', '%'.$this->search.'%')
                    ->orWhere('posts.role', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('posts.created_at', 'DESC')
            ->paginate($this->perPage),
        ]);
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

    // private function validateInputs()
    // {
    //     $this->validate([
    //         'arr.title' => ['required', 'regex:/^[A-Z,Ñ,a-z,1-9][A-Z,a-z, ,,1-9,ñ,Ñ,á,é,í,ó,ú,Á,É,Í,Ó,Ú]+$/', 'max:40'],
    //         'arr.description' => ['required', 'regex:/^[A-Z,Ñ,a-z,1-9][A-Z,a-z, ,,1-9,ñ,Ñ,á,é,í,ó,ú,Á,É,Í,Ó,Ú]+$/', 'max:100'],
    //         'arr.role' =>  ['required', 'regex:/^[Participante, Instructor, Todos]+$/', 'max:15'],
    //     ]);
    // }




    public function create()
    {
        $this->resetInputFields();
        $this->open_modal();
        $this->edit = false;
        $this->create = true;
    }

    public function view(Post $post)
    {
        $this->title = $post->title;
        $this->description= $post->description;
        $this->role= $post->role;
        $this->show_view_modal = true;
    }

    public function index()
    {
        $postNotifications = auth()->user()->unreadNotifications;
        return view('livewire.admin.notifications.view', compact('postNotifications'));
    }


    public function mark_notification(Request $request)
    {
        auth()->user()->unreadNotifications
                ->when($request->input('id'), function($query) use ($request){
                    return $query->where('id', $request->input('id'));
                })->mark_as_read();
        return response()->noContent();
    }

    //Eliminar un post
    public function delete_post($id, $title)
    {
        $this->posts = Post::findOrFail($id);
        $this->title = $title;
        $this->confirming_part_deletion = true;
    }

    public function delete()
    {
        $this->posts->delete();
        // Notification::where('data[1]', '=', $title)->each->delete();
        $this->confirming_part_deletion = false;
        $this->dispatchBrowserEvent('notify', [
            'icon' => 'trash',
            'message' =>  'Mensaje eliminado exitosamente',
        ]);
        $this->resetInputFields();
    }

    public function confirmation(){
        $this->confirming_save_notificacion=true;
    }

    protected $rules = [
        'arr.title' => ['required', 'regex:/^[A-Z,Ñ,a-z,0-9][A-Z,a-z, ,,0-9,ñ,Ñ,.,á,é,í,ó,ú,Á,É,Í,Ó,Ú]+$/', 'max:40'],
        'arr.description' => ['required', 'regex:/^[A-Z,Ñ,a-z,0-9][A-Z,a-z, ,,0-9,.,ñ,Ñ,á,é,í,ó,ú,Á,É,Í,Ó,Ú]+$/', 'max:250'],
        'arr.role' =>  ['required', 'regex:/^[Participante, Instructor, Todos]+$/', 'max:15'],
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        // $this->validateInputs();
        $this->validate();
        $this->arr['user_id']=Auth::id();
        $post=Post::create($this->arr);
        event(new PostEvent($post));

        $this->dispatchBrowserEvent('notify', [
            'icon' => $this->edit ? 'pencil' : 'success',
            'message' =>  $this->edit ? 'Mensaje Enviado exitosamente' : 'Mensaje Enviado exitosamente',
        ]);

        $this->edit = false;
        $this->create = false;
        $this->confirming_save_notificacion= false;
        $this->close_modal();
        $this->resetInputFields();
    }

    //eliminar todas las notificaciones enviadas
    public function delete_noti()
    {
        $this->confirmin_notificacion= true;
    }

    public function delete_notifications(){
        Notification::all()->each->delete();
        Post::all()->each->delete();
        $this->dispatchBrowserEvent('notify', [
            'icon' => 'trash',
            'message' =>  'Notificaciones eliminadas exitosamente!!!',
        ]);
        $this->confirmin_notificacion= false;
    }

    public function delete_todas_noti(){
        // $this->delete_todas_notifi=true;
        auth()->user()->Notifications->each->delete();
        $this->dispatchBrowserEvent('notify', [
            'icon' => 'trash',
            'message' =>  'Notificaciones eliminadas exitosamente!!!',
        ]);
    }

    // public function delete_notificationsleidas(){
    //     auth()->user()->Notifications->each->delete();
    //     $this->delete_todas_notifi= false;
    // }

    public function mark_as_read(){
        auth()->user()->unreadNotifications->mark_as_read();
        $this->dispatchBrowserEvent('notify', [
            'icon' => 'trash',
            'message' =>  'Notificaciones marcada como leídas',
        ]);
    }

    public function markone_as_read($id){
        auth()->user()->unreadNotifications->where('id', $id)->mark_as_read();
        $this->dispatchBrowserEvent('notify', [
            'icon' => 'trash',
            'message' =>  'Notificación marcada como leída',
        ]);
    }

    public function delet_full_notify_read(){
        auth()->user()->readNotifications->each->delete();
        $this->dispatchBrowserEvent('notify', [
            'icon' => 'trash',
            'message' =>  'Notificación leíadas eliminadas exitosamente...',
        ]);
    }
}

