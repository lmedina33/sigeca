<?foreach($result as $row):?>
    <table>
        <tr>
            <th>Rut</th>
            <th>Nombres</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
        </tr>
        <tr>
            <td><input class="ui-corner-all" type="text" name="rut" id="rutE" disabled maxlength="8" size="15" value="<?=$row->IDPROFESOR;?>"/>&ensp;-&ensp;<input class="ui-corner-all" type="text" name="digito" id="digito" maxlength="1" size="1" disabled value="<?=$digito;?>"/></td>
            <td><input class="ui-corner-all" type="text" name="nombres" id="nombresE" value="<?=$row->NOMBRES;?>"/></td>
            <td><input class="ui-corner-all" type="text" name="aPaterno" id="aPaternoE" value="<?=$row->APELLIDOP;?>"/></td>
            <td><input class="ui-corner-all" type="text" name="aMaterno" id="aMaternoE" value="<?=$row->APELLIDOM;?>"/></td>
        </tr>
    </table>
    <table>
        <tr>
            <th>Fecha Nacimiento</th>
            <th>Dirección</th>
            <th>Teléfono</th>
        </tr>
        <tr>
            <td><input class="ui-corner-all" type="text" name="fNacimiento" id="fNacimientoE" value="<?=$fNacimiento;?>"/></td>
            <td><input class="ui-corner-all" type="text" name="direccion" id="direccionE" size="42px" value="<?=$row->DIRECCION;?>"/></td>
            <td><input class="ui-corner-all" type="text" name="telefono" id="telefonoE" value="<?=$row->TELEFONO;?>"/></td>
        </tr>
    </table>
    <table>
        <tr>
            <th>Previsión</th>
            <th>AFP</th>
            <th>Título</th>
            <th>Mensión</th>
        </tr>
        <tr>
            <td><input class="ui-corner-all" type="text" name="prevision" id="previsionE" value="<?=$row->PREVISION;?>"/></td>
            <td><input class="ui-corner-all" type="text" name="AFP" id="AFPE" value="<?=$row->AFP;?>"/></td>
            <td><input class="ui-corner-all" type="text" name="titulo" id="tituloE" value="<?=$row->TITULO;?>"/></td>
            <td><input class="ui-corner-all" type="text" name="mension" id="mensionE" size="21" value="<?=$row->MENSION;?>"/></td>
        </tr>
    </table>
    <table>
        <tr>
            <td><button id="editarProfesor">Guardar</button></td>
            <td><td><div id="msjErrorE" style="display:none;"></div></td></td>
        </tr>
    </table>
<?endforeach;?>
<script>
    $("#fNacimientoE").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy' //dd/M/yy
        /*monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr',
                    'May', 'Jun', 'Jul', 'Aug',
                    'Sep', 'Oct', 'Nov', 'Dec'] */
    });
    $("#editarProfesor").button().click(
        function(){
            if($('#nombresE').val() != '' && $('#aPaternoE').val()!= '' && $('#aMaternoE').val() != '' && $('#direccionE').val() != '' && $('#telefonoE').val() != '' && $('#previsionE').val() != '' && $('#AFPE').val() != '' && $('#tituloE').val() != '' && $('#mensionE').val() != '' && $("#fNacimientoE").val() != '')
            {
                    $.post(base_url+'sigeca/guardarEditarProfesor',{
                    rut     :$('#rutE').val(),
                    nombres :$('#nombresE').val(),
                    aPaterno:$('#aPaternoE').val(),
                    aMaterno:$('#aMaternoE').val(),
                    direccion   :$('#direccionE').val(),
                    telefono    :$('#telefonoE').val(),
                    prevision   :$('#previsionE').val(),
                    AFP         :$('#AFPE').val(),
                    titulo      :$('#tituloE').val(),
                    mension     :$('#mensionE').val(),
                    fNacimiento :$("#fNacimientoE").val()
                });
                $('#msjErrorE').html('<label class="msjOK">Profesor modificado correctamente</label>');
                $('#msjErrorE').show();
            }
            else
            {
                $('#msjErrorE').html('<label class="msjError">Debe completar todos los campos</label>');
                $('#msjErrorE').show();
            }
        }
    );
</script>