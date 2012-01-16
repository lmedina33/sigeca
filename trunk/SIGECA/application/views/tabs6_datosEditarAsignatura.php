<?foreach($result as $row):?>
    <input type="hidden" id="idAsignatura" value="<?=$row->IDASIGNATURA;?>"></input>
    <table>
        <tr>
            <td><label>Nombre</label></td>
            <td><label>:</label></td>
            <td><input type="text" class="ui-corner-all ancho150" id="nombreAsignaturaE" value="<?=$row->NOMBREASIGNATURA;?>"></input></td>
        </tr>
        <tr>
            <td>
                <label>Electivo</label>
            </td>
            <td>:</td>
            <?if($row->TIPOASIGNATURA == 0):?>
                <td>
                    <input id="electivoNo" type="radio" name="electivo" value="No" checked></input><label>No</label>
                    &ensp;&ensp;<input type="radio" name="electivo" value="Si"></input><label>Si</label>
                </td>
            <?else:?>
                <td>
                    <input id="electivoNo" type="radio" name="electivo" value="No"></input><label>No</label>
                    &ensp;&ensp;<input type="radio" name="electivo" value="Si" checked></input><label>Si</label>
                </td>
            <?endif;?>
        </tr>
        <tr>
            <td>
                <label>Calificación</label>
            </td>
            <td>:</td>
            <?if($row->TIPOEVALUACION == 0):?>
                <td>
                    <input id="califNota" type="radio" name="calificacion" value="Nota" checked></input><label>Nota</label>
                    <input type="radio" name="calificacion" value="Concepto"></input><label>Concepto</label>
                </td>
            <?else:?>
                <td>
                    <input id="califNota" type="radio" name="calificacion" value="Nota"></input><label>Nota</label>
                    <input type="radio" name="calificacion" value="Concepto" checked></input><label>Concepto</label>
                </td>
            <?endif;?>
        </tr>
    </table>
<?endforeach;?>
<table>
    <tr>
        <td><button id="guardarEditarAsignatura">Guardar</button></td>
        <td><div id="msjErrorE" style="display: none;"></div></td>
    </tr>
</table>
<script>
    $("#guardarEditarAsignatura").button().click(function(){
        if($("#nombreAsignaturaE").val()!='' )
        {
            var electivo = $("input[name='electivo']:checked").val();
            var calificacion = $("input[name='calificacion']:checked").val();
            $.post(
                base_url+'sigeca/guardarEditarAsignatura',
                {
                    idasignatura    :   $("#idAsignatura").val(),
                    nombre      :   $("#nombreAsignaturaE").val(),
                    electivo:electivo,
                    calificacion:calificacion
                },function(htmlresponse, data){
                        $("#divDatosEditarAsignatura").html(htmlresponse,data);
                    }
            );
        }
        else
        {
            $("#msjErrorE").html('<label class="msjError">Debe completar todos los campos</label>');
            $("#msjErrorE").show();
        }
    });
</script>