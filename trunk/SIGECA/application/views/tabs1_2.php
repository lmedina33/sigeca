<table>
    <tr>
        <th>Curso</th>
        <th>Asignatura</th>
    </tr>
    <tr>
        <td>
            <select class="ui-corner-all ancho150" id="cursosNoAsignados">
                <option selected></option>
                
                <?for($i=0;$i<$indice2;$i++):foreach(${'cursos'.$i} as $row):?>
                    <option value="<?=$row->IDCURSO;?>"><?=$row->NOMBRE.' '.$row->LETRA;?></option>
                <?endforeach;endfor;?>
       
            </select>
        </td>
        <td>
            <div id="asignaturasDisponibles">
                <select class="ui-corner-all ancho150" disabled id="asignaturas">
                    <option selected></option>
                    <?foreach($asignaturas as $row):?>
                    <option value="<?=$row->IDASIGNATURA;?>"><?=$row->NOMBREASIGNATURA;?></option>
                    <?endforeach;?>
                </select>
            </div>
        </td>
        <td><button id="agregaAsignaturaCurso">Asignar</button></td>
    </tr>
</table>

<script>
$("#cursosNoAsignados").change(function(){
    actualizaTabla2();
});
$("#agregaAsignaturaCurso").button().click(function(){
    $.post(base_url+'sigeca/asignarAsignaturaCurso',{ano:$("#seleccionAnoAcademico").val(),curso:$("#cursosNoAsignados").val(),asignatura:$("#asignaturas").val()},
    function(){
        actualizaTabla2();
    });
});
</script>