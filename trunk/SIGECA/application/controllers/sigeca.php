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
        $this->load->view('principal',$datos);
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
        $this->modelo->guardarNuevaAsignatura($idasignatura,$nombre);
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
        $this->modelo->guardarEditarAsignatura($idasignatura,$nombre);
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
        $this->modelo->guardaConfigAnoAcademico($ano,'01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00');
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
            $this->modelo->guardaConfigAnoAcademico($ano,'01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00','01-JAN-00');
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
                }
                else{
                    $datos['fechaInicioA']  = "";
                    $datos['fechaFinA']     = "";
                    $datos['fechaInicioPS'] = "";
                    $datos['fechaFinPS']    = "";
                    $datos['fechaInicioSS'] = "";
                    $datos['fechaFinSS']    = "";
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
        $this->modelo->guardaConfigAnoAcademico($ano,$fechaInicioA,$fechaFinA,$fechaInicioPS,$fechaFinPS,$fechaInicioSS,$fechaFinSS);
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
        $idferiado = $this->generaRandom3();
        $this->modelo->guardarFeriado($idferiado,$ano,$fecha);        
    }
    function eliminaFeriado()
    {
        $idferiado  = $this->input->post('idferiado');
        $this->modelo->eliminaFeriado($idferiado);
    }
    function cargaTabs1_1()
    {
        $ano = $this->input->post('ano');
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
        
        $profesoresAsignados = $this->modelo->buscaDatosCursoProfesor($ano);
        $datos['cantProfesoresAsignados'] = $profesoresAsignados->num_rows();
        $i=0;
        foreach ($profesoresAsignados->result() as $row):
            $datos['profesorGuiaTabla'.$i] = $this->modelo->buscaProfesorPorID($row->IDPROFESOR)->result();
            $datos['cursosTabla'.$i] = $this->modelo->buscaDatosCursoPorID($row->IDCURSO)->result();
            $i++;
        endforeach;
        
        $this->load->view('tabs1_1',$datos);
    }
    function cargaTabs1_12()
    {
        $ano = $this->input->post('ano');
        
        $profesoresAsignados = $this->modelo->buscaDatosCursoProfesor($ano);
        $datos['cantProfesoresAsignados'] = $profesoresAsignados->num_rows();
        $i=0;
        foreach ($profesoresAsignados->result() as $row):
            $datos['profesorGuiaTabla'.$i] = $this->modelo->buscaProfesorPorID($row->IDPROFESOR)->result();
            $datos['cursosTabla'.$i] = $this->modelo->buscaDatosCursoPorID($row->IDCURSO)->result();
            $i++;
        endforeach;
        
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
                
        $alumnos = $this->modelo->cargaListadoAlumnos($ano,$idcurso);
        $datos['largo'] = $alumnos->num_rows();
        $datos['alumnos'] = $alumnos->result();
        $datos['datosCalificacion'] = $this->modelo->buscaDatosCalificacion($idasignatura,$ano)->result();
        
        $i=1;
        foreach ($alumnos->result() as $row1):
            $j=0;
            foreach($datos['datosCalificacion'] as $row):
                $notas = $this->modelo->buscaNota($row1->IDALUMNO,$ano,$idasignatura,$row->IDCALIFICACION);
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
        /*foreach($datos['alumnos'] as $row):
            $notas = $this->modelo->buscaNota2($row->IDALUMNO,$ano,$idasignatura);
            $cantNotas = $notas->num_rows();
            $notas = $notas->result();
            $i=0;
            foreach($notas as $row1):
                $ponderacion[$i] = $this->modelo->buscaPonderacionNotas();
                $i++;
            endforeach;
        endforeach;
        */
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
        
        $this->modelo->almacenarCalificaciones($idAlumno,$ano,$idAsignatura,$idCalif,$fecha,$calif,$idCurso); //FALTA EL TIPO DE CALIFICACION!!
    }
    function cargaConfigurarAsignatura()
    {
        $ordenCurso = $this->input->post('ordenCurso');
        $nombreAsignatura = $this->input->post('nombreAsignatura');
        $idAsignatura = $this->input->post('idAsignatura');
        $ano = DATE('Y');
        $fechaCalificacion = $this->modelo->buscaFechaCalificacion2($ano,$idAsignatura);
        $datos['ponderacion'] = 'si';
        if($ordenCurso<6) //Desde 4º básico hacia atrás!
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
        $calificaciones = $this->modelo->buscaNota3($ano,$idasignatura,$idcalificacion )->num_rows();
        //La nueva fecha debe ser mayor 0 igual a la fecha de hoy y la evaluación no ha sido registrada! (tabla calificaciones)
        if($fecha1<=$fecha && $calificaciones == 0)
        {
            $fecha = $this->convierteFecha1($fecha2);
            $this->modelo->guardaFechaCalificacion($ano,$idasignatura,$idcalificacion,$fecha,$ponde,$tipo);   
        }
    }
    function eliminaConfigCalificacion()
    {
        $idEvaluacion = $this->input->post('idEvaluacion');
        $idAsignatura = $this->input->post('idAsignatura');
        $ano = DATE('Y');
        $this->modelo->eliminaConfigCalificacion($idEvaluacion,$idAsignatura,$ano);
    }
}