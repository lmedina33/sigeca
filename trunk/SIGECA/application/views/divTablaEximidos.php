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
                <input class="ui-corner-all ancho150 desabilitado" disabled value="<?=${"motivo".$j};?>"></input>
            </td>
            <td align="center">
                <img onclick="eliminaEximido(<?=$j;?>)" alt="Eliminar" src="<?=base_url()?>images/cancel.png"></img>
            </td>
        </tr>
    <?endfor;?>
</table>