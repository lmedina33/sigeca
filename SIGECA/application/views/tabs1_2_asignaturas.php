<select class="ui-corner-all ancho150" id="asignaturas">
    <option selected></option>
    <?for($i=0;$i<$indice3;$i++):?>
    <?foreach(${"asignaturasNoAsignadas".$i} as $row):?>
    <option value="<?=$row->IDASIGNATURA;?>"><?=$row->NOMBREASIGNATURA;?></option>
    <?endforeach;?>
    <?endfor;?>
</select>