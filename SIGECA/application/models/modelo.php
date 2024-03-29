<?php
class modelo extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function validaUsuario($usuario,$clave)
    {
        $this->db->select('*');
        $this->db->where('IDUSUARIO',$usuario);
        $this->db->where('CONTRASENA',$clave);
        return $this->db->get('USUARIOS')->num_rows();
    }
    function buscaPermisoUsuario($usuario,$clave)
    {
        $this->db->select('*');
        $this->db->where('IDUSUARIO',$usuario);
        $this->db->where('CONTRASENA',$clave);
        $cargo = $this->db->get('USUARIOS')->result();
        foreach($cargo as $row):
            $cargo = $row->CARGO;
        endforeach;
      
        return $cargo;
    }
    function buscaNombreUsuarioPorID($idusuario)
    {
        $this->db->select('*');
        $this->db->where('IDUSUARIO',$idusuario);
        $nombre = $this->db->get('USUARIOS')->result();
        foreach($nombre as $row):
            $nombre = $row->NOMBRE;
        endforeach;
        return $nombre;
    }
    function buscaDatosUsuariosPorID($idUsuario)
    {
        $this->db->select('*');
        $this->db->where('IDUSUARIO',$idUsuario);
        return $this->db->get('USUARIOS');
    }
    function buscaTodosCursos()
    {
        //No se trabajará con los pre-kinder y kinder
        $this->db->select('*');
        $this->db->where('ORDEN !=','0');
        $this->db->where('ORDEN !=','1');
        $this->db->order_by("ORDEN","asc");
        return $this->db->get('CURSOS');
    }
    function buscaTodasAsignatura()
    {
        $this->db->select('*');
        $this->db->order_by("NOMBREASIGNATURA","asc");
        return $this->db->get('ASIGNATURA');
    }
    function buscaAnioConfigUTP()
    {
        $this->db->select('ANOACADEMICO');
        return $this->db->get('CONFIGURACIONUTP');
    }
    function actualizarNombreUsuario($idusuario,$nombreUsuario)
    {
        $datos = array(
            'NOMBRE' => $nombreUsuario
        );
        $this->db->where('IDUSUARIO',$idusuario);
        $this->db->update('USUARIOS',$datos);
    }
    function actualizarNombreUsuarioyContrasena($idUsuario,$nombreUsuario,$contrasena)
    {
        $datos = array(
            'NOMBRE' => $nombreUsuario,
            'CONTRASENA' => $contrasena
        );
        $this->db->where('IDUSUARIO',$idUsuario);
        $this->db->update('USUARIOS',$datos);
    }
    function validaRut($r)
    {
        $s = 1;
        for($m=0;$r!=0;$r/=10)
            $s = ($s+$r%10*(9-$m++%6))%11;
        return chr($s?$s+47:75);
    }
    function buscaProfesorPorID($rut)
    {
        $this->db->select('*');
        $this->db->where('IDPROFESOR',$rut);
        $this->db->where('BLOQUEO','no');
        return $this->db->get('PROFESOR');
    }
    function buscaTodosProfesores()
    {
        $this->db->select('*');
        $this->db->where('IDPROFESOR !=','11111111');
        $this->db->where('BLOQUEO','no');
        return $this->db->get('PROFESOR');
    }
    function guardarNuevoProfesor($rut,$digito,$nombres,$aPaterno,$aMaterno,$direccion,$telefono,$prevision,$AFP,$titulo,$mension,$fNacimiento)
    {
        $datos = array(
            "IDPROFESOR"    =>$rut,
            "NOMBRES"       =>$nombres,
            "APELLIDOP"     =>$aPaterno,
            "APELLIDOM"     =>$aMaterno,
            "DIRECCION"     =>$direccion,
            "TELEFONO"      =>$telefono,
            "PREVISION"     =>$prevision,
            "AFP"           =>$AFP,
            "FECHANACIMIENTO"=>$fNacimiento,
            "TITULO"        =>$titulo,
            "MENSION"       =>$mension,
            "BLOQUEO"       => 'no'
        );
        $this->db->insert('PROFESOR',$datos);
        
        //Al insertar un nuevo profesor debo también insertar un nuevo usuario... hasta ahora solo con permiso de
        //profesor, ya que no sé si será Guia de algún curso :P!
        $largo = strlen($nombres);
        $dato = array(
            "IDUSUARIO" => $rut,
            "NOMBRE" => substr($nombres,-$largo,1).''.$aPaterno,  //el nombre de usuario será EJ: RPavez
            "CONTRASENA" => $rut,
            "CARGO" => '3' //Cargo 3 para profesor
        );
        $this->db->insert("USUARIOS",$dato);
        
    }
    function guardarEditarProfesor($rut,$nombres,$aPaterno,$aMaterno,$direccion,$telefono,$prevision,$AFP,$titulo,$mension,$fNacimiento)
    {
        $datos = array(
            "NOMBRES"       =>$nombres,
            "APELLIDOP"     =>$aPaterno,
            "APELLIDOM"     =>$aMaterno,
            "DIRECCION"     =>$direccion,
            "TELEFONO"      =>$telefono,
            "PREVISION"     =>$prevision,
            "AFP"           =>$AFP,
            "FECHANACIMIENTO"=>$fNacimiento,
            "TITULO"        =>$titulo,
            "MENSION"       =>$mension,
            "BLOQUEO"       =>'no'
        );
        $this->db->where('IDPROFESOR',$rut);
        $this->db->update('PROFESOR',$datos);
    }
    function buscaDatosCursoPorID($idcurso)
    {
        $this->db->select('*');
        $this->db->where('IDCURSO',$idcurso);
        return $this->db->get('CURSOS');
    }
    function buscaDatosCursoProfesorPorID($ano,$IDCURSO)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDCURSO',$IDCURSO);
        return $this->db->get('CURSOPROFESOR');
    }
    function buscaDatosCursoProfesor($ano)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->order_by("IDCURSO","asc");
        return $this->db->get('CURSOPROFESOR');
    }
    function buscaDatosAsignaturaPorID($idasignatura)
    {
        $this->db->select('*');
        $this->db->where('IDASIGNATURA',$idasignatura);
        return $this->db->get('ASIGNATURA');
    }
    function guardarEditarCursos($idcurso,$nombre,$letra,$jornada,$capacidad,$orden)
    {
         $datos = array(
            "NOMBRE"    =>$nombre,
            "LETRA"     =>$letra,
            "JORNADA"   =>$jornada,
            "CAPACIDAD" =>$capacidad,
            "ORDEN"     =>$orden,
        );
        $this->db->where('IDCURSO',$idcurso);
        $this->db->update('CURSOS',$datos);
    }
    function guardarEditarAsignatura($idasignatura,$nombre,$tipoAsignatura,$tipoEvaluacion)
    {
        $datos = array(
            "NOMBREASIGNATURA"    =>$nombre,
            "TIPOASIGNATURA" => $tipoAsignatura,
            "TIPOEVALUACION" => $tipoEvaluacion
        );
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->update('ASIGNATURA',$datos);
    }
    function revisaRand($val)
    {
        $this->db->select('*');
        $this->db->where('IDCURSO',$val);
        return $this->db->get('CURSOS');
    }
    function revisaRand2($val)
    {
        $this->db->select('*');
        $this->db->where('IDASIGNATURA',$val);
        return $this->db->get('ASIGNATURA');
    }
    function revisaRand3($val)
    {
        $this->db->select('*');
        $this->db->where('IDFERIADOS',$val);
        return $this->db->get('FERIADOS');
    }
    function guardarNuevoCurso($idCurso,$nombre,$letra,$jornada,$capacidad,$orden)
    {
        $datos = array(
            "IDCURSO"   => $idCurso,
            "NOMBRE"    => $nombre,
            "LETRA"     => $letra,
            "JORNADA"   => $jornada,
            "CAPACIDAD" => $capacidad,
            "ORDEN"     => $orden
        );
        $this->db->insert('CURSOS',$datos);
    }
    function guardarNuevaAsignatura($idasignatura,$nombre,$tipoAsignatura,$tipoEvaluacion)
    {
        $datos = array(
            "IDASIGNATURA" => $idasignatura,
            "NOMBREASIGNATURA" => $nombre,
            "TIPOASIGNATURA" => $tipoAsignatura,
            "TIPOEVALUACION" => $tipoEvaluacion
        );
        $this->db->insert('ASIGNATURA',$datos);
    }
    function buscaCursosProfesorPorAnio($ano)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        return $this->db->get('CURSOPROFESOR');
    }
    function guardarProfesorGuia($ano,$idprofesor,$idcurso)
    {
        $datos = array(
            "ANOACADEMICO"=>$ano,
            "IDCURSO"=>$idcurso,
            "IDPROFESOR"=>$idprofesor
        );
        $this->db->insert('CURSOPROFESOR',$datos);
        
        $this->db->select('*');
        $this->db->where('IDUSUARIO',$idprofesor);
        $this->db->where('CARGO','3'); //Profesor Normal
        $query = $this->db->get('USUARIOS');
        if($query->num_rows() > 0) 
        {
            $datos = array("CARGO"=>'4'); //Profesor Guia!
            $this->db->where('IDUSUARIO',$idprofesor);
            $this->db->update('USUARIOS',$datos);
        }
    }
    function eliminaProfesorGuia($ano,$idprofesor,$idcurso)
    {
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDCURSO',$idcurso);
        $this->db->where('IDPROFESOR',$idprofesor);
        $this->db->delete('CURSOPROFESOR');
        
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDPROFESOR',$idprofesor);
        $query = $this->db->get('CURSOPROFESOR');
        if($query->num_rows() == 0) 
        {
            $datos = array("CARGO"=>'3'); //Profesor Guia!
            $this->db->where('IDUSUARIO',$idprofesor);
            $this->db->update('USUARIOS',$datos);
        }
        
    }
    function buscaConfigAnoAcademico($ano)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        return $this->db->get('CONFIGURACIONUTP');
    }
    function guardaConfigAnoAcademico($ano,$fInicioA,$fFinA,$fInicioPS,$fFinPS,$fInicioSS,$fFinSS,$fFinS4,$fFinA4)
    {
        if($fInicioA == '01-JAN-00'){
            $datos=array(
                "ANOACADEMICO"  =>$ano,
                "FECHAINICIOA"  =>$fInicioA,
                "FECHAFINA"     =>$fFinA,
                "FECHAINICIOPS" =>$fInicioPS,
                "FECHAFINPS"    =>$fFinPS,
                "FECHAINICIOSS" =>$fInicioSS,
                "FECHAFINSS"    =>$fFinSS,
                "FECHAFINS4"    =>$fFinS4,
                "FECHAFINA4"    =>$fFinA4
            );
            $this->db->insert('CONFIGURACIONUTP',$datos);
        }
        else{
            $datos=array(
                "FECHAINICIOA"  =>$fInicioA,
                "FECHAFINA"     =>$fFinA,
                "FECHAINICIOPS" =>$fInicioPS,
                "FECHAFINPS"    =>$fFinPS,
                "FECHAINICIOSS" =>$fInicioSS,
                "FECHAFINSS"    =>$fFinSS,
                "FECHAFINS4"    =>$fFinS4,
                "FECHAFINA4"    =>$fFinA4
            );
            $this->db->where('ANOACADEMICO',$ano);
            $this->db->update('CONFIGURACIONUTP',$datos);
        }
    }
    function buscaFeriados($ano)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->order_by('FECHAS');
        return $this->db->get('FERIADOS');
    }
    function guardarFeriado($idferiado,$ano,$fecha,$motivo)
    {
        $data = array(
            "IDFERIADOS"    => $idferiado,
            "ANOACADEMICO"  => $ano,
            "FECHAS"        => $fecha,
            "MOTIVO"        => $motivo
        );
        $this->db->insert("FERIADOS",$data);
    }
    function eliminaFeriado($idferiado)
    {
        $this->db->where('IDFERIADOS',$idferiado);
        $this->db->delete("FERIADOS");
    }
    function buscaprofesorCursoAsignatura($ano,$idcurso)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDCURSO',$idcurso);
        return $this->db->get('PROFESORCURSOASIGNATURA');
    }
    function buscaDatosProfesorCursoAsignaturaPorID($ano,$IDCURSO)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDCURSO',$IDCURSO);
        return $this->db->get('PROFESORCURSOASIGNATURA');
    }
    function buscaprofesorCursoAsignatura2($ano,$idcurso,$idasignatura)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDCURSO',$idcurso);
        $this->db->where('IDASIGNATURA',$idasignatura);
        return $this->db->get('PROFESORCURSOASIGNATURA');
    }
    function eliminarAsignaturaCurso($idasignatura,$ano,$curso)
    {
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDCURSO',$curso);
        $this->db->delete('PROFESORCURSOASIGNATURA');
    }
    function asignarAsignaturaCurso($idasignatura,$ano,$curso,$idprofesor)
    {
        $datos = array(
            "ANOACADEMICO"   => $ano,
            "IDASIGNATURA"  => $idasignatura,
            "IDCURSO"       => $curso,
            "IDPROFESOR"    => $idprofesor
        );
        $this->db->insert('PROFESORCURSOASIGNATURA',$datos);
    }
    function actualizaProfesorCursoAsignatura($ano,$idcurso,$idprofesor,$idasignatura)
    {
        $datos = array(
            "IDPROFESOR" => $idprofesor
        );
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDCURSO',$idcurso);
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->update('PROFESORCURSOASIGNATURA',$datos);
    }
    function buscaProfesorGuia($ano,$IDCURSO)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDCURSO',$IDCURSO);
        return $this->db->get('CURSOPROFESOR');
    }
    function buscaCursosProfesorGuia($ano,$idProfesor)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDPROFESOR',$idProfesor);
        return $this->db->get('CURSOPROFESOR');
    }
    function buscaFechaCalificacion($ano,$idasignatura,$idcalificacion,$semestre)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('IDCALIFICACION',$idcalificacion);
        $this->db->where('SEMESTRE',$semestre);
        return $this->db->get('FECHACALIFICACION');
    }
    function buscaFechaCalificacion2($ano,$idasignatura,$semestre)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('SEMESTRE',$semestre);
        $this->db->order_by('FECHA');
        return $this->db->get('FECHACALIFICACION');
    }
    function buscaDatosPROFESORCURSOASIGNATURAPorIdProfesor($idProfesor)
    {
        $ano =  DATE('Y');
        $this->db->select('IDCURSO');
        $this->db->where('IDPROFESOR',$idProfesor);
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->distinct();
        $this->db->order_by('IDCURSO','asc');
        return $this->db->get('PROFESORCURSOASIGNATURA');
    }
    function buscaDatosPROFESORCURSOASIGNATURAporCursoAnoProfesor($idcurso,$ano,$idprofesor)
    {
        $this->db->select('*');
        $this->db->where('IDPROFESOR',$idprofesor);
        $this->db->where('IDCURSO',$idcurso);
        $this->db->where('ANOACADEMICO',$ano);
        return $this->db->get('PROFESORCURSOASIGNATURA');
    }
    function buscaIdCursoEnProfCursoAsig($ano,$idasignatura,$idprofesor)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('IDPROFESOR',$idprofesor);
        return $this->db->get('PROFESORCURSOASIGNATURA');
    }
    function buscaAlumnosMatriculados($IDCURSO)
    {
        $this->db->select('*');
        $this->db->where('IDCURSO',$IDCURSO);
        return $this->db->get('MATRICULA');
    }
    function buscaAlumnosPorID($idalumno)
    {
        $this->db->select('*');
        $this->db->where('IDALUMNO',$idalumno);
        return $this->db->get('ALUMNOS');
    }
    function buscaDatosCalificacion($idasignatura,$ano,$semestre)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('SEMESTRE',$semestre);
        return $this->db->get('FECHACALIFICACION');
    }
    function cargaListadoAlumnos($ano,$idcurso)
    {
        $this->db->select('*');
        $this->db->from('ALUMNOS');
        $this->db->join('MATRICULA','MATRICULA.IDALUMNO = ALUMNOS.IDALUMNO');
        $this->db->where('MATRICULA.ANO',$ano);
        $this->db->where('MATRICULA.IDCURSO',$idcurso);
        $this->db->order_by('APELLIDOP','asc');
        return $this->db->get();
    }
    function buscaNota($IDALUMNO,$ano,$idasignatura,$IDCALIFICACION,$semestre)
    {
        $this->db->select('*');
        $this->db->where('IDALUMNO',$IDALUMNO);
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('IDCALIFICACION',$IDCALIFICACION);
        $this->db->where('SEMESTRE',$semestre);
        return $this->db->get('CALIFICACIONES');
    }
    function buscaNota2($IDALUMNO,$ano,$idasignatura,$semestre)
    {
        $this->db->select('*');
        $this->db->where('IDALUMNO',$IDALUMNO);
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('SEMESTRE',$semestre);
        return $this->db->get('CALIFICACIONES');
    }
    function buscaCalifC2($ano,$idasignatura,$tipo,$idalumno,$semestre)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('TIPOCALIFICACION',$tipo);
        $this->db->where('IDALUMNO',$idalumno);
        $this->db->where('SEMESTRE',$semestre);
        return $this->db->get('CALIFICACIONES');
    }
    function buscaNota3($ano,$idasignatura,$idcalificacion,$semestre)
    {
        $this->db->select('*');
        $this->db->where('IDCALIFICACION',$idcalificacion);
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('SEMESTRE',$semestre);
        return $this->db->get('CALIFICACIONES');
    }
    function buscaCalifC2porIDCalificacion($ano,$idasignatura,$IDCALIFICACION,$semestre)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('IDCALIFICACION',$IDCALIFICACION);
        $this->db->where('TIPOCALIFICACION','C/2');
        $this->db->where('SEMESTRE',$semestre);
        return $this->db->get('FECHACALIFICACION');
    }
    function buscaPonderacionNotas($ano,$idasignatura,$IDCALIFICACION,$semestre)
    {
        $this->db->select('PONDERACION');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('IDCALIFICACION',$IDCALIFICACION);
        $this->db->where('SEMESTRE',$semestre);
        return $this->db->get('FECHACALIFICACION');
    }
    function almacenarCalificaciones($idAlumno,$ano,$idAsignatura,$idCalif,$fecha,$calif,$idCurso,$tipo,$semestre)
    {
        if($calif == '')
            $calif = '10';
        $this->db->select('*');
        $this->db->where('IDALUMNO',$idAlumno);
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDASIGNATURA',$idAsignatura);
        $this->db->where('IDCALIFICACION',$idCalif);
        $this->db->where('IDCURSO',$idCurso);
        $this->db->where('SEMESTRE',$semestre);
        $query = $this->db->get('CALIFICACIONES')->num_rows();
        if($query > 0)
        {
            $datos= array
            (
                //"FECHA" => $fecha,
                "NOTAS" => $calif,
                //"TIPOCALIFICACION" => $tipo,
                "BLOQUEO" =>'si'
            );
            $this->db->where('IDALUMNO',$idAlumno);
            $this->db->where('ANOACADEMICO',$ano);
            $this->db->where('IDASIGNATURA',$idAsignatura);
            $this->db->where('IDCALIFICACION',$idCalif);
            $this->db->where('IDCURSO',$idCurso);
            $this->db->where('SEMESTRE',$semestre);
            $this->db->update('CALIFICACIONES',$datos);
        }
        else
        {
            $datos= array
            (
                "IDALUMNO"          => $idAlumno,
                "ANOACADEMICO"      => $ano,
                "IDASIGNATURA"      => $idAsignatura,
                "IDCALIFICACION"    => $idCalif,
                "FECHA"             => $fecha,
                "NOTAS"             => $calif,
                "TIPOCALIFICACION"  => $tipo,
                "IDCURSO"           => $idCurso,
                "BLOQUEO"           =>'si',
                "SEMESTRE"          => $semestre
            );
            $this->db->insert('CALIFICACIONES',$datos);   
        }
        
    }
    function buscaCursosEncargadoUTP($idprofesor)
    {
        $ano =  DATE('Y');
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDUSUARIO',$idprofesor);
        return $this->db->get('ENCARGADOUTP');
    }
    function buscaCursosEncargadoUTP2($ano,$idprofesor)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDUSUARIO',$idprofesor);
        return $this->db->get('ENCARGADOUTP');
    }
    function buscaCursosNoAsignadosUTP(){
        
        $ano =  DATE('Y');
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $query = $this->db->get('ENCARGADOUTP');
        
        if($query->num_rows() == 0)
        {
            return $this->buscaTodosCursos()->result();
        }
        else
        {
            $this->db->select('*');
            foreach($query->result() as $row):
                $this->db->where('IDCURSO !=', $row->IDCURSO);
            endforeach;
            $this->db->where('ORDEN !=','0');
            $this->db->where('ORDEN !=','1');
            return $this->db->get('CURSOS')->result();
        }

    }
    function guardaCursoEncargadoUTP($profesor,$curso)
    {
        $ano =  DATE('Y');
        $data = array('ANOACADEMICO'=>$ano,'IDUSUARIO'=>$profesor,'IDCURSO'=>$curso);
        $this->db->insert('ENCARGADOUTP',$data);
    }
    function eliminaCursoEncargadoUTP($idprofesor,$idcurso)
    {
        $ano =  DATE('Y');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDUSUARIO',$idprofesor);
        $this->db->where('IDCURSO',$idcurso);
        $this->db->delete('ENCARGADOUTP');
    }
    function cambiaPermisoUTP1($profesor)
    {
        $this->db->select('*');
        $this->db->where('IDUSUARIO',$profesor);
        $this->db->where('CARGO !=','2');
        $query = $this->db->get('USUARIOS');
        if($query->num_rows() > 0) //Si ya existe como usuario NO UTP... le cambio el permiso a UTP
        {
            $datos = array('CARGO'=>'2'); //Se Cambia a UTP!
            $this->db->where('IDUSUARIO',$profesor);
            $this->db->update('USUARIOS',$datos);
        }
    }
    function cambiaPermisoUTP2($idprofesor)
    {
        $this->db->select('*');
        $this->db->where('IDUSUARIO',$idprofesor);
        $query = $this->db->get('ENCARGADOUTP');
        if($query->num_rows() == 0) //Se elimininó como UTP... solo pasa a ser profesor
        {
            $datos = array('CARGO'=>'3'); //Se Cambia a UTP!
            $this->db->where('IDUSUARIO',$profesor);
            $this->db->update('USUARIOS',$datos);
        }
    }
    function buscaTodosCursosAsignadosUTP($ano,$idusuario)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDUSUARIO',$idusuario);
        return $this->db->get('ENCARGADOUTP');
    }
    function guardaFechaCalificacion($ano,$idasignatura,$idcalificacion,$fecha,$ponde,$tipo,$semestre)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('IDCALIFICACION',$idcalificacion);
        $this->db->where('SEMESTRE',$semestre);
        $query = $this->db->get('FECHACALIFICACION');
        if($query->num_rows()> 0) //implica que solo lo debo editar!
        {
            $datos = array(
                "FECHA"         =>  $fecha,
                "PONDERACION"   =>  $ponde,
                "TIPOCALIFICACION" => $tipo
            );
            $this->db->where('ANOACADEMICO',$ano);
            $this->db->where('IDASIGNATURA',$idasignatura);
            $this->db->where('IDCALIFICACION',$idcalificacion);
            $this->db->where('SEMESTRE',$semestre);
            $this->db->update('FECHACALIFICACION',$datos);
        }
        else
        {
            $datos = array(
                "ANOACADEMICO"      =>  $ano,
                "IDASIGNATURA"      =>  $idasignatura,
                "IDCALIFICACION"    =>  $idcalificacion,
                "FECHA"             =>  $fecha,
                "PONDERACION"       =>  $ponde,
                "TIPOCALIFICACION"  =>  $tipo,
                "SEMESTRE"          =>  $semestre
            );
            $this->db->insert('FECHACALIFICACION',$datos);
        }
    }
    function eliminaConfigCalificacion($idEvaluacion,$idAsignatura,$ano,$semestre)
    {
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDASIGNATURA',$idAsignatura);
        $this->db->where('IDCALIFICACION',$idEvaluacion);
        $this->db->where('SEMESTRE',$semestre);
        $this->db->delete('FECHACALIFICACION');
    }
    function verificaFeriado($fecha)
    {
        $this->db->select('*');
        $this->db->where('FECHAS',$fecha);
        return $this->db->get('FERIADOS');
    }
    function buscaAsignaturasPorCursoAno($ano,$curso,$semestre)
    {
        $this->db->select('IDASIGNATURA');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDCURSO',$curso);
        $this->db->where('SEMESTRE',$semestre);
        $this->db->distinct();
        return $this->db->get('CALIFICACIONES');
    }
    function buscaCalificacionesPorCursoAno($ano,$curso,$semestre)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDCURSO',$curso);
        $this->db->where('SEMESTRE',$semestre);
        return $this->db->get('CALIFICACIONES');
    }
    function cargaFechasCalificacion($ano,$idasignatura,$idcurso,$semestre)
    {
        $this->db->select('FECHA');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDCURSO',$idcurso);
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('SEMESTRE',$semestre);
        $this->db->distinct();
        return $this->db->get('CALIFICACIONES');
    }
    function modificacionCalificacion($ano,$idasignatura,$idalumno,$idcurso,$fecha,$semestre)
    {
        //echo $ano.' - '.$idasignatura.' - '.$idalumno.' - '.$idcurso.' - '.$fecha;
        $datos= array(
            "BLOQUEO" => 'no'
        );
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('IDCURSO',$idcurso);
        $this->db->where('IDALUMNO',$idalumno);
        $this->db->where('FECHA',$fecha);
        $this->db->where('SEMESTRE',$semestre);
        $this->db->update('CALIFICACIONES',$datos);
    }
    function buscaEximidos($ano,$idAlumno)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDALUMNO',$idAlumno);
        return $this->db->get('ASIGNATURAEXIMIDA');
    }
    function buscaEximidosPorAno($ano)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        return $this->db->get('ASIGNATURAEXIMIDA');
    }
    function guardarEximido($ano,$alumno,$asignatura,$motivo)
    {
        $this->db->select('*');
        $this->db->where('IDALUMNO',$alumno);
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDASIGNATURA',$asignatura);
        $query = $this->db->get('ASIGNATURAEXIMIDA')->num_rows();
        if($query==0)
        {
            $datos = array(
                "IDALUMNO"=>$alumno,
                "ANOACADEMICO"=>$ano,
                "IDASIGNATURA"=>$asignatura,
                "MOTIVO"=>$motivo
            );
            $this->db->insert('ASIGNATURAEXIMIDA',$datos);
        }
    }
    function eliminarEximido($ano,$alumno,$asignatura)
    {
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDALUMNO',$alumno);
        $this->db->where('IDASIGNATURA',$asignatura);
        $this->db->delete('ASIGNATURAEXIMIDA');
    }
    function verificaEximido($ano,$alumno,$asignatura)
    {
        $this->db->select('*');
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->where('IDALUMNO',$alumno);
        $this->db->where('IDASIGNATURA',$asignatura);
        return $this->db->get('ASIGNATURAEXIMIDA');
    }
    function cargaListadoAlumnosConElectivo($ano,$idcurso)
    {
        //$eximidos = $this->buscaEximidosPorAno($ano)->result();
        
        $this->db->select('ALUMNOS.IDALUMNO, ALUMNOS.NOMBRES, ALUMNOS.APELLIDOP, ALUMNOS.APELLIDOM, ASIGNATURAELECTIVA.IDASIGNATURA');
        $this->db->from('ALUMNOS');
        $this->db->join('MATRICULA','MATRICULA.IDALUMNO = ALUMNOS.IDALUMNO');
        $this->db->where('MATRICULA.ANO',$ano);
        $this->db->where('MATRICULA.IDCURSO',$idcurso);
        /*foreach($eximidos as $row):
            $this->db->where('MATRICULA.IDALUMNO !=',$row->IDALUMNO);
        endforeach;*/
        $this->db->join('ASIGNATURAELECTIVA','ASIGNATURAELECTIVA.IDALUMNO = ALUMNOS.IDALUMNO');
        $this->db->where('ASIGNATURAELECTIVA.ANOACADEMICO',$ano);
        $this->db->order_by('APELLIDOP','asc');
        return $this->db->get();
    }
    function cargaListadoAlumnosConElectivo2($ano,$idcurso,$idasignatura)
    {
        $this->db->select('ALUMNOS.IDALUMNO, ALUMNOS.NOMBRES, ALUMNOS.APELLIDOP, ALUMNOS.APELLIDOM, ASIGNATURAELECTIVA.IDASIGNATURA');
        $this->db->from('ALUMNOS');
        $this->db->join('MATRICULA','MATRICULA.IDALUMNO = ALUMNOS.IDALUMNO');
        $this->db->where('MATRICULA.ANO',$ano);
        $this->db->where('MATRICULA.IDCURSO',$idcurso);
        $this->db->join('ASIGNATURAELECTIVA','ASIGNATURAELECTIVA.IDALUMNO = ALUMNOS.IDALUMNO');
        $this->db->where('ASIGNATURAELECTIVA.ANOACADEMICO',$ano);
        $this->db->where('ASIGNATURAELECTIVA.IDASIGNATURA',$idasignatura);
        $this->db->order_by('APELLIDOP','asc');
        return $this->db->get();
    }
    function cargaListadoAlumnosSinElectivo($ano,$idcurso)
    {
        $query = $this->cargaListadoAlumnosConElectivo($ano,$idcurso); //Cargo los alumnos Con Electivo
        $num = $query->num_rows();
        if($num == 0):
            return $this->cargaListadoAlumnos($ano, $idcurso);
        else:
            $this->db->select('*');
            $this->db->from('ALUMNOS');
            $this->db->join('MATRICULA','MATRICULA.IDALUMNO = ALUMNOS.IDALUMNO');
            $this->db->where('MATRICULA.ANO',$ano);
            $this->db->where('MATRICULA.IDCURSO',$idcurso);
            foreach($query->result() as $row):
                $this->db->where('ALUMNOS.IDALUMNO !=',$row->IDALUMNO);
            endforeach;
            $this->db->order_by('APELLIDOP','asc');
            return $this->db->get();
        endif;
    }
    function cargaAsignaturasElectivas()
    {
        $this->db->select('*');
        $this->db->where('TIPOASIGNATURA',1);
        return $this->db->get('ASIGNATURA');
    }
    function guardarElectivo($alumno,$asignatura,$ano)
    {
        $datos = array(
            "IDASIGNATURA" => $asignatura,
            "IDALUMNO" => $alumno,
            "ANOACADEMICO" => $ano
        );
        $this->db->insert('ASIGNATURAELECTIVA',$datos);
    }
    function modificarElectivo($alumno,$asignatura,$ano)
    {
        $datos = array(
            "IDASIGNATURA" => $asignatura,
        );
        $this->db->where('IDALUMNO',$alumno);
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->update('ASIGNATURAELECTIVA',$datos);
    }
    function eliminarElectivo($alumno,$asignatura,$ano)
    {
        $this->db->where("IDASIGNATURA",$asignatura);
        $this->db->where('IDALUMNO',$alumno);
        $this->db->where('ANOACADEMICO',$ano);
        $this->db->delete('ASIGNATURAELECTIVA');
    }
    function buscaAsignaturaElectiva($idasignatura)
    {
        $this->db->select('*');
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('TIPOASIGNATURA',1);
        return $this->db->get('ASIGNATURA');
    }
    function buscaAsignaturaConcepto($idasignatura)
    {
        $this->db->select('*');
        $this->db->where('IDASIGNATURA',$idasignatura);
        $this->db->where('TIPOEVALUACION',1);
        return $this->db->get('ASIGNATURA');
    }
}
?>
