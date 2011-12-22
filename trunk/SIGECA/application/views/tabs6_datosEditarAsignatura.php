<?foreach($result as $row):?>
    <input type="hidden" id="idAsignatura" value="<?=$row->IDASIGNATURA;?>"></input>
    <table>
        <tr>
            <td><label>Nombre</label></td>
            <td><label>:</label></td>
            <td><input type="text" class="ui-corner-all ancho150" id="nombreAsignaturaE" value="<?=$row->NOMBREASIGNATURA;?>"></input></td>
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
            $.post(
                base_url+'sigeca/guardarEditarAsignatura',
                {
                    idasignatura    :   $("#idAsignatura").val(),
                    nombre      :   $("#nombreAsignaturaE").val()
                }
            );
            $("#msjErrorE").html('<label class="msjOK">Cambios realizados correctamente</label>');
            $("#msjErrorE").show();
        }
        else
        {
            $("#msjErrorE").html('<label class="msjError">Debe completar todos los campos</label>');
            $("#msjErrorE").show();
        }
    });
</script>