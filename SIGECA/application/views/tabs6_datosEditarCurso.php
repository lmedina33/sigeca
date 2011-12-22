<?foreach($result as $row):?>
    <input type="hidden" id="idCurso" value="<?=$row->IDCURSO;?>"></input>
    <table>
        <tr>
            <td><label>Nombre</label></td>
            <td><label>:</label></td>
            <td><input type="text" class="ui-corner-all ancho150" id="nombreCursoE" value="<?=$row->NOMBRE;?>"></input></td>
        </tr>
        <tr>
            <td><label>Letra</label></td>
            <td><label>:</label></td>
            <td><input type="text" class="ui-corner-all ancho150" id="letraCurosE" value="<?=$row->LETRA;?>"></input></td>
        </tr>
        <tr>
            <td><label>Jornada</label></td>
            <td><label>:</label></td>
            <td><input type="text" class="ui-corner-all ancho150" id="jornadaCursoE" value="<?=$row->JORNADA;?>"></input></td>
        </tr>
        <tr>
            <td><label>Capacidad</label></td>
            <td><label>:</label></td>
            <td><input type="text" class="ui-corner-all ancho150" id="capacidadCursoE" value="<?=$row->CAPACIDAD;?>"></input></td>
        </tr>
        <tr>
            <td><label>Orden</label></td>
            <td><label>:</label></td>
            <td><input type="text" class="ui-corner-all ancho150" id="ordenCursoE" value="<?=$row->ORDEN;?>"></input></td>
        </tr>
    </table>
<?endforeach;?>
<table>
    <tr>
        <td><button id="guardarEditarCurso">Guardar</button></td>
        <td><div id="msjErrorE" style="display: none;"></div></td>
    </tr>
</table>
<script>
    $("#guardarEditarCurso").button().click(function(){
        if($("#nombreCursoE").val()!='' && $('#letraCurosE').val()!='' && $("#jornadaCursoE").val()!='' && $("#capacidadCursoE").val()!='' && $("#ordenCursoE").val()!='' )
        {
            $.post(
                base_url+'sigeca/guardarEditarCursos',
                {
                    idcurso     :   $("#idCurso").val(),
                    nombre      :   $("#nombreCursoE").val(),
                    letra       :   $('#letraCurosE').val(),
                    jornada     :   $("#jornadaCursoE").val(),
                    capacidad   :   $("#capacidadCursoE").val(),
                    orden       :   $("#ordenCursoE").val()
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