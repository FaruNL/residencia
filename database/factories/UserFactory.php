<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $nombre = $this->faker->firstName();
        // $apellido_paterno = $this->faker->lastName();

        $hora_entrada = $this->faker->time('H:i');

        $nombre1 = $this->faker->randomElement([
            'Sergio','Saúl','Valentina','Regina','Camila','María','Fernanda','María','Valeria','Renata','Victoria','Maria','Expropiasión', 'Juana','Arturo','Alexis','Carmen','Alejandro','Alejandra','Mareli','Natali',
            'Concha','Natalia', 'Mareli', 'MariaBD','Mateo','Sebastián','Emiliano','Diego','Miguel','Ángel','Daniel','Daniela',
            'Jesús', 'Pedro','Emiliano','Gael', 'David', 'Marco','Farid', 'Erik', 'Pablo','Santiago','Leonardo','Victoria', 'André','Manuel', 'Martín', 'Perla', 'Rebecca','Izmucaneth', 'Abimael','Maricela', 'Francisco',
        ]);
        $nombre2 = $this->faker->randomElement([
            'Sergio','Saúl','Valentina','Regina','Camila','María','Fernanda','María','Valeria','Renata','Victoria','Maria','Expropiasión', 'Juana','Arturo','Alexis','Carmen','Alejandro','Alejandra','Mareli','Natali',
            'Concha','Natalia', 'Mareli', 'MariaBD','Mateo','Sebastián','Emiliano','Diego','Miguel','Ángel','Daniel','Daniela',
            'Jesús', 'Pedro','Emiliano','Gael', 'David', 'Marco','Farid', 'Erik', 'Pablo','Santiago','Leonardo','Victoria', 'André','Manuel', 'Martín', 'Perla', 'Rebecca','Izmucaneth', 'Abimael','Maricela', 'Francisco',
        ]);

        $apellido_paterno = $this->faker->randomElement([
            'López','García','Hernández','González','Pérez','Rodríguez', 'Sánchez','Ramírez','Cruz','Gómez','Flores','Morales',
            'Vázquez','Reyes','Torres','Jiménez','Díaz','Gutiérrez','Mendoza','Ruíz','Ortiz','Aguilar','Moreno','Castillo','Álvarez','Zarate', 'Anaya','Juárez','Suarez','Domínguez','Ramos','Herrera','Medina','Castro','Guzmán'
        ]);

        $apellido_materno = $this->faker->randomElement([
            'López','García','Hernández','González','Pérez','Rodríguez', 'Sánchez','Ramírez','Cruz','Gómez','Flores','Morales',
            'Vázquez','Reyes','Torres','Jiménez','Díaz','Gutiérrez','Mendoza','Ruíz','Ortiz','Aguilar','Moreno','Castillo','Álvarez','Zarate', 'Anaya','Juárez','Suarez','Domínguez','Ramos','Herrera','Medina','Castro','Guzmán'
        ]);

        $correo_id = strtolower("{$nombre1}{$nombre2}_$apellido_paterno");
        $correoito=$this->faker->randomElement([
            'itoaxaca.edu.mx','gmail.com',
        ]);
        $carrera=$this->faker->randomElement([
                'Ingeniería Electrónica',
                'Ingeniería Civil',
                'Ingeniería Mecánica',
                'Ingeniería Industrial',
                'Ingeniería Química',
                'Ingeniería Eléctrica',
                'Ingeniería en Gestión Empresarial',
                'Ingeniería en Sistemas Computacionales',
                'Ingeniería en Administración','Licenciando en Administración','Contador publico','Fisico matematico',
        ]);

        $organizacion=$this->faker->randomElement([
            'Técnologico de oaxaca',
            'Cisco','Fundación Carlos Slim'
        ]);

        $jefein=$this->faker->randomElement([
            'Dr. Rubén Doroteo Castillejos',
            'M.A. Efrén Normando Enríquez Porras',
            'M.C. Maricela Morales Hernández',
            'M.A. Edgar Alberto Cortes Jiménez',
            'M.C. Minerva Donají Méndez López',
            'M.C. Martha Hilaria Bartolo Alemán',
            'Ing. Adrián Gómez Ordaz',
            'Ing. Roberto Tamar Castellanos Baltazar',
            'José Alfredo Reyes Juárez',
            'L.I. Virginia Ortíz Méndez',
        ]);

        $estudiosmax=$this->faker->randomElement([
            'Licenciatura', 'Maestría','Doctorado',
        ]);


        return [
            'name' =>"$nombre1 $nombre2",
            'email' => "$correo_id@$correoito",
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'apellido_materno' => $apellido_materno,
            'apellido_paterno'=> $apellido_paterno,
            'rfc' => $this->faker->regexify('/^[A-Z&]{3,4}\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])([A-Z0-9]{2})([A0-9])$/'),
            'curp' => $this->faker->regexify('/[A-Z][AEIOUX][A-Z]{2}\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])[HM](AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z0-9]\d/'),
            'tipo' => $this->faker->randomElement(['Base', 'Interinato', 'Honorarios']),
            'sexo' => $this->faker->randomElement(['M', 'F']),
            'carrera' => $carrera,
            'clave_presupuestal' => $this->faker->regexify('[0-9A-Z]{15}'),
            'organizacion_origen' => $organizacion,
            'estudio_maximo' => $estudiosmax,
            'cuenta_moodle' => $this->faker->numberBetween(0, 1),
            'puesto_en_area' => $this->faker->jobTitle(),
            'jefe_inmediato' => $jefein,
            'hora_entrada' => $hora_entrada,
            'hora_salida' => date('H:i', strtotime($hora_entrada.'+5 hour')),
            'correo_tecnm' => "$correo_id@oaxaca.tecnm.mx",
            'area_id' => Area::inRandomOrder()->first()->id,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     *
     * @return $this
     */
    public function withPersonalTeam()
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(function (array $attributes, User $user) {
                    return ['name' => $user->name.'\'s Team', 'user_id' => $user->id, 'personal_team' => true];
                }),
            'ownedTeams'
        );
    }
}
