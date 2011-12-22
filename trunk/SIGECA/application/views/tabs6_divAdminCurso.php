<select id="adminCursos" class="ancho150 ui-corner-all">
    <option selected></option>
    <?foreach($cursos as $row):?>
        <option value="<?=$row->IDCURSO;?>"><?=$row->NOMBRE.' '.$row->LETRA;?></option>
    <?endforeach;?>
</select>