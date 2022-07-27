<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class BackupController extends Component{


    public function render(){
        return view('livewire.admin.backup.index');
    }
    public function comando(){
        // Artisan::call('backup:run');
        // dd(Artisan::output());
    }

    public function sql($query){
        $con=mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'));
        mysqli_set_charset($con, "utf8");
        if (mysqli_connect_errno()) {
            printf("Conexion fallida: %s\n", mysqli_connect_error());
            exit();
        }else{
            mysqli_autocommit($con, false);
            mysqli_begin_transaction($con, MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);
            if($consul=mysqli_query($con, $query)){
                if (!mysqli_commit($con)) {
                    print("Falló la consignación de la transacción\n");
                    exit();
                }
            }else{
                mysqli_rollback($con);
                echo "Falló la transacción";
                exit();
            }
            return $consul;
        }
    }  


    public function back(){

        $day=date("d");
        $mont=date("m");
        $year=date("Y");
        $hora=date("H-i-s");
        $fecha=$day.'_'.$mont.'_'.$year;
        $DataBASE=$fecha."_(".$hora."_hrs).sql";
        $tables=array();
        $result= $this->sql('SHOW TABLES');
        if($result){
            while($row=mysqli_fetch_row($result)){
                $tables[] = $row[0];
            }
            $sql='SET FOREIGN_KEY_CHECKS=0;'."\n\n";
            $sql.='CREATE DATABASE IF NOT EXISTS '.env('DB_DATABASE').";\n\n";
            $sql.='USE '.env('DB_DATABASE').";\n\n";;
            foreach($tables as $table){
                $result= $this->sql('SELECT * FROM '.$table);
                if($result){
                    $numFields=mysqli_num_fields($result);
                    $sql.='DROP TABLE IF EXISTS '.$table.';';
                    $row2=mysqli_fetch_row($this->sql('SHOW CREATE TABLE '.$table));
                    $sql.="\n\n".$row2[1].";\n\n";
                    for ($i=0; $i < $numFields; $i++){
                        while($row=mysqli_fetch_row($result)){
                            $sql.='INSERT INTO '.$table.' VALUES(';
                            for($j=0; $j<$numFields; $j++){
                                $row[$j]=addslashes($row[$j]);
                                $row[$j]=str_replace("\n","\\n",$row[$j]);
                                if (isset($row[$j])){
                                    $sql .= '"'.$row[$j].'"' ;
                                }
                                else{
                                    $sql.= '""';
                                }
                                if ($j < ($numFields-1)){
                                    $sql .= ',';
                                }
                            }
                            $sql.= ");\n";
                        }
                    }
                    $sql.="\n\n\n";
                }else{
                    $error=1;
                }
                $error=0;
            }
            if($error==1){
                dd('Ocurrio un error inesperado al crear la copia de seguridad');
            }else{
                chmod("../backup/", 0777);
                $sql.='SET FOREIGN_KEY_CHECKS=1;';
                $handle=fopen("../backup/".$DataBASE,'w+');
                if(fwrite($handle, $sql)){
                    fclose($handle);
                    // dd('Copia de seguridad realizada con éxito '.$handle);
                    $this->noti('success','Copia de seguridad realizada con éxito');
                    // return redirect()->route('backup');
                }else{
                    dd('Ocurrio un error inesperado al crear la copia de seguridad');
                }
            }
        }else{
            dd('Ocurrio un error inesperado');
        }
        // mysqli_free_result($result);
    }

    public $aux;
    public function limpiarCadena($valor) {
        $valor = addslashes($valor);
        // $valor = str_ireplace("<script>", "", $valor);
        // $valor = str_ireplace("</script>", "", $valor);
        $valor = str_ireplace("SELECT * FROM", "", $valor);
        $valor = str_ireplace("DELETE FROM", "", $valor);
        $valor = str_ireplace("UPDATE", "", $valor);
        $valor = str_ireplace("INSERT INTO", "", $valor);
        $valor = str_ireplace("DROP TABLE", "", $valor);
        // $valor = str_ireplace("TRUNCATE TABLE", "", $valor);
        // $valor = str_ireplace("--", "", $valor);
        // $valor = str_ireplace("^", "", $valor);
        // $valor = str_ireplace("[", "", $valor);
        // $valor = str_ireplace("]", "", $valor);
        // $valor = str_ireplace("\\", "", $valor);
        // $valor = str_ireplace("=", "", $valor);
        return $valor;
    }

    public function restore(){
        $totalErrors=0;

        $restorePoint=$this->limpiarCadena($this->aux);
        $sql=explode(";",file_get_contents($restorePoint));
        // set_time_limit (60);
        $con=mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'));
        $con->query("SET FOREIGN_KEY_CHECKS=0");

        for($i = 0; $i < (count($sql)-1); $i++){
            if(!$con->query($sql[$i].";")){   
                $totalErrors++;
            }
        }

        if($totalErrors<=0){
            dd("Restauración completada con éxito");
        }else{
            dd("Ocurrio un error inesperado, no se pudo hacer la restauración completamente");
        }
        $con->query("SET FOREIGN_KEY_CHECKS=1");
        $con->close();
    }




    public function noti($icon, $txt){
        $this->dispatchBrowserEvent('notify', [
            'icon' => $icon,
            'message' => $txt,
        ]);
    }
    public function import(){
        $mysqlDatabaseName = env('DB_DATABASE');
        $mysqlUserName = env('DB_USERNAME');
        $mysqlPassword = env('DB_PASSWORD');
        $mysqlHostName = env('DB_HOST');
        $mysqlExportPath ='27_07_2022_(03-22-37_hrs).sql';
        $command='mysqldump --opt -h' .$mysqlHostName .' -u' .$mysqlUserName .' --password="' .$mysqlPassword .'" ' .$mysqlDatabaseName .' > ' .$mysqlExportPath;
        exec($command,$output,$worked);
        switch($worked){
            case 0:
            echo 'La base de datos <b>' .$mysqlDatabaseName .'</b> se ha almacenado correctamente en la siguiente ruta '.getcwd().'/' .$mysqlExportPath .'</b>';
            break;
            case 1:
            echo 'Se ha producido un error al exportar <b>' .$mysqlDatabaseName .'</b> a '.getcwd().'/' .$mysqlExportPath .'</b>';
            break;
            case 2:
            echo 'Se ha producido un error de exportación, compruebe la siguiente información: <br/><br/><table><tr><td>Nombre de la base de datos:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>Nombre de usuario MySQL:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>Contraseña MySQL:</td><td><b>NOTSHOWN</b></td></tr><tr><td>Nombre de host MySQL:</td><td><b>' .$mysqlHostName .'</b></td></tr></table>';
            break;
        }
    }
    public function export(){
        $mysqlDatabaseName = 'resi';
        $mysqlUserName = 'root';
        $mysqlPassword = '';
        $mysqlHostName = '127.0.0.1';
        $mysqlImportFilename ='27_07_2022_(03-22-37_hrs).sql';
        $command='mysql -h' .$mysqlHostName .' -u' .$mysqlUserName .' --password="' .$mysqlPassword .'" ' .$mysqlDatabaseName .' < ' .$mysqlImportFilename;
        exec($command,$output,$worked);
        switch($worked){
            case 0:
            echo 'Los datos del archivo <b>' .$mysqlImportFilename .'</b> se han importado correctamente a la base de datos <b>' .$mysqlDatabaseName .'</b>';
            break;
            case 1:
            echo 'Se ha producido un error durante la importación. Por favor, compruebe si el archivo está en la misma carpeta que este script. Compruebe también los siguientes datos de nuevo: <br/><br/><table><tr><td>Nombre de la base de datos MySQL:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>Nombre de usuario MySQL:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>Contraseña MySQL:</td><td><b>NOTSHOWN</b></td></tr><tr><td>Nombre de host MySQL:</td><td><b>' .$mysqlHostName .'</b></td></tr><tr><td>Nombre de archivo de la importación de MySQL:</td><td><b>' .$mysqlImportFilename .'</b></td></tr></table>';
            break;
        }
    }
}
