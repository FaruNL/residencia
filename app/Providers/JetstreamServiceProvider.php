<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Fortify\Fortify;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if ($user &&
                Hash::check($request->password, $user->password)) {

                $rol = $user->getRoleNames()->first();
                $id_user=$user->id;

                $organizacion_origen=$user->organizacion_origen;

                $estatus = User::join('inscriptions','inscriptions.user_id','users.id')
                ->join('course_details','course_details.id','inscriptions.course_detail_id')
                ->join('periods','periods.id','course_details.period_id')
                ->select('inscriptions.estatus_participante')
                ->where('inscriptions.user_id','=',$id_user)
                ->where('inscriptions.estatus_participante','=','Instructor')
                ->where('periods.estado','=',1)
                ->first();

                if($rol!=='Super admin' and $rol!=='Administrador'){ //a los usuarios que tienen estos roles no se les cambia el rol nunca, ni con los valores del rb
                    if($request->rol=='Instructor' and $estatus==null){ //si seleccionó el rb instrcutor y no es intructor en el periodo activo
                        if($organizacion_origen=='Tecnologico de oaxaca' and $rol == 'Instructor'){ //Si es del Tec y su rol es intructor
                            $user->syncRoles('Participante'); //cambiale el rol a participante(por si se haya quedado con el rol Instructor)
                        }
                        if($organizacion_origen!=='Tecnologico de oaxaca' and $rol == 'Participante'){ //si no es del tec y por equivocación lo hayan guardado con el rol participante
                            $user->syncRoles('Instructor'); // entrará como instructor siempre aunque no sea de un periodo activo
                            return $user; //entra
                        }
                        $user->syncRoles('Participante');
                    }
                    if($request->rol=='Participante' and $organizacion_origen=='Tecnologico de oaxaca'){ //si selecciono el rb participante
                        if($rol !== 'Participante'){ //y no tiene el rol participante
                            $user->syncRoles($request->rol); //se le asigna ese rol
                            return $user; //entra
                        }
                        return $user; //si ya tiene el rol participante simplemente entra
                    }
                    if($request->rol=='Instructor' and $rol == 'Participante'){ //si selecciono el rb instructor y tiene el rol parti
                        $user->syncRoles($request->rol); //se le cambia el rol
                        return $user; //retorna el usuario
                    }
                    if($request->rol=='Instructor' and $estatus!==null){ //si selecciono el rb instrctor y esta en periodo activo
                        $user->syncRoles('Instructor'); // entrará como instructor siempre aunque no sea de un periodo activo
                            return $user; //entra
                    }
                }
                return $user; //entra al sistema si es admin, super admin, o el nuevo rol que no se si ya lo agregaron "coordinador"
            }
            return false;

        });
}

    /**
     * Configure the permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
