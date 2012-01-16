<img alt="Colegio Andes" src="<?=base_url();?>images/banner.GIF"></img>
<div id="tabs">
    <div id="Usuario" style="display:block; float:right; margin-right: 20px; margin-top: 25px; font-size:small;">
        <?php echo "Bienvenido ".$nombre." - ". anchor('sigeca/logout','Salir del Sistema');?>
    </div>
    <?if($cargo=='0'):?>
        <ul>
            <li><a href="<?=base_url();?>index.php/sigeca/tabs_6">Administración</a></li>
            <li><a href="<?=base_url();?>index.php/sigeca/tabs_7">Datos Usuario</a></li>
        </ul>
        
    <?endif;?>
    <?if($cargo=='1'):?>
        <ul>
            <li><a href="<?=base_url();?>index.php/sigeca/tabs_2">Registro Académico</a></li>
            <li><a href="<?=base_url();?>index.php/sigeca/tabs_7">Datos Usuario</a></li>
            <li><a href="#tabs-5">Estadísticos</a></li>
        </ul>
        <div id="tabs-5">
            <p>Informes Estadísticos.</p>
            <p>Se presentarán gráficos de:</p>
            <ul>
                <li>Notas más altas por curso.</li>
                <li>Gráficos comparativos de promedios.</li>
                <li>Asignaturas con promedios más altos.</li>
            </ul>
            <p>Estos podrán ser vistos una ves cerrado el semestro o el año.</p>
        </div>
    <?endif;?>
    <?if($cargo =='2'):?>
        <ul>
            <li><a href="<?=base_url();?>index.php/sigeca/tabs_1">Sección UTP</a></li>
            <?if($utpCursos == 'si'):?>
                <li><a href="<?=base_url();?>index.php/sigeca/tabs_8">Asignaturas</a></li>
            <?endif;?>
            <li><a href="<?=base_url();?>index.php/sigeca/tabs_3">Registro de Notas</a></li>
            <li><a href="#tabs-4">Informes</a></li>
            <li><a href="<?=base_url();?>index.php/sigeca/tabs_7">Datos Usuario</a></li>
        </ul>
        
        <div id="tabs-4">
            <div id="accordionINF">
                <h3><a href="#">Informe Notas Parciales</a></h3>
                <div>
                    <div class="divEstilo1">
                        <table>
                            <tr>
                                <th>Seleccione Curso</th>
                                <th>Seleccione Alumnos</th>
                            </tr>
                            <tr>
                                <td>
                                    <select style="width:200px">
                                        <option selected>Todos</option>
                                        <option>Todos Cursos Básicos</option>
                                        <option>1º Básico A</option>
                                        <option>1º Básico B</option>
                                        <option>1º Básico C</option>
                                        <option>2º Básico A</option>
                                        <option>2º Básico B</option>
                                        <option>3º Básico A</option>
                                        <option>3º Básico B</option>
                                        <option>4º Básico A</option>
                                        <option>4º Básico B</option>
                                        <option>5º Básico A</option>
                                        <option>5º Básico B</option>
                                        <option>6º Básico A</option>
                                        <option>6º Básico B</option>
                                        <option>7º Básico A</option>
                                        <option>8º Básico A</option>
                                        <option>Todos Cursos Superiores</option>
                                        <option>1º Medio A</option>
                                        <option>2º Medio A</option>
                                        <option>3º Medio A</option>
                                        <option>4º Medio A</option>
                                    </select>
                                </td>
                                <td>
                                    <select style="width:200px">
                                        <option>Alumno 1</option>
                                        <option>Alumno 2</option>
                                        <option>Alumno 3</option>
                                        <option>Alumno 4</option>
                                        <option>...</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <button id="generarInformeNotasParciales">Generar</button>
                    </div>
                </div>
                <h3><a href="#">Informe Semestral</a></h3>
                <div>
                    <p>Se desplegará el informe semestral</p>
                    <p>Permitiendole escoger el curso o alumno</p>
                </div>
            </div>
        </div>
    <?endif;?>
    <?if($cargo=='3' || $cargo=='4'):?>
        <ul>
            <li><a href="<?=base_url();?>index.php/sigeca/tabs_8">Asignaturas</a></li>
            <li><a href="<?=base_url();?>index.php/sigeca/tabs_3">Registro de Notas</a></li>
            <?if($cargo=='4'):?>
                <li><a href="<?=base_url();?>index.php/sigeca/tabs_Curso">Mis Cursos</a></li>
                <li><a href="#tabs-4">Informes</a></li>
            <?endif;?>
            <li><a href="<?=base_url();?>index.php/sigeca/tabs_7">Datos Usuario</a></li>
        </ul>
        <?if($cargo=='4'):?>
            <div id="tabs-4">
                <div id="accordionINF">
                    <h3><a href="#">Informe Notas Parciales</a></h3>
                    <div>
                        <div class="divEstilo1">
                            <table>
                                <tr>
                                    <th>Seleccione Curso</th>
                                    <th>Seleccione Alumnos</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select style="width:200px">
                                            <option selected>Todos</option>
                                            <option>Todos Cursos Básicos</option>
                                            <option>1º Básico A</option>
                                            <option>1º Básico B</option>
                                            <option>1º Básico C</option>
                                            <option>2º Básico A</option>
                                            <option>2º Básico B</option>
                                            <option>3º Básico A</option>
                                            <option>3º Básico B</option>
                                            <option>4º Básico A</option>
                                            <option>4º Básico B</option>
                                            <option>5º Básico A</option>
                                            <option>5º Básico B</option>
                                            <option>6º Básico A</option>
                                            <option>6º Básico B</option>
                                            <option>7º Básico A</option>
                                            <option>8º Básico A</option>
                                            <option>Todos Cursos Superiores</option>
                                            <option>1º Medio A</option>
                                            <option>2º Medio A</option>
                                            <option>3º Medio A</option>
                                            <option>4º Medio A</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select style="width:200px">
                                            <option>Alumno 1</option>
                                            <option>Alumno 2</option>
                                            <option>Alumno 3</option>
                                            <option>Alumno 4</option>
                                            <option>...</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <button id="generarInformeNotasParciales">Generar</button>
                        </div>
                    </div>
                    <h3><a href="#">Informe Semestral</a></h3>
                    <div>
                        <p>Se desplegará el informe semestral</p>
                        <p>Permitiendole escoger el curso o alumno</p>
                    </div>
                </div>
            </div>
        <?endif;?>
    <?endif;?>
</div>
