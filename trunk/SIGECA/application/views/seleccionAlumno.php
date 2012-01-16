<select class="ui-corner-all ancho150" id="alumnoModCal">
    <option selected></option>
    <?foreach($alumnos as $row):?>
        <option value="<?=$row->IDALUMNO;?>"><?=$row->APELLIDOP.' '.$row->APELLIDOM.' '.$row->NOMBRES;?></option>
    <?endforeach;?>
    <option value="Todos">Todos</option>
</select>