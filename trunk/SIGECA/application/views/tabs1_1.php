<div>
    <table>
        <tr>
            <th>Curso</th>
            <th>Profesor Guía</th>
        </tr>
        <tr>
            <td>
                <select class="ui-corner-all ancho150" id="cursosSinProfesor">
                    <option selected></option>
                    <?for($i=0;$i<$indice;$i++):?>
                        <?foreach(${"cursosSinProfesorGuia".$i} as $row):?>
                            <option value="<?=$row->IDCURSO;?>"><?=$row->NOMBRE.' '.$row->LETRA;?></option>
                        <?endforeach;?>
                    <?endfor;?>
                </select>
            </td>
            <td>
                <select class="ui-corner-all ancho150" id="profesoresDisponibles">
                    <option selected></option>
                    <?foreach($profesores as $row):?>
                    <option value="<?=$row->IDPROFESOR;?>"><?=$row->NOMBRES.' '.$row->APELLIDOP.' '.$row->APELLIDOM;?></option>
                    <?endforeach;?>
                </select>
            </td>
            <td><button id="agregaProfesorGuia">Asignar</button></td>
        </tr>
    </table>
    </div>
<script>
    $("#agregaProfesorGuia").button().click(
        function (){
            if($("#profesoresDisponibles").val()!='' && $("#cursosSinProfesor").val()!= '')
            {
                $.post(base_url+'sigeca/guardarProfesorGuia',{idprofesor:$("#profesoresDisponibles").val(),idcurso:$("#cursosSinProfesor").val(),anoacademico:$('#seleccionAnoAcademico').val()},
                    function(){
                        actualizaTabla();
                    }
                );
            }
        }
    );
    function eliminaProfesorAsignado(valor)
    {
        var idprofesor  = $("#profesorTabla"+valor).val();
        var idcurso     = $("#cursoTabla"+valor).val();

        $.post(base_url+'sigeca/eliminaProfesorGuia',{idprofesor:idprofesor,idcurso:idcurso,anoacademico:$('#seleccionAnoAcademico').val()},
            function(){
                actualizaTabla();
            }
        );
    }
</script>