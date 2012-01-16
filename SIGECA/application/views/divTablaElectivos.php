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

<script>
    $("#asignarElectivo").button().click(function(){asignarElectivo()});
    $("#divAsignarElectivos").css('height','auto');

</script>