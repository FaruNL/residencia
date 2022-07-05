<?php

use App\Http\Livewire\Admin\ActivatePeriodController;
use App\Http\Livewire\Admin\ActiveInscriptionController;
use App\Http\Livewire\Admin\AreaController;
use App\Http\Livewire\Admin\AssignedInstructorController;
use App\Http\Livewire\Admin\ConstanciasController;
use App\Http\Livewire\Admin\CourseController;
use App\Http\Livewire\Admin\CourseDetailsController;
use App\Http\Livewire\Admin\GradeController;
use App\Http\Livewire\Admin\GroupController;
use App\Http\Livewire\Admin\HistoryCourseController;
use App\Http\Livewire\Admin\HistoryInstructorController;
use App\Http\Livewire\Admin\HistoryParticipantController;
use App\Http\Livewire\Admin\InscriptionsController;
use App\Http\Livewire\Admin\InstructorCurseController;
use App\Http\Livewire\Admin\ParticipantController;
use App\Http\Livewire\Admin\ParticipantListsController;
use App\Http\Livewire\Admin\PeriodCoursesController;
use App\Http\Livewire\Admin\ProfileController;
use App\Http\Livewire\Admin\RoleController;
use App\Http\Livewire\Admin\StudyingController;
use App\Http\Livewire\Admin\UserController;
use App\Http\Livewire\Admin\PostController;
use App\Http\Livewire\Admin\TeachingController;
use App\Http\Livewire\Admin\ConstanciaInstructorController;
use App\Http\Livewire\Admin\EmailController;
use App\Http\Livewire\Seepdsf;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;

// Use App\Models\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('subir-pdf', \App\Http\Livewire\FileUpload::class)->name('subir-pdf');
Route::get('files/{fileName}', [\App\Http\Livewire\FileUpload::class, 'seePDF'])->name('see-pdf');


Route::middleware(['auth:web', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::get('/activatePeriod', ActivatePeriodController::class);



    Route::middleware('can:user.show')->prefix('admin')->name('admin.')
        ->get('usuarios', UserController::class)->name('usuarios');

    Route::middleware('can:constancy.show')->prefix('admin')->name('admin.')
        ->get('constancias', ConstanciasController::class)->name('constancias');

    Route::middleware('can:constancyInstructor.show')->prefix('admin')->name('admin.')
        ->get('constanciasinstructores', ConstanciaInstructorController::class)->name('constanciasinstructores');

    Route::middleware('can:instructor.show')->prefix('admin')->name('admin.')
        ->get('instructores', InstructorCurseController::class)->name('instructores');

    Route::middleware('can:role.show')->prefix('admin')->name('admin.')
        ->get('roles', RoleController::class)->name('roles');

    Route::middleware('can:studying.show')->prefix('participat')->name('participant.')
        ->get('mis-cursos', StudyingController::class)->name('studying');

    Route::middleware('can:area.show')->prefix('admin')->name('admin.')
        ->get('areas', AreaController::class)->name('area');

    Route::middleware('can:group.show')->prefix('admin')->name('admin.')
        ->get('grupos', GroupController::class)->name('group');

    Route::middleware('can:qualification.edit')->prefix('instructor')->name('instr.')
        ->get('Asignar-calificacion', GradeController::class)->name('grades');

    Route::middleware('can:period.show')->prefix('admin')->name('admin.')
        ->get('periodos-cursos', PeriodCoursesController::class)->name('periods-courses');

    Route::middleware('can:coursedetails.show')->prefix('admin')->name('admin.')
        ->get('detalles-cursos', CourseDetailsController::class)->name('coursedetail');

    Route::middleware('can:course.show')->prefix('admin')->name('admin.')
        ->get('cursos', CourseController::class)->name('cursos');

    Route::middleware('can:participant.show')->prefix('admin')->name('admin.')
        ->get('participante', ParticipantController::class)->name('participante');

    Route::middleware('can:inscription.create')->prefix('participant')->name('part.')
        ->get('inscription', InscriptionsController::class)->name('inscription');

    Route::middleware('can:assigninstructor.show')->prefix('admin')->name('admin.')
        ->get('asignar-instructor', AssignedInstructorController::class)->name('asignarinstructor');

    Route::middleware('can:participantlists.show')->prefix('admin')->name('admin.')
        ->get('lista-participantes', ParticipantListsController::class)->name('participantLists');

    Route::middleware('can:profile.show')->prefix('user')->name('user.')
        ->get('perfil', ProfileController::class)->name('perfil');

    Route::middleware('can:sendnotify.show')->prefix('admin')->name('admin.')
    ->get('post', PostController::class)->name('post');

    //ruta para visualizar de todas las notificaciones del usuario
    Route::resource('post', PostController::class);

    // Ruta para marcar como leída las notificaciones
    Route::get('markAsRead', function (){
        app(PostController::class)->markAsRead();
        return redirect()->back();//te retorna a la misma vista
    })->name('markAsRead');
     // Ruta para eliminar todas sus notifications
    Route::get('destroyNotificationsss', function (){
        app(PostController::class)->deletetodasnoti();
        return redirect()->back();//te retorna a la misma vista
    })->name('destroyNotificationsss');

    // Ruta para eliminar todas sus notifications lídas
    Route::get('destroyNotifications', function (){
        app(PostController::class)->deletfullnotifyread();
        return redirect()->back();//te retorna a la misma vista
    })->name('destroyNotifications');

    //Ruta para marcar una notificación como marcada
    Route::get('marcarunanoti/{id}', function ($id){
        app(PostController::class)->markoneAsRead($id);
        return redirect()->back();//te retorna a la misma vista
    })->name('marcarunanoti');

    Route::post('/mark-as-read', PostController::class)->name('markNotification');

    Route::get('markNotificationone', function (Request $request){
        auth()->user()->unreadNotifications
        ->where('id', $request)->markAsRead();
        return redirect()->back();//te retorna a la misma vista
    })->name('markNotificationone');

    Route::middleware('can:historycourse.show')->prefix('admin')->name('admin.')
        ->get('historial-cursos', HistoryCourseController::class)->name('historycourse');

    Route::middleware('can:historyparticipant.show')->prefix('admin')->name('admin.')
        ->get('historial-participant', HistoryParticipantController::class)->name('historyparticipant');

    Route::middleware('can:historyinstructor.show')->prefix('admin')->name('admin.')
        ->get('historial-instructor', HistoryInstructorController::class)->name('historyinstructor');

    Route::middleware('can:activeinscription.show')->prefix('admin')->name('admin.')
        ->get('activar-inscripciones', ActiveInscriptionController ::class)->name('activeinscription');

    Route::middleware('can:teaching.show')->prefix('admin')->name('instructor.')
        ->get('teaching', TeachingController::class)->name('teaching');

    Route::middleware('can:sendemail.show')->prefix('admin')->name('admin.')
        ->get('email', EmailController::class)->name('email');

        Route::middleware('can:sendemail.show')->prefix('admin')->name('admin.')
        ->get('view', Seepdsf::class)->name('view');

});
