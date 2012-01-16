<div style="float:left;">
    <table>
    <tr>
        <td><label>Selecione Asignatura:</label></td>
        <td>
            <select class="ui-corner-all ancho150" id="asignaturaModCal">
                <option selected></option>
                <?for($i=0;$i<$cantAsignaturas;$i++):?>
                    <?foreach(${"asignatura".$i} as $row):?>
                        <option value="<?=$row->IDASIGNATURA;?>"><?=$row->NOMBREASIGNATURA;?></option>
                    <?endforeach;?>
                <?endfor;?>
            </select>
        </td>
    </tr>
    <tr>
        <td><label>Selecione Alumno:</label></td>
        <td>
            <select class="ui-corner-all ancho150" id="alumnoModCal">
                <option selected></option>
                <?foreach($alumnos as $row):?>
                    <option value="<?=$row->IDALUMNO;?>"><?=$row->APELLIDOP.' '.$row->APELLIDOM.' '.$row->NOMBRES;?></option>
                <?endforeach;?>
                <option value="Todos">Todos</option>
            </select>
        </td>
    </tr>
    <tr>
        <td></td>
        <td align="right"><button id="buscaCalif">Buscar</button></td>
    </tr>
</table>
</div>
<div style="float: right; display:none; text-align: center;" class="divEstilo12" id="fechasCalificaciones"></div>
<div class="msjError" id="msjMod"></div>
<div class="msjOK" id="msjMod1"></div>
<script>
    $("#buscaCalif").button().click(function(){
        $("#msjMod").hide();
        $("#msjMod1").hide();
        if($("#asignaturaModCal").val()!='' && $("#alumnoModCal").val() != ''){
            $.post(base_url+'sigeca/cargaFechasCalificacion',
                {idasignatura:$("#asignaturaModCal").val(),idcurso:$("#cursoModCal").val()},
                function(htmlresponse,data){
                    $('#fechasCalificaciones').html(htmlresponse,data);
                    $('#fechasCalificaciones').show();
                });
        }
        else{
            $("#msjMod").html('<label>Debe Seleccionar: Curso, Asignatura y Alumno</label>');
            $("#msjMod").show();
        }
    });
</script>