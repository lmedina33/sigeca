<div>
    <?if($cantProfesoresAsignados > 0):?>
    <h3 align="center" class="h3Modificados">Profesor Asignado</h3>
    <table class="tabla1">
        <tr>
            <th>Curso</th>
            <th>Profesor</th>
            <th>Eliminar</th>
        </tr>
        <?for($i=0;$i<$cantProfesoresAsignados;$i++):?>
        <tr>
            <?foreach(${"cursosTabla".$i} as $row):?>
            <td><label><?=$row->NOMBRE.' '.$row->LETRA?></label><input type="hidden" id="cursoTabla<?=$i;?>" value="<?=$row->IDCURSO;?>" /></td>
            <?endforeach;?>
            <?foreach(${"profesorGuiaTabla".$i} as $row):?>
            <td><label><?=$row->NOMBRES.' '.$row->APELLIDOP.' '.$row->APELLIDOM;?><input type="hidden" id="profesorTabla<?=$i;?>" value="<?=$row->IDPROFESOR;?>" /></label></td>
            <?endforeach;?>
            <td align="center"><img src="<?=base_url()?>images/cancel.png" src="Eliminar" name="<?=$i;?>" onclick="eliminaProfesorAsignado(this.name);"></img></td>
        </tr>
        <?endfor;?>
    </table>
    <?endif;?>
</div>
