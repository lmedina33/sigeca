<div class="divEstilo1">
    <table>
        <tr>
            <td><label>Editar Asignatura</label></td>
            <td><label>:</label></td>
            <td>
                <select id="editarAsignatura" class="ancho180 ui-corner-all">
                    <option></option>
                    <?foreach($asignaturas as $row):?>
                        <option value="<?=$row->IDASIGNATURA;?>"><?=$row->NOMBREASIGNATURA;?></option>
                    <?endforeach;?>
                </select>
            </td>
            <td><button id="nuevaAsignatura">Crear Nueva Asignatura</button></td>
        </tr>
    </table>
    <div id="datosEditarAsignatura" style="display:none;">
    </div>
</div>
<script>
    $("#nuevaAsignatura").button().click(function(){
            $("#crearAsignatura").dialog("open");
        });

    $("#editarAsignatura").change(
        function (){
            $.ajax({
                url:base_url+'sigeca/cargarDatosEditarAsignatura',
                type:'POST',
                cache:false,
                data:{idasignatura: $("#editarAsignatura").val()},
                success: function(htmlresponse,data){
                    $('#divDatosEditarAsignatura').css('float','none');
                    $('#divDatosEditarAsignatura').css('height','250px');
                    $("#datosEditarAsignatura").html(htmlresponse,data);
                    $("#datosEditarAsignatura").show();
                }
            });
        }
    );
</script>