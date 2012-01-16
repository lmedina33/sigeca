<input type="hidden" id="curso" value="<?=$curso;?>"></input>
<div id="accordionCursos">
    <h3><a href="#">Eximir Alumno</a></h3>
    <div id="divEximirAlumno">
        <div class="divEstilo1">
            <table>
                <tr>
                    <th>Alumnos</th>
                    <th>Asignaturas</th>
                    <th>Motivo</th>
                </tr>
                <tr>
                    <td>
                        <select id="listadoAlumnos" class="ui-corner-all ancho150">
                            <option selected></option>
                            <?foreach($alumnos as $row):?>
                                <option value="<?=$row->IDALUMNO?>"><?=$row->APELLIDOP.' '.$row->NOMBRES?></option>
                            <?endforeach;?>
                        </select>
                    </td>
                    <td>
                        <select id="asignaturasCurso" class="ui-corner-all ancho150">
                            <option selected></option>
                            <?for($k=0;$k<$cantAsignaturas;$k++):?>
                                <?foreach(${"asignaturasCurso".$k} as $row):?>
                                    <option value="<?=$row->IDASIGNATURA;?>"><?=$row->NOMBREASIGNATURA;?></option>
                                <?endforeach;?>
                            <?endfor;?>
                        </select>
                    </td>
                    <td>
                        <input id="motivo" class="ui-corner-all ancho180"></input>
                    </td>
                    <td>
                        <button id="eximirAlumnos">Eximir</button>
                    </td>
                </tr>
            </table>
            <div id="msjEximir" class="msjError"></div>
            <div id="tablaEximidos">
                <?if($cantEximidos > 0):?>
                    <table id="tablaEximido" class="tabla1">
                        <tr>
                            <th>Alumno</th>
                            <th>Asignatura</th>
                            <th>Motivo</th>
                            <th>Eliminar</th>
                        </tr>
                        <?for($j=0;$j<$cantEximidos;$j++):?>
                            <tr>
                                <td>
                                    <?foreach(${"alumnoEximido".$j} as $row):?>
                                        <input class="ui-corner-all ancho150 desabilitado" disabled value="<?=$row->NOMBRES.' '.$row->APELLIDOP;?>"/>
                                        <input type="hidden" id="eliminarAlumno<?=$j?>" value="<?=$row->IDALUMNO;?>"></input>
                                    <?endforeach;?>
                                </td>
                                <td>
                                    <?foreach(${"asignaturaEximida".$j} as $row):?>
                                        <input class="ui-corner-all ancho150 desabilitado" disabled value="<?=$row->NOMBREASIGNATURA;?>"/>
                                        <input type="hidden" id="eliminarAsignatura<?=$j?>" value="<?=$row->IDASIGNATURA;?>"></input>
                                    <?endforeach;?>
                                </td>
                                <td>
                                    <input class="ui-corner-all ancho150 desabilitado" disabled value="<?=${"motivo".$j};?>"/>
                                </td>
                                <td align="center">
                                    <img onclick="eliminaEximido(<?=$j;?>)" alt="Eliminar" src="<?=base_url()?>images/cancel.png"></img>
                                </td>
                            </tr>
                        <?endfor;?>
                    </table> 
                <?endif;?>
            </div>
        </div>
    </div>
    <?if($electivo == 'si'):?>
        <h3><a href="#">Asignar Electivos</a></h3>
        <div id="divAsignarElectivos">
            <div class="divEstilo1">
                <table>
                    <tr>
                        <th>Alumnos</th>
                        <th>Electivos</th>
                    </tr>
                    <tr>
                        <td>
                            <select id="alumnosSinElectivo" class="ui-corner-all ancho150">
                                <option selected></option>
                                <?foreach($alumnosSinElectivos as $row):?>
                                    <option value="<?=$row->IDALUMNO?>"><?=$row->APELLIDOP.' '.$row->NOMBRES?></option>
                                <?endforeach;?>
                            </select>
                        </td>
                        <td>
                            <select id="asignaturasElectivas" class="ui-corner-all ancho150">
                                <option selected></option>
                                <?foreach($asignaturasElectivas as $row):?>
                                    <option value="<?=$row->IDASIGNATURA;?>"><?=$row->NOMBREASIGNATURA;?></option>
                                <?endforeach;?>
                            </select>
                        </td>
                        <td>
                            <button id="asignarElectivo">Asignar</button>
                        </td>
                    </tr>
                </table>
                <div id="msjElectivo" class="msjError"></div>
                <?if($cantElectivosAsignados > 0):?>
                    <table id="tablaElectivos" class="tabla1">
                        <tr>
                            <th>Alumno</th>
                            <th>Modificar</th>
                            <th>Eliminar</th>
                        </tr>
                        <?$j=0;foreach($alumnosConElectivos as $row):?>
                            <tr>
                                <td>
                                    <input class="ui-corner-all ancho150 desabilitado" disabled value="<?=$row->NOMBRES.' '.$row->APELLIDOP;?>"/>
                                    <input type="hidden" id="alumnosConElectivo<?=$j;?>" value="<?=$row->IDALUMNO;?>"></input>
                                </td>
                                <td>
                                    <select id="asignaturasElectivas<?=$j;?>" class="ui-corner-all ancho150" onchange="modificarElectivo(<?=$j;?>)">
                                        <?foreach($asignaturasElectivas as $row1):?>
                                            <?foreach(${"asignaturaAsignada".$j} as $row2):?>
                                                <?if($row1->IDASIGNATURA == $row2->IDASIGNATURA):?>
                                                    <option value="<?=$row2->IDASIGNATURA;?>" selected><?=$row2->NOMBREASIGNATURA;?></option>
                                                <?else:?>
                                                    <option value="<?=$row1->IDASIGNATURA;?>"><?=$row1->NOMBREASIGNATURA;?></option>
                                                <?endif;?>
                                            <?endforeach;?>
                                        <?endforeach;?>
                                    </select>
                                </td>                            
                                <td align="center">
                                    <img onclick="eliminaElectivo(<?=$j;?>)" alt="Eliminar" src="<?=base_url()?>images/cancel.png"></img>
                                </td>
                            </tr>
                        <?$j++;endforeach;?>
                    </table> 
                <?endif;?>
            </div>
        </div>
    <?endif;?>
    <h3><a href="#">Informe de Personalidad</a></h3>
    <div id="divDatosEditarAsignatura">
        <div class="divEstilo1">

        </div>
    </div>
</div>
<script>
    $("#accordionCursos").accordion({collapsible:true});
    $("#eximirAlumnos").button().click(function(){eximirAlumnos()});
    $("#divEximirAlumno").css('height','auto');
    $("#asignarElectivo").button().click(function(){asignarElectivo()});
    $("#divAsignarElectivos").css('height','auto');
    
</script>