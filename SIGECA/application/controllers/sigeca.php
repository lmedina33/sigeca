<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sigeca extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('modelo');
        //$this->load->library('Image_lib');
        //$this->load->library('Email');
    }
    public function index()
    {
        $this->load->view('login');
    }
    function login()
    {
        $usuario    =   $this->input->post('usuario');
        $clave      =   $this->input->post('clave');
        //VERIFICAR USUARIO EN LA BD!
        //$usuario == 'Colegio' && $clave == 'Andes'
        if($this->modelo->validaUsuario($usuario,$clave)> 0){
            $cargo  =   $this->modelo->buscaPermisoUsuario($usuario,$clave);
            $data   =   array(
                'username'  => $usuario,
                'logged_in' => TRUE,
                'cargo' => $cargo
            );
            $this->session->set_userdata($data);
            $msj    =   'true';
        }
        else
            $msj    =   'false';

       echo json_encode(array('msj'=>$msj));
    }
    function cargaPrincipal()
    {
        $idusuario                  =   $this->session->userdata('username');
        $datos['nombre']            =   $this->modelo->buscaNombreUsuarioPorID($idusuario);
        $datos['cargo']             =   $this->session->userdata('cargo');
        $datos['utpCursos'] = 'no';
        if($datos['cargo'] == '2') //Es UTP... que puede tener CURSOS... por lo que debo ver si debe configurar asignatura!
        {
            if($this->modelo->buscaDatosPROFESORCURSOASIGNATURAPorIdProfesor($idusuario)->num_rows() > 0)
            {
                $datos['utpCursos'] = 'si';
            }
        }
        $datos['anios']             =   $this->modelo->buscaAnioConfigUTP()->result();
        $datos['idusuario'] = $idusuario;
        $ano = DATE('Y');
        $cursos   = $this->modelo->buscaTodosCursosAsignadosUTP($ano,$this->session->userdata('username'))->result();
        $i=0;
        foreach ($cursos as $row):
            $datos['cursos'.$i] = $this->modelo->buscaDatosCursoPorID($row->IDCURSO)->result();
            $i++;
        endforeach;
        $datos['cantCursos'] = $i;
        $this->load->view('principal',$datos);
    }
    function tabs_1()
    {
        $idusuario                  =   $this->session->userdata('username');
        $datos['nombre']            =   $this->modelo->buscaNombreUsuarioPorID($idusuario);
        $datos['cargo']             =   $this->session->userdata('cargo');
        $datos['utpCursos'] = 'no';
        if($datos['cargo'] == '2') //Es UTP... que puede tener CURSOS... por lo que debo ver si debe configurar asignatura!
        {
            if($this->modelo->buscaDatosPROFESORCURSOASIGNATURAPorIdProfesor($idusuario)->num_rows() > 0)
            {
                $datos['utpCursos'] = 'si';
            }
        }
        $datos['anios']             =   $this->modelo->buscaAnioConfigUTP()->result();
        $datos['idusuario'] = $idusuario;
        $ano = DATE('Y');
        $cursos   = $this->modelo->buscaTodosCursosAsignadosUTP($ano,$this->session->userdata('username'))->result();
        $i=0;
        foreach ($cursos as $row):
            $datos['cursos'.$i] = $this->modelo->buscaDatosCursoPorID($row->IDCURSO)->result();
            $i++;
        endforeach;
        $datos['cantCursos'] = $i;
        $this->load->view('tabs1',$datos);
    }
    function tabs_3()
    {
        $PROFESORCURSOASIGNATURA = $this->modelo->buscaDatosPROFESORCURSOASIGNATURAPorIdProfesor($this->session->userdata('username'));
        $i=0;
        foreach($PROFESORCURSOASIGNATURA->result() as $row):
            $datos['cursos'.$i] = $this->modelo->buscaDatosCursoPorID($row->IDCURSO)->result();
            $i++;
        endforeach;
        $datos['cantCursos'] = $i;
        $this->load->view('tabs3',$datos);
    }
    function tabs_3Asignaturas()
    {
        $idcurso = $this->input->post('curso');
        $PROFESORCURSOASIGNATURA = $this->modelo->buscaDatosPROFESORCURSOASIGNATURAPorIdProfesor($this->session->userdata('username'));
        $i=0;
        foreach($PROFESORCURSOASIGNATURA->result() as $row):
            $datos['cursos'.$i] = $this->modelo->buscaDatosCursoPorID($row->IDCURSO)->result();
            $i++;
        endforeach;
        $datos['cantCursos'] = $i;
        $idasignaturas  =  $this->modelo->buscaDatosPROFESORCURSOASIGNATURAporCursoAnoProfesor($idcurso,DATE('Y'),$this->session->userdata('username'));
        $i=0;
        foreach($idasignaturas->result() as $row):
            $datos['asignaturas'.$i] = $this->modelo->buscaDatosAsignaturaPorID($row->IDASIGNATURA)->result();
            $i++;
        endforeach;
        $datos['cantAsignaturas'] = $i;
        $datos['idcurso'] = $idcurso;
        $this->load->view('tabs3_selectAsignatura',$datos);
    }
    function verificaLogin()
    {
        if($this->session->userdata('logged_in')==TRUE)
        {
            $msj = 'true';
        }
        else
        {
            $msj = 'false';
        }
        echo json_encode(array('msj'=>$msj));
    }

    function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url());
    }
    function tabs_2()
    {
        $profesores = $this->modelo->buscaTodosProfesores();
        $datos['cantProfesores'] = $profesores->num_rows();
        $datos['profesores'] = $profesores->result();
        $this->load->view('tabs2',$datos);
    }
    function tabs_6()
    {
        $datos['cursos'] = $this->modelo->buscaTodosCursos()->result();
        $datos['profesores'] = $this->modelo->buscaTodosProfesores()->result();
        $datos['asignaturas'] = $this->modelo->buscaTodasAsignatura()->result();
        $anios = $this->modelo->buscaAnioConfigUTP()->result();
        foreach ($anios as $row):
            $datos['ultimoAno'] = $row->ANOACADEMICO;
        endforeach;
        $this->load->view('tabs6',$datos);
    }
    function tablaUTP()
    {
        $idprofesor = $this->input->post('idprofesor');
        $idcursos = $this->modelo->buscaCursosEncargadoUTP($idprofesor)->result();
        $i=0;
        foreach($idcursos as $row):
            $datos['curso'.$i] = $this->modelo->buscaDatosCursoPorID($row->IDCURSO)->result();
            $i++;
        endforeach;
        
        $datos['cantCurso'] = $i;
        $this->load->view('tabs6_tablaUTP',$datos);
    }
    function asignarUTP()
    {
        $profesor = $this->input->post('profesor');
        $curso =  $this->input->post('curso');
        $this->modelo->cambiaPermisoUTP1($profesor); //Desde profesor a UTP
        $this->modelo->guardaCursoEncargadoUTP($profesor,$curso);
    }
    function selectDivAdminCurso()
    {
        $datos['cursos'] = $this->modelo->buscaCursosNoAsignadosUTP();        
        $this->load->view('tabs6_divAdminCurso',$datos);
    }
    function eliminarCursoUTP()
    {
        $idprofesor = $this->input->post('idprofesor');
        $idcurso = $this->input->post('idcurso');
        $this->modelo->eliminaCursoEncargadoUTP($idprofesor,$idcurso);
        $this->modelo->cambiaPermisoUTP2($idprofesor);
    }
    function tabs_7()
    {
        $idUsuario          =   $this->session->userdata('username');
        $datos['result']    =   $this->modelo->buscaDatosUsuariosPorID($idUsuario)->result();
        $this->load->view('tabs7',$datos);
    }
    function tabs_8()
    {
        $idUsuario          =   $this->session->userdata('username');
        $cursos = $this->modelo->buscaDatosPROFESORCURSOASIGNATURAPorIdProfesor($idUsuario)->result();
        $i=0;
        foreach($cursos as $row):
            $datos['curso'.$i] = $this->modelo->buscaDatosCursoPorID($row->IDCURSO)->result();
            $i++;
        endforeach;
        $datos['cantCurso'] = $i;
        $this->load->view('tabs8',$datos);
    }
    function tabs_8CargaConfigAsignaturas()
    {
        $idcurso = $this->input->post('curso');
        $idprofesor = $this->session->userdata('username');
        $ano = DATE('Y');
        
        $datos['nombreCurso'] = $this->modelo->buscaDatosCursoPorID($idcurso)->result();
        $profesorGuia = $this->modelo->buscaProfesorGuia($ano,$idcurso)->result();
        $datos['profesorGuiaCant'] = $this->modelo->buscaProfesorGuia($ano,$idcurso)->num_rows();
        foreach($profesorGuia as $row):
            $datos['profesorGuia'] = $this->modelo->buscaProfesorPorID($row->IDPROFESOR)->result();
        endforeach;
        
        $asignaturas = $this->modelo->buscaDatosPROFESORCURSOASIGNATURAporCursoAnoProfesor($idcurso,$ano,$idprofesor)->result();
        $i=0;
        foreach($asignaturas as $row):
            $datos['asignatura'.$i] = $this->modelo->buscaDatosAsignaturaPorID($row->IDASIGNATURA)->result();
            $datos['configurada'.$i] = 'no';
            $configurada = $this->modelo->buscaDatosCalificacion($row->IDASIGNATURA,$ano);
            if($configurada->num_rows() > 0):
                $datos['configurada'.$i] = 'si';
            endif;
            $i++;
        endforeach;
        $datos['indice2'] = $i;
        
        $this->load->view('tabs8_tablaConfigAsignatura',$datos);
    }
    function guardarNuevoCurso()
    {
        $idCurso = $this->generaRandom(); 
        $nombre = $this->input->post('nombre');
        $letra = $this->input->post('letra');
        $jornada = $this->input->post('jornada');
        $capacidad = $this->input->post('capacidad');
        $orden = $this->input->post('orden');
        
        $this->modelo->guardarNuevoCurso($idCurso,$nombre,$letra,$jornada,$capacidad,$orden);
    }
    function guardarNuevaAsignatura()
    {
        $idasignatura = $this->generaRandom2();
        $nombre = $this->input->post('nombre');
        $calificacion = $this->input->post('calificacion');
        $electivo = $this->input->post('electivo');
        if($calificacion == 'Nota')
           $calificacion=0;
        else
            $calificacion=1;
        if($electivo == 'Si')
            $electivo=1;
        else
            $electivo=0;
        $this->modelo->guardarNuevaAsignatura($idasignatura,$nombre,$electivo,$calificacion);
        $datos['asignaturas'] = $this->modelo->buscaTodasAsignatura()->result();
        $this->load->view('divDatosEditarAsignatura',$datos);
    }
    function cargarDatosEditarCursos()
    {
        $idcurso = $this->input->post('idcurso');
        $datos['result'] = $this->modelo->buscaDatosCursoPorID($idcurso)->result();
        $this->load->view('tabs6_datosEditarCurso',$datos);
    }
    function cargarDatosEditarAsignatura()
    {
        $idasignatura = $this->input->post('idasignatura');
        $datos['result'] = $this->modelo->buscaDatosAsignaturaPorID($idasignatura)->result();
        $this->load->view('tabs6_datosEditarAsignatura',$datos);
    }
    function guardarEditarCursos()
    {
        $idcurso    = $this->input->post('idcurso');
        $nombre     = $this->input->post('nombre');
        $letra      = $this->input->post('letra');
        $jornada    = $this->input->post('jornada');
        $capacidad  = $this->input->post('capacidad');
        $orden      = $this->input->post('orden');
        $this->modelo->guardarEditarCursos($idcurso,$nombre,$letra,$jornada,$capacidad,$orden);
    }
    function guardarEditarAsignatura()
    {
        $idasignatura    = $this->input->post('idasignatura');
        $nombre     = $this->input->post('nombre');
        $calificacion = $this->input->post('calificacion');
        $electivo = $this->input->post('electivo');
        if($calificacion == 'Nota')
           $calificacion=0;
        else
            $calificacion=1;
        if($electivo == 'Si')
            $electivo=1;
        else
            $electivo=0;
        $this->modelo->guardarEditarAsignatura($idasignatura,$nombre,$electivo,$calificacion);
        $datos['asignaturas'] = $this->modelo->buscaTodasAsignatura()->result();
        $this->load->view('divDatosEditarAsignatura',$datos);
    }
    function generaRandom()
    {
        $val = rand();
        if( $this->modelo->revisaRand($val)->num_rows()>0)
            $val = $this->generaRandom();
        return $val;
    }
    function generaRandom2()
    {
        $val = rand();
        if( $this->modelo->revisaRand2($val)->num_rows()>0)
            $val = $this->generaRandom2();
        return $val;
    } 
    function generaRandom3()
    {
        $val = rand();
        if( $this->modelo->revisaRand3($val)->num_rows()>0)
            $val = $this->generaRandom3();
        return $val;
    }
    function validaContasena()
    {
        $idUsuario  = $this->input->post('idusuario');
        $contrasena = $this->input->post('contrasena');
        $msj = "Usuario no válido";
        if($this->modelo->validaUsuario($idUsuario,$contrasena)> 0){
            $msj = 'ok';
        }
        echo json_encode(array('msj'=>$msj));
    }
    function actualizarNombreUsuario(){
        $idusuario = $this->input->post('idUsuario');
        $nombreUsuario = $this->input->post('nombreUsuario');
        $this->modelo->actualizarNombreUsuario($idusuario,$nombreUsuario);
    }
    function actualizarNombreUsuarioyContrasena()
    {
        $idUsuario = $this->input->post('idUsuario');
        $nombreUsuario = $this->input->post('nombreUsuario');
        $contrasena = $this->input->post('contrasena');
        $this->modelo->actualizarNombreUsuarioyContrasena($idUsuario,$nombreUsuario,$contrasena);
    }
    function validaRut()
    {
        $rut = $this->input->post('RUT');
        $dig = $this->input->post('Digito');
        $valida = "false";
        if ($dig == $this->modelo->validaRut($rut))
        {
            $valida = "true";
            if($this->modelo->buscaProfesorPorID($rut)->num_rows() > 0)
                $valida = "existe";
        }
        echo json_encode(array('valida'=>$valida));
    }
    function guardarNuevoProfesor()
    {
        $rut        = $this->input->post('rut');
        $digito     = $this->input->post('digito');
        $nombres    = $this->input->post('nombres');
        $aPaterno   = $this->input->post('aPaterno');
        $aMaterno   = $this->input->post('aMaterno');
        $direccion  = $this->input->post('direccion');
        $telefono   = $this->input->post('telefono');
        $prevision  = $this->input->post('prevision');
        $AFP        = $this->input->post('AFP');
        $titulo     = $this->input->post('titulo');
        $mension    = $this->input->post('mension');
        $fNacimiento = $this->input->post('fNacimiento');
        $fNacimiento = $this->convierteFecha1($fNacimiento);
        //echo $fNacimiento;
        $this->modelo->guardarNuevoProfesor($rut,$digito,$nombres,$aPaterno,$aMaterno,$direccion,$telefono,$prevision,$AFP,$titulo,$mension,$fNacimiento);
        $profesores = $this->modelo->buscaTodosProfesores();
        $datos['cantProfesores'] = $profesores->num_rows();
        $datos['profesores'] = $profesores->result();
        $this->load->view('divCrearProfesor',$datos);
    }
    function convierteFecha1($fecha) //convierte de 11-01-2011 a -> 11-Jun-2011
    {
        $fechas = array();
        $fechas = explode('-',$fecha);
        $mes = null;
        if($fechas[1] == '1')
            $mes='JAN';
        if($fechas[1] == '2')
            $mes='FEB';
        if($fechas[1] == '3')
            $mes='MAR';
        if($fechas[1] == '4')
            $mes='APR';
        if($fechas[1] == '5')
            $mes='MAY';
        if($fechas[1] == '6')
            $mes='JUN';
        if($fechas[1] == '7')
            $mes='JUL';
        if($fechas[1] == '8')
            $mes='AUG';
        if($fechas[1] == '9')
            $mes='SEP';
        if($fechas[1] == '10')
            $mes='OCT';
        if($fechas[1] == '11')
            $mes='NOV';
        if($fechas[1] == '12')
            $mes='DEC';
        return $fechas[0].'-'.$mes.'-'.$fechas[2];
    }
    function convierteFecha2($fecha) //convierte de 11-Jun-2011 a -> 11-01-2011
    {
        $fechas = array();
        $fechas = explode('-',$fecha);
        $mes = null;
        if($fechas[1] == 'JAN' || $fechas[1] == 'Jan')
            $mes='01';
        if($fechas[1] == 'FEB' || $fechas[1] == 'Feb')
            $mes='02';
        if($fechas[1] == 'MAR' || $fechas[1] == 'Mar')
            $mes='03';
        if($fechas[1] == 'APR' || $fechas[1] == 'Apr')
            $mes='04';
        if($fechas[1] == 'MAY' || $fechas[1] == 'May')
            $mes='05';
        if($fechas[1] == 'JUN' || $fechas[1] == 'Jun')
            $mes='06';
        if($fechas[1] == 'JUL' || $fechas[1] == 'Jul')
            $mes='07';
        if($fechas[1] == 'AUG' || $fechas[1] == 'Aug')
            $mes='08';
        if($fechas[1] == 'SEP' || $fechas[1] == 'Sep')
            $mes='09';
        if($fechas[1] == 'OCT' || $fechas[1] == 'Oct')
            $mes='10';
        if($fechas[1] == 'NOV' || $fechas[1] == 'Nov')
            $mes='11';
        if($fechas[1] == 'DEC' || $fechas[1] == 'Dec')
            $mes='12';
        
        if($fechas[2]<2000)
            $fechas[2] = $fechas[2]+2000;
        return $fechas[0].'-'.$mes.'-'.($fechas[2]);
    }
    function datosEditarProfesor()
    {
        $idprofesor     = $this->input->post('idprofesor');
        $data['digito'] = $this->modelo->validaRut($idprofesor);
        $data['result'] = $this->modelo->buscaProfesorPorID($idprofesor)->result();
        foreach($data['result'] as $row):
            $data['fNacimiento'] = $this->convierteFecha2($row->FECHANACIMIENTO);
        endforeach;
        $this->load->view('tabs2_editarProfesor',$data);
    }
    function guardarEditarProfesor()
    {
        $idprofesor     = $this->input->post('rut');
        $nombres        = $this->input->post('nombres');
        $aPaterno       = $this->input->post('aPaterno');
        $aMaterno       = $this->input->post('aMaterno');
        $direccion      = $this->input->post('direccion');
        $telefono       = $this->input->post('telefono');
        $prevision      = $this->input->post('prevision');
        $AFP            = $this->input->post('AFP');
        $titulo         = $this->input->post('titulo');
        $mension        = $this->input->post('mension');
        $fNacimiento    = $this->input->post('fNacimiento');
        $fNacimiento = $this->convierteFecha1($fNacimiento);
        $this->modelo->guardarEditarProfesor($idprofesor,$nombres,$aPaterno,$aMaterno,$direccion,$telefono,$prevision,$AFP,$titulo,$mension,$fNacimiento);
    }
    function guardaNuevoAnoAcademico()
    {
        $ano = $this->input->post('ano');
        $this->modelo->guardaConfigAnoAcademico($ano,'01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00');
        $anios = $this->modelo->buscaAnioConfigUTP()->result();
        foreach ($anios as $row):
            $data['ultimoAno'] = $row->ANOACADEMICO;
        endforeach;
        $this->load->view('tabs6_anoAcademico',$data);
    }
    function cargaConfigAnoAcademico()
    {
        //Creo el ano académico si es que no se encuentra creado
        $ano = $this->input->post('ano');
        if($this->modelo->buscaConfigAnoAcademico($ano)->num_rows()==0):
            $this->modelo->guardaConfigAnoAcademico($ano,'01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00');
            $datos['bandera'] = 0;
            $datos['cantFeriados']=0;
        else:
            $datos['bandera'] = 1;
            $datos['configUTP'] = $this->modelo->buscaConfigAnoAcademico($ano)->result();
            foreach($datos['configUTP'] as $row):
                if($row->FECHAINICIOA!='01-JAN-00'){
                    $datos['fechaInicioA']  = $this->convierteFecha2($row->FECHAINICIOA);
                    $datos['fechaFinA']     = $this->convierteFecha2($row->FECHAFINA);
                    $datos['fechaInicioPS'] = $this->convierteFecha2($row->FECHAINICIOPS);
                    $datos['fechaFinPS']    = $this->convierteFecha2($row->FECHAFINPS);
                    $datos['fechaInicioSS'] = $this->convierteFecha2($row->FECHAINICIOSS);
                    $datos['fechaFinSS']    = $this->convierteFecha2($row->FECHAFINSS);
                    $datos['fechaFinS4']    = $this->convierteFecha2($row->FECHAFINS4);
                    $datos['fechaFinA4']    = $this->convierteFecha2($row->FECHAFINA4);
                }
                else{
                    $datos['fechaInicioA']  = "";
                    $datos['fechaFinA']     = "";
                    $datos['fechaInicioPS'] = "";
                    $datos['fechaFinPS']    = "";
                    $datos['fechaInicioSS'] = "";
                    $datos['fechaFinSS']    = "";
                    $datos['fechaFinS4']    = "";
                    $datos['fechaFinA4']    = "";
                }
            endforeach;
            $datos['datosFeriados'] = $this->modelo->buscaFeriados($ano)->result();
            $datos['cantFeriados']  = $this->modelo->buscaFeriados($ano)->num_rows();
        endif;
        //Solo cursos que corresponden al UTP logeado!!
        $cursos   = $this->modelo->buscaTodosCursosAsignadosUTP($ano,$this->session->userdata('username'))->result();
        $i=0;
        foreach ($cursos as $row):
            $datos['cursos'.$i] = $this->modelo->buscaDatosCursoPorID($row->IDCURSO)->result();
            $i++;
        endforeach;
        $datos['cantCursos'] = $i;
        $this->load->view('tabs1_configAnoAcademico',$datos);
    }
    function guardarConfiguracionUTP()
    {
        $ano            = $this->input->post('ano');
        $fechaInicioA   = $this->convierteFecha1($this->input->post('fechaInicioA'));
        $fechaFinA      = $this->convierteFecha1($this->input->post('fechaFinA'));
        $fechaInicioPS  = $this->convierteFecha1($this->input->post('fechaInicioPS'));
        $fechaFinPS     = $this->convierteFecha1($this->input->post('fechaFinPS'));
        $fechaInicioSS  = $this->convierteFecha1($this->input->post('fechaInicioSS'));
        $fechaFinSS     = $this->convierteFecha1($this->input->post('fechaFinSS'));
        $fechaFinS4     = $this->convierteFecha1($this->input->post('fechaFinS4'));
        $fechaFinA4     = $this->convierteFecha1($this->input->post('fechaFinA4'));
        $this->modelo->guardaConfigAnoAcademico($ano,$fechaInicioA,$fechaFinA,$fechaInicioPS,$fechaFinPS,$fechaInicioSS,$fechaFinSS,$fechaFinS4,$fechaFinA4);
    }
    function cargaFeriados()
    {
        $ano = $this->input->post('ano');
        $datos['datosFeriados'] = $this->modelo->buscaFeriados($ano)->result();
        $datos['cantFeriados']  = $this->modelo->buscaFeriados($ano)->num_rows();
        $this->load->view('feriados',$datos);
    }
    function guardarFeriados()
    {
        $ano = $this->input->post('ano');
        $fecha = $this->convierteFecha1($this->input->post('fecha'));
        $motivo = $this->input->post('motivo');
        $idferiado = $this->generaRandom3();
        $this->modelo->guardarFeriado($idferiado,$ano,$fecha,$motivo);        
    }
    function eliminaFeriado()
    {
        $idferiado  = $this->input->post('idferiado');
        $this->modelo->eliminaFeriado($idferiado);
    }
    function cargaTabs1_1()
    {
        $ano = $this->input->post('ano');
        $idutp = $this->input->post('idutp');
        //Se debe cargar la configuración que existe para el año academico seleccionado!!
        $datos['profesores']    = $this->modelo->buscaTodosProfesores()->result();
        $datos['asignaturas']   = $this->modelo->buscaTodasAsignatura()->result();
        $datos['cursos']   = $this->modelo->buscaTodosCursosAsignadosUTP($ano,$this->session->userdata('username'))->result();
        //$datos['cursos']        = $this->modelo->buscaTodosCursos()->result();
        $i=0;
        foreach($datos['cursos'] as $row):
            if($this->modelo->buscaDatosCursoProfesorPorID($ano,$row->IDCURSO)->num_rows()==0){
                $datos['cursosSinProfesorGuia'.$i] = $this->modelo->buscaDatosCursoPorID($row->IDCURSO)->result();
                $i++;
            }
        endforeach;
        $datos['indice'] = $i;
        
        $cursosUTP = $this->modelo->buscaCursosEncargadoUTP2($ano,$idutp);
        $i=0;
        foreach($cursosUTP->result() as $row):
            $profesoresAsignados = $this->modelo->buscaDatosCursoProfesorPorID($ano,$row->IDCURSO);
            foreach ($profesoresAsignados->result() as $row):
                $datos['profesorGuiaTabla'.$i] = $this->modelo->buscaProfesorPorID($row->IDPROFESOR)->result();
                $datos['cursosTabla'.$i] = $this->modelo->buscaDatosCursoPorID($row->IDCURSO)->result();
                $i++;
            endforeach;
        endforeach;
        
        $datos['cantProfesoresAsignados'] = $i;
        
        $this->load->view('tabs1_1',$datos);
    }
    function cargaTabs1_12()
    {
        $ano = $this->input->post('ano');
        $idutp = $this->input->post('idutp');
        
        $cursosUTP = $this->modelo->buscaCursosEncargadoUTP2($ano,$idutp);
        $i=0;
        foreach($cursosUTP->result() as $row):
            $profesoresAsignados = $this->modelo->buscaDatosCursoProfesorPorID($ano,$row->IDCURSO);
            foreach ($profesoresAsignados->result() as $row):
                $datos['profesorGuiaTabla'.$i] = $this->modelo->buscaProfesorPorID($row->IDPROFESOR)->result();
                $datos['cursosTabla'.$i] = $this->modelo->buscaDatosCursoPorID($row->IDCURSO)->result();
                $i++;
            endforeach;
        endforeach;
        
        $datos['cantProfesoresAsignados'] = $i;
        $this->load->view('tabs1_12',$datos);
    }
    function cargaTabs1_2()
    {
        $ano = $this->input->post('ano');
        
        $datos['cursos']   = $this->modelo->buscaTodosCursosAsignadosUTP($ano,$this->session->userdata('username'))->result();
        $i=0;
        $j=0;
        foreach($datos['cursos'] as $row):
            $datos['cursos'.$j] = $this->modelo->buscaDatosCursoPorID($row->IDCURSO)->result();
            $j++;
            if($this->modelo->buscaDatosProfesorCursoAsignaturaPorID($ano,$row->IDCURSO)->num_rows()==0){
                $datos['cursosNoAsignados'.$i] = $this->modelo->buscaDatosCursoPorID($row->IDCURSO)->result();
                $i++;
            }
        endforeach;
        $datos['indice1'] = $i;
        $datos['indice2'] = $j;
        
        $this->load->view('tabs1_2',$datos);
    }
    function tabs1_2_asignaturas()
    {
        $ano = $this->input->post('ano');
        $idcurso = $this->input->post('curso');
        $asignaturas = $this->modelo->buscaTodasAsignatura()->result();
        $i=0;
        foreach($asignaturas as $row):
            if($this->modelo->buscaprofesorCursoAsignatura2($ano,$idcurso,$row->IDASIGNATURA)->num_rows() == 0):
                $datos['asignaturasNoAsignadas'.$i] = $this->modelo->buscaDatosAsignaturaPorID($row->IDASIGNATURA)->result();
                $i++;
            endif;
        endforeach;
        $datos['indice3'] = $i;
        
        $this->load->view('tabs1_2_asignaturas',$datos);
    }
    function tabs1_2_tablaAsignaturasCurso()
    {
        $ano = $this->input->post('ano');
        $idcurso = $this->input->post('curso');
        $datos['nombreCurso'] = $this->modelo->buscaDatosCursoPorID($idcurso)->result();
        $profesorCursoAsignatura = $this->modelo->buscaprofesorCursoAsignatura($ano,$idcurso)->result();
        $i=0; 
        foreach($profesorCursoAsignatura as $row):
            $datos['asignaturasAsignadas'.$i] = $this->modelo->buscaDatosAsignaturaPorID($row->IDASIGNATURA)->result();
            $i++;
        endforeach;
        $datos['indice2'] = $i;
        $this->load->view('tabs1_2_tablaAsignaturasCurso',$datos);
    }
    function guardarProfesorGuia()
    {
        $ano        = $this->input->post('anoacademico');
        $idprofesor = $this->input->post('idprofesor');
        $idcurso    = $this->input->post('idcurso');
        
        $this->modelo->guardarProfesorGuia($ano,$idprofesor,$idcurso);
    }
    function eliminaProfesorGuia()
    {
        $ano        = $this->input->post('anoacademico');
        $idprofesor = $this->input->post('idprofesor');
        $idcurso    = $this->input->post('idcurso');
        
        $this->modelo->eliminaProfesorGuia($ano,$idprofesor,$idcurso);
    }
    function eliminarAsignaturaCurso()
    {
        $idasignatura = $this->input->post('idasignatura');
        $ano = $this->input->post('ano');
        $curso = $this->input->post('curso');
        $this->modelo->eliminarAsignaturaCurso($idasignatura,$ano,$curso);
    }
    function asignarAsignaturaCurso()
    {
        $idasignatura = $this->input->post('asignatura');
        $ano = $this->input->post('ano');
        $curso = $this->input->post('curso');
        $this->modelo->asignarAsignaturaCurso($idasignatura,$ano,$curso,'11111111');
    }
    function cargaConfigAsignaturas()
    {
        $ano = $this->input->post('ano');
        $idcurso = $this->input->post('curso');
        $datos['nombreCurso'] = $this->modelo->buscaDatosCursoPorID($idcurso)->result();
        $profesorCursoAsignatura = $this->modelo->buscaprofesorCursoAsignatura($ano,$idcurso)->result();
        $i=0;
        foreach($profesorCursoAsignatura as $row):
            $datos['profesorAsignadoCant'.$i] = '0';
            if($row->IDPROFESOR!='11111111'){
                $datos['profesorAsignado'.$i] = $this->modelo->buscaProfesorPorID($row->IDPROFESOR)->result();
                $datos['profesorAsignadoCant'.$i] = '1';
            }
            $datos['asignaturasAsignadas'.$i] = $this->modelo->buscaDatosAsignaturaPorID($row->IDASIGNATURA)->result();
            $datos['configurada'.$i] = 'no';
            $configurada = $this->modelo->buscaDatosCalificacion($row->IDASIGNATURA,$ano);
            if($configurada->num_rows() > 0):
                $datos['configurada'.$i] = 'si';
            endif;
            $i++;
        endforeach;
        $datos['indice2'] = $i;
        $datos['profesores'] = $this->modelo->buscaTodosProfesores()->result();
        foreach( $datos['nombreCurso']  as $row):
            $profesorGuia = $this->modelo->buscaProfesorGuia($ano,$row->IDCURSO)->result();
            $datos['profesorGuiaCant'] = $this->modelo->buscaProfesorGuia($ano,$row->IDCURSO)->num_rows();
            foreach($profesorGuia as $row1):
                $datos['profesorGuia'] = $this->modelo->buscaProfesorPorID($row1->IDPROFESOR)->result();
            endforeach;
        endforeach;
        
        
        $this->load->view('tablaConfigAsignatura',$datos);
    }
    function actualizaProfesorCursoAsignatura()
    {
        $ano = $this->input->post('ano');
        $idcurso = $this->input->post('curso');
        $idprofesor = $this->input->post('profesor');
        $idasignatura = $this->input->post('asignatura');
        $this->modelo->actualizaProfesorCursoAsignatura($ano,$idcurso,$idprofesor,$idasignatura);
    }
    function cargaListadoAlumnos()
    {
        $ano =  DATE('Y');
        $idcurso = $this->input->post('curso');
        $idprofesor = $this->session->userdata('username');
        $idasignatura = $this->input->post('asignatura');
        $electivo = $this->modelo->buscaAsignaturaElectiva($idasignatura)->num_rows();
        
        if($electivo > 0):
            $alumnos = $this->modelo->cargaListadoAlumnosConElectivo2($ano,$idcurso,$idasignatura);
        else:
            $alumnos = $this->modelo->cargaListadoAlumnos($ano,$idcurso);
        endif;
        
        $datos['largo'] = $alumnos->num_rows();
        $datos['alumnos'] = $alumnos->result();
        $datos['datosCalificacion'] = $this->modelo->buscaDatosCalificacion($idasignatura,$ano)->result();
        
        $i=1;
        foreach ($alumnos->result() as $row1):
            $j=0;
            foreach($datos['datosCalificacion'] as $row):
                $semestre=$this->semestre(strtotime(DATE('d-m-Y')));
                $notas = $this->modelo->buscaNota($row1->IDALUMNO,$ano,$idasignatura,$row->IDCALIFICACION,$semestre);
                if($notas->num_rows() > 0):
                    $datos['cantNota'.$i.$j] = '1';
                    $datos['nota'.$i.$j] = $notas->result();
                else:
                    $datos['cantNota'.$i.$j] = '0';
                    $datos['nota'.$i.$j] = '';
                endif;
                $j++;
            endforeach;
            $i++;
        endforeach;
 
        $fecha1 = DATE('d-M-Y');
        $fecha1 = strtotime($this->convierteFecha2($fecha1));
        $i=0;
        foreach ($datos['datosCalificacion'] as $row):
            $fecha_entrada = strtotime($this->convierteFecha2($row->FECHA));
            if($fecha1 > $fecha_entrada){
                $datos['bloqueo'.$i] = 'si';
            }else{  
                $datos['bloqueo'.$i] = 'no';
            }
            $i++;
        endforeach;
        
        //Calcular Promedios
        $datosCurso =  $this->modelo->buscaDatosCursoPorID($idcurso);
        foreach($datosCurso->result() as $row):
            $orden = $row->ORDEN;
        endforeach;
        $j=1;
        $promedio[$j] = 0;
        $verPonde = 'si';
        foreach($datos['alumnos'] as $row):
            $semestre=$this->semestre(strtotime(DATE('d-m-Y')));
            $notas = $this->modelo->buscaNota2($row->IDALUMNO,$ano,$idasignatura,$semestre);
            $cantNotas = $notas->num_rows();
            $notas = $notas->result();
            $i=0;
            $suma=0;
            if($orden<6){
                $cantC2 = $this->modelo->buscaCalifC2($ano,$idasignatura,"C/2",$row->IDALUMNO,$semestre);
                $cantNotas = $cantNotas + $cantC2->num_rows();
                $verPonde='no';
                foreach($notas as $row1):
                    $ponderacion[$i] = 100/$cantNotas;
                    $notaC2 = $this->modelo->buscaCalifC2porIDCalificacion($ano,$idasignatura,$row1->IDCALIFICACION,$semestre)->num_rows();
                    if($notaC2 > 0)
                        $nota[$i] = ($row1->NOTAS)*2;
                    else
                        $nota[$i] = $row1->NOTAS;
                    $i++;
                endforeach;
            }
            else{
                foreach($notas as $row1):
                    $ponderaciones = $this->modelo->buscaPonderacionNotas($ano,$idasignatura,$row1->IDCALIFICACION,$semestre);
                    foreach($ponderaciones as $row2):
                        $ponderacion[$i] = $row2->PONDERACION;
                        $suma = $suma + $row2->PONDERACION;
                    endforeach;
                    $nota[$i] = $row1->NOTAS;
                    $i++;
                endforeach;
            }
            $promedio=0;
            for($k=0;$k<$i;$k++):
                $promedio = $promedio + $nota[$k]*$ponderacion[$k]/100;
            endfor;
            if($suma!=0)
                $datos['promedio'.$j] = round($promedio/($suma/100));
            else
                $datos['promedio'.$j] = round($promedio);
            $j++;
        endforeach;
        $datos['verPonde'] = $verPonde;
        $this->load->view('tabs3_ingresarNotas',$datos);
    }
    function almacenarCalificaciones()
    {
        $idAlumno= $this->input->post('idAlumno');
        $calif = $this->input->post('Nota');
        $idCalif = $this->input->post('idCalif');
        $idCurso = $this->input->post('idCurso');
        $idAsignatura = $this->input->post('idAsignatura');
        $ano = DATE('Y');
        $fecha = DATE('d-M-Y');
        $fecha = $this->convierteFecha2($fecha);
        $fecha = $this->convierteFecha1($fecha);
        $semestre = $this->semestre(strtotime(DATE('d-m-Y')));
        $tipo = $this->modelo->buscaFechaCalificacion($ano,$idAsignatura,$idCalif,$semestre);
        foreach($tipo->result() as $row):
            $tipo = $row->TIPOCALIFICACION;
        endforeach;
        
        $this->modelo->almacenarCalificaciones($idAlumno,$ano,$idAsignatura,$idCalif,$fecha,$calif,$idCurso,$tipo,$semestre);
    }
    function cargaConfigurarAsignatura()
    {
        $ordenCurso = $this->input->post('ordenCurso');
        $nombreAsignatura = $this->input->post('nombreAsignatura');
        $idAsignatura = $this->input->post('idAsignatura');
        $ano = DATE('Y');
        $semestre = $this->semestre(strtotime(DATE('d-m-Y')));
        $fechaCalificacion = $this->modelo->buscaFechaCalificacion2($ano,$idAsignatura,$semestre);
        $datos['ponderacion'] = 'si'; //7º en adelante es con Ponderaciones
        if($ordenCurso<8) //Desde 6º básico hacia atrás no tiene ponderaciones!
            $datos['ponderacion'] = 'no';
        
        $fecha1 = DATE('d-M-Y');
        $fecha1 = strtotime($this->convierteFecha2($fecha1));
        $i=0;
        if($fechaCalificacion->num_rows()>0){     
            $datos['evaluaciones']=$fechaCalificacion->result();
            foreach($datos['evaluaciones'] as $row):
                $fecha_entrada = strtotime($this->convierteFecha2($row->FECHA));
                $calificaciones = $this->modelo->buscaNota3($ano,$idAsignatura,$row->IDCALIFICACION)->num_rows();
                $datos['bloqueo'.$i] = 'no';
                if($fecha1 > $fecha_entrada || $calificaciones > 0)
                    $datos['bloqueo'.$i] = 'si';
                $i++;
            endforeach;
        }
        $mayor =0;
        foreach($fechaCalificacion->result() as $row)
        {
            if(floor($row->IDCALIFICACION) > $mayor)
            {
                $mayor=floor($row->IDCALIFICACION);
            }
        }
        $datos['indice'] = $i;
        $datos['cantEvaluaciones'] = $mayor;
        $datos['nombreAsignatura'] = $nombreAsignatura;
        $datos['idAsignatura'] = $idAsignatura;
        $datos['ultimaFila'] = $fechaCalificacion->num_rows();
        $this->load->view('dialogConfigAsignaturas',$datos);
    }
    function guardaFechaCalificacion()
    {
        $ano = DATE('Y');
        $idasignatura = $this->input->post('idasignatura');
        $idcalificacion  = $this->input->post('idcalificacion');
        $fecha = $this->input->post('fecha');
        $ponde = $this->input->post('ponde');
        
        $tipo = $this->input->post('tipo');
        if(strlen($fecha) == 9)
            $fecha = $this->convierteFecha2 ($fecha);
        $fecha1 = DATE('d-M-Y');
        $fecha1 = strtotime($this->convierteFecha2($fecha1));
        $fecha2 = $fecha;
        $fecha = strtotime($fecha);
        $semestre = $this->semestre($fecha);
        $calificaciones = $this->modelo->buscaNota3($ano,$idasignatura,$idcalificacion )->num_rows();
        //La nueva fecha debe ser mayor 0 igual a la fecha de hoy y la evaluación no ha sido registrada! (tabla calificaciones)
        if($fecha1<=$fecha && $calificaciones == 0)
        {
            $fecha = $this->convierteFecha1($fecha2);
            $this->modelo->guardaFechaCalificacion($ano,$idasignatura,$idcalificacion,$fecha,$ponde,$tipo,$semestre);   
        }
    }
    function eliminaConfigCalificacion()
    {
        $idEvaluacion = $this->input->post('idEvaluacion');
        $idAsignatura = $this->input->post('idAsignatura');
        $ano = DATE('Y');
        $semestre = $this->semestre(strtotime(DATE('d-m-Y')));
        $this->modelo->eliminaConfigCalificacion($idEvaluacion,$idAsignatura,$ano,$semestre);
    }
    function semestre($fecha)
    {
        $fechas = $this->modelo->buscaConfigAnoAcademico(DATE('Y'))->result();
        foreach($fechas as $row):
            $inicioPS   = strtotime($this->convierteFecha2($row->FECHAINICIOPS));
            $finPS      = strtotime($this->convierteFecha2($row->FECHAFINPS));
            $inicioSS   = strtotime($this->convierteFecha2($row->FECHAINICIOSS));
            $finSS      = strtotime($this->convierteFecha2($row->FECHAFINSS));
        endforeach;
        $semestre=0;
        if($fecha >= $inicioPS && $fecha <= $finPS)
            $semestre =1;
        else
            if($fecha >= $inicioSS && $fecha <= $finSS)
                $semestre=2;
        return $semestre;
    }
    function verificaFeriadoySemestres()
    {
        $fecha  = $this->input->post('fecha');
        $orden  = $this->input->post('orden');
        $fecha1  = $this->convierteFecha1($fecha);
        $msj    = 'no';
        $tipo   = null;
        $leyenda = '';

        $fecha = strtotime($fecha);
        $fechas = $this->modelo->buscaConfigAnoAcademico(DATE('Y'))->result();
        foreach($fechas as $row):
            $finS4      = strtotime($this->convierteFecha2($row->FECHAFINS4));
        endforeach;
        $msj='no';
        $leyenda='';
        if($fecha>$finS4 && $orden == 13)
        {
            $msj='si';
            $leyenda='Fecha fuera del segundo semestre de 4º Medio';
        }
        $fecha2 = DATE('d-M-Y');
        $fecha2 = strtotime($this->convierteFecha2($fecha2));
        $semestreA = $this->semestre($fecha2);
        $semestreS = $this->semestre($fecha);
        
        if($semestreA == 0 && $msj=='no')
        {
            $msj='si';
            $leyenda='Aun no comienza un semestre académico';
        }
        else{
            if($semestreS == 0 && $msj=='no')
            {
                $msj='si';
                $leyenda='Fecha fuera de un semestre académico';
            }
            else{
                if($semestreA != $semestreS && $msj=='no')
                {
                    $msj='si';
                    $leyenda = 'Fecha fuera del semestre en curso';
                }
            }
        }

        if($this->modelo->verificaFeriado($fecha1)->num_rows() > 0 && $msj =='no') //Existe la fecha como feriado!
        {
            $msj = 'si';
            foreach($this->modelo->verificaFeriado($fecha1)->result() as $row):
                $tipo = $row->MOTIVO;
            endforeach;
            $leyenda = 'Fecha seleccionada es feriado por '.$tipo;
        }
        echo json_encode(array('msj'=>$msj,'leyenda'=>$leyenda));
    }
    function modificarCalificacion()
    {
        $curso = $this->input->post('curso');
        $ano = DATE('Y');
        
        $semestre = $this->semestre(strtotime(DATE('d-m-Y')));
        $asignaturas = $this->modelo->buscaAsignaturasPorCursoAno($ano,$curso,$semestre)->result();
        
        $i=0;
        foreach($asignaturas as $row):
            $datos['asignatura'.$i] = $this->modelo->buscaDatosAsignaturaPorID($row->IDASIGNATURA)->result();
            $i++;
        endforeach;
        $datos['cantAsignaturas'] = $i;
        
        $datos['alumnos'] = $this->modelo->cargaListadoAlumnos($ano,$curso)->result();
        
        $this->load->view('divModificarCalificacion',$datos);
    }
    function cargaFechasCalificacion()
    {
        $idasignatura = $this->input->post('idasignatura');
        $idcurso = $this->input->post('idcurso');
        $ano = DATE('Y');
        $semestre=$this->semestre(strtotime(DATE('d-m-Y')));
        $datos['fechas'] = $this->modelo->cargaFechasCalificacion($ano,$idasignatura,$idcurso,$semestre)->result();
        $this->load->view('cargarFechasModifCalif',$datos);
    }
    function modificacionCalificacion()
    {   
        $idasignatura = $this->input->post('idasignatura');
        $idcurso = $this->input->post('idcurso');
        $idalumno = str_replace(" ","",$this->input->post('idalumno'));
        $fecha = $this->input->post('fecha');
        $ano = DATE('Y');
        if($idalumno == "Todos")
        {
            $alumnos = $this->modelo->cargaListadoAlumnos($ano,$idcurso)->result();
            $semestre=$this->semestre(strtotime($fecha));
            foreach($alumnos as $row):
                $this->modelo->modificacionCalificacion($ano,$idasignatura,$row->IDALUMNO,$idcurso,$fecha,$semestre);
            endforeach;
        }
        else
        {
            $this->modelo->modificacionCalificacion($ano,$idasignatura,$idalumno,$idcurso,$fecha);
        }
    }
    function tabs_Curso()
    {
        $idusuario  =   $this->session->userdata('username');
        $ano = DATE('Y');        
        //Buscar cuantos cursos tiene a su cargo
        $cursos = $this->modelo->buscaCursosProfesorGuia($ano,$idusuario);
        $i=0;
        foreach($cursos->result() as $row):
            $datos['datosCurso'.$i] = $this->modelo->buscaDatosCursoPorID($row->IDCURSO)->result();
            $i++;
        endforeach;
        $datos['cantCursos']=$i;
        
        //Buscar si es jefe de algún 3º o 4º medio
        $datos['electivo'] = 'no';
        $j=0;
        if($i==1): //Solo tiene un curso
            foreach ($datos['datosCurso0'] as $row):
                if($row->ORDEN == 12 || $row->ORDEN == 13):
                    $datos['electivo'] = 'si';
                    //Si tiene electivos busco la lista del curso y la lista de los ya asignados
                    $alumnosConElectivos = $this->modelo->cargaListadoAlumnosConElectivo($ano,$row->IDCURSO);
                    $datos['cantElectivosAsignados'] = $alumnosConElectivos->num_rows();
                    $datos['alumnosConElectivos'] = $alumnosConElectivos->result();
                    $datos['alumnosSinElectivos'] = $this->modelo->cargaListadoAlumnosSinElectivo($ano,$row->IDCURSO)->result();
                    $datos['asignaturasElectivas'] = $this->modelo->cargaAsignaturasElectivas()->result();
                endif;
                $asignaturasCurso = $this->modelo->buscaprofesorCursoAsignatura($ano,$row->IDCURSO)->result();
                foreach($asignaturasCurso as $row1):
                    $datos['asignaturasCurso'.$j] = $this->modelo->buscaDatosAsignaturaPorID($row1->IDASIGNATURA)->result();
                    $j++;
                endforeach;
                $datos['idCurso']=$row->IDCURSO;
                $datos['alumnos'] = $this->modelo->cargaListadoAlumnos($ano,$row->IDCURSO)->result();
                $datos['cantAsignaturas']=$j;

                //Busca los alumnos ya Eximidos
                $j=0;
                foreach($datos['alumnos'] as $row1):
                    $eximidos = $this->modelo->buscaEximidos($ano,$row1->IDALUMNO);
                    if($eximidos->num_rows()>0):
                        foreach($eximidos->result() as $row2):
                            $datos['motivo'.$j] = $row2->MOTIVO;
                            $datos['alumnoEximido'.$j] = $this->modelo->buscaAlumnosPorID($row2->IDALUMNO)->result();
                            $datos['asignaturaEximida'.$j] = $this->modelo->buscaDatosAsignaturaPorID($row2->IDASIGNATURA)->result();
                            $j++;
                        endforeach;
                    endif;
                endforeach;
                $datos['cantEximidos']=$j;
            endforeach;
        endif;
        
        $this->load->view('tabs_Curso',$datos);
    }
    function guardarEximido(){
        
        $alumno = $this->input->post('alumno');
        $asignatura = $this->input->post('asignatura');
        $motivo = $this->input->post('motivo');
        $curso = $this->input->post('curso');
        $ano = DATE('Y');
        $this->modelo->guardarEximido($ano,$alumno,$asignatura,$motivo);
        
        $datos['alumnos'] = $this->modelo->cargaListadoAlumnos($ano,$curso)->result();
        $j=0;
        foreach($datos['alumnos'] as $row1):
            $eximidos = $this->modelo->buscaEximidos($ano,$row1->IDALUMNO);
            if($eximidos->num_rows()>0):
                foreach($eximidos->result() as $row2):
                    $datos['motivo'.$j] = $row2->MOTIVO;
                    $datos['alumnoEximido'.$j] = $this->modelo->buscaAlumnosPorID($row2->IDALUMNO)->result();
                    $datos['asignaturaEximida'.$j] = $this->modelo->buscaDatosAsignaturaPorID($row2->IDASIGNATURA)->result();
                    $j++;
                endforeach;
            endif;
        endforeach;
        $datos['cantEximidos']=$j;

        $this->load->view('divTablaEximidos',$datos);
    }
    function eliminarEximido()
    {
        $alumno = $this->input->post('alumno');
        $asignatura = $this->input->post('asignatura');
        $curso = $this->input->post('curso');
        $ano  = DATE('Y');
        
        $this->modelo->eliminarEximido($ano,$alumno,$asignatura);
        
        $datos['alumnos'] = $this->modelo->cargaListadoAlumnos($ano,$curso)->result();
        $j=0;
        foreach($datos['alumnos'] as $row1):
            $eximidos = $this->modelo->buscaEximidos($ano,$row1->IDALUMNO);
            if($eximidos->num_rows()>0):
                foreach($eximidos->result() as $row2):
                    $datos['motivo'.$j] = $row2->MOTIVO;
                    $datos['alumnoEximido'.$j] = $this->modelo->buscaAlumnosPorID($row2->IDALUMNO)->result();
                    $datos['asignaturaEximida'.$j] = $this->modelo->buscaDatosAsignaturaPorID($row2->IDASIGNATURA)->result();
                    $j++;
                endforeach;
            endif;
        endforeach;
        $datos['cantEximidos']=$j;
        
        $this->load->view('divTablaEximidos',$datos);
    }
    function verificaEximido(){
        $alumno = $this->input->post('alumno');
        $asignatura = $this->input->post('asignatura');
        $ano = DATE('Y');
        $msj = 'si';
        $eximido = $this->modelo->verificaEximido($ano,$alumno,$asignatura)->num_rows();
        if($eximido == 0)
            $msj = 'no';
        echo json_encode(array("msj"=>$msj));
    }
    function cargaCursoEspecifico()
    {
        $curso = $this->input->post('curso');
        $idusuario  =   $this->session->userdata('username');
        $ano = DATE('Y');        
        //Buscar cuantos cursos tiene a su cargo
        
        $datos['datosCurso0'] = $this->modelo->buscaDatosCursoPorID($curso)->result();
        
        //Buscar si es jefe de algún 3º o 4º medio
        $datos['electivo'] = 'no';
        $j=0;$i=0;
        foreach ($datos['datosCurso0'] as $row):
            if($row->ORDEN == 12 || $row->ORDEN == 13):
                $datos['electivo'] = 'si';
                //Si tiene electivos busco la lista del curso y la lista de los ya asignados
                $alumnosConElectivos = $this->modelo->cargaListadoAlumnosConElectivo($ano,$row->IDCURSO);
                $k=0;
                foreach($alumnosConElectivos->result() as $row1):
                    $datos['asignaturaAsignada'.$k] = $this->modelo->buscaDatosAsignaturaPorID($row1->IDASIGNATURA)->result();
                    $k++;
                endforeach;
                $datos['cantElectivosAsignados'] = $alumnosConElectivos->num_rows();
                $datos['alumnosConElectivos'] = $alumnosConElectivos->result();
                $datos['alumnosSinElectivos'] = $this->modelo->cargaListadoAlumnosSinElectivo($ano,$row->IDCURSO)->result();
                $datos['asignaturasElectivas'] = $this->modelo->cargaAsignaturasElectivas()->result();
            endif;
            $asignaturasCurso = $this->modelo->buscaprofesorCursoAsignatura($ano,$row->IDCURSO)->result();
            foreach($asignaturasCurso as $row1):
                $datos['asignaturasCurso'.$j] = $this->modelo->buscaDatosAsignaturaPorID($row1->IDASIGNATURA)->result();
                $j++;
            endforeach;
            $datos['idCurso']=$row->IDCURSO;
            $datos['alumnos'] = $this->modelo->cargaListadoAlumnos($ano,$row->IDCURSO)->result();
            $datos['cantAsignaturas']=$j;

            //Busca los alumnos ya Eximidos
            $j=0;
            foreach($datos['alumnos'] as $row1):
                $eximidos = $this->modelo->buscaEximidos($ano,$row1->IDALUMNO);
                if($eximidos->num_rows()>0):
                    foreach($eximidos->result() as $row2):
                        $datos['motivo'.$j] = $row2->MOTIVO;
                        $datos['alumnoEximido'.$j] = $this->modelo->buscaAlumnosPorID($row2->IDALUMNO)->result();
                        $datos['asignaturaEximida'.$j] = $this->modelo->buscaDatosAsignaturaPorID($row2->IDASIGNATURA)->result();
                        $j++;
                    endforeach;
                endif;
            endforeach;
            $datos['cantEximidos']=$j;
        endforeach;
        $datos['curso'] = $curso;
        $this->load->view('tabs_Curso2',$datos);
    }
    function guardarElectivo()
    {
        $alumno  = $this->input->post('alumno');
        $asignatura = $this->input->post('asignatura');
        $curso = $this->input->post('curso');
        $ano = DATE('Y');
        $this->modelo->guardarElectivo($alumno,$asignatura,$ano);
        $this->cargaTablaElectivos($ano,$curso);
    }
    function modificarElectivo()
    {
        $alumno  = $this->input->post('alumno');
        $asignatura = $this->input->post('asignatura');
        $curso = $this->input->post('curso');
        $ano = DATE('Y');
        $this->modelo->modificarElectivo($alumno,$asignatura,$ano);
        $this->cargaTablaElectivos($ano,$curso);
    }
    function eliminarElectivo()
    {
        $alumno  = $this->input->post('alumno');
        $asignatura = $this->input->post('asignatura');
        $curso = $this->input->post('curso');
        $ano = DATE('Y');
        $this->modelo->eliminarElectivo($alumno,$asignatura,$ano);
        $this->cargaTablaElectivos($ano,$curso);
    }
    function cargaTablaElectivos($ano,$curso)
    {
        $alumnosConElectivos = $this->modelo->cargaListadoAlumnosConElectivo($ano,$curso);
        $k=0;
        foreach($alumnosConElectivos->result() as $row1):
            $datos['asignaturaAsignada'.$k] = $this->modelo->buscaDatosAsignaturaPorID($row1->IDASIGNATURA)->result();
            $k++;
        endforeach;
        $datos['cantElectivosAsignados'] = $alumnosConElectivos->num_rows();
        $datos['alumnosConElectivos'] = $alumnosConElectivos->result();
        $datos['alumnosSinElectivos'] = $this->modelo->cargaListadoAlumnosSinElectivo($ano,$curso)->result();
        $datos['asignaturasElectivas'] = $this->modelo->cargaAsignaturasElectivas()->result();
        $this->load->view('divTablaElectivos',$datos);
    }
}