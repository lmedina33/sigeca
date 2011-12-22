<div id="tabs-2">
    <div id="accordionCrarProfesor">
        <h3><a href="#">Registrar Nuevo Profesor</a></h3>
        <div>
        <div class="divEstilo1" style="float:none; margin-left: 13%; height: 200px;">
            <table>
                <tr>
                    <th>Rut (Ej: 11111111-1)</th>
                    <th>Nombres</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                </tr>
                <tr>
                    <td><input class="ui-corner-all" type="text" name="rut" id="rut" maxlength="8" size="15"/>&ensp;-&ensp;<input class="ui-corner-all" type="text" name="digito" id="digito" maxlength="1" size="1"/></td>
                    <td><input class="ui-corner-all" type="text" name="nombres" id="nombres" disabled/></td>
                    <td><input class="ui-corner-all" type="text" name="aPaterno" id="aPaterno" disabled/></td>
                    <td><input class="ui-corner-all" type="text" name="aMaterno" id="aMaterno" disabled/></td>
                </tr>
            </table>
            <table>
                <tr>
                    <th>Fecha Nacimiento</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                </tr>
                <tr>
                    <td><input class="ui-corner-all" type="text" name="fNacimiento" id="fNacimiento" disabled/></td>
                    <td><input class="ui-corner-all" type="text" name="direccion" id="direccion" disabled size="42px"/></td>
                    <td><input class="ui-corner-all" type="text" name="telefono" id="telefono" disabled/></td>
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
                    <td><input class="ui-corner-all" type="text" name="prevision" id="prevision" disabled/></td>
                    <td><input class="ui-corner-all" type="text" name="AFP" id="AFP" disabled/></td>
                    <td><input class="ui-corner-all" type="text" name="titulo" id="titulo" disabled/></td>
                    <td><input class="ui-corner-all" type="text" name="mension" id="mension" size="21" disabled/></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td><button id="guardarNuevoProfesor">Guardar</button></td>
                    <td><td><div id="msjError" style="display:none;"></div></td></td>
                </tr>
            </table>
        </div>
        </div>
        <h3><a href="#">Editar Profesor</a></h3>
        <div>
        <div class="divEstilo1" style="float:none; margin-left: 13%;">
            <?if($cantProfesores <1):?>
                <p class="msjError">No hay profesores almacenados.</p>
            <?else:?>
                <label>Seleccione un profesor: </label>
                <select class="ui-corner-all ancho180" id="selectEditarProfesor">
                    <option selected></option>
                    <?foreach($profesores as $row):?>
                        <option value="<?=$row->IDPROFESOR;?>"><?=$row->NOMBRES.' '.$row->APELLIDOP;?></option>
                    <?endforeach;?>
                </select>
                <div id="divDatosEditarProfesor" style="display:none;"></div>
            <?endif;?>
        </div>
        </div>
    </div>
</div>

<script>
    $('#accordionCrarProfesor').accordion({collapsible: true});

    //Validar RUT!
    $('#digito').blur(function(){
        if($('#rut').val() != '' && $('#digito').val() != ''  ){
            $.post(base_url+'sigeca/validaRut',
                {RUT:$('#rut').val(),Digito:$('#digito').val()},
                function(data){
                    if(data.valida =='true')
                    { //Rut válido... bloqueo el rut y activo el botton
                        $('#msjError').hide();
                        $('#rut')       .attr('disabled',true);
                        $('#digito')    .attr('disabled',true);
                        $('#nombres')   .attr('disabled',false);
                        $('#aPaterno')  .attr('disabled',false);
                        $('#aMaterno')  .attr('disabled',false);
                        $('#direccion') .attr('disabled',false);
                        $('#telefono')  .attr('disabled',false);
                        $('#prevision') .attr('disabled',false);
                        $('#AFP')       .attr('disabled',false);
                        $('#titulo')    .attr('disabled',false);
                        $('#mension')   .attr('disabled',false);
                        $('#fNacimiento')   .attr('disabled',false);
                        $("#guardarNuevoProfesor").button().attr('disabled',false);
                        $("#fNacimiento").datepicker({
                            showOn: "button",
                            buttonImage: "images/calendar.gif",
                            buttonImageOnly: true,
                            dateFormat: 'dd-mm-yy'
                        });
                    }
                    else
                    {
                        if(data.valida == 'existe')
                        {
                            $('#msjError').html('<label class="msjError">Profesor ya registrado</label>');
                            $('#msjError').show();
                        }
                        else{
                            $('#msjError').html('<label class="msjError">Debe ingresar un rut válido</label>');
                            $('#msjError').show();
                        }
                    }                        
                },
                'json');
        }
        else
        {
            $('#msjError').html('<label class="msjError">Debe ingresar un rut válido</label>');
            $('#msjError').show();
        }
    });
    //botones
    $("#guardarNuevoProfesor").button().attr('disabled',true).click(
        function(){
            if($('#rut').val() != '' && $('#digito').val() != '' && $('#nombres').val() != '' && $('#aPaterno').val()!= '' && $('#aMaterno').val() != '' && $('#direccion').val() != '' && $('#telefono').val() != '' && $('#prevision').val() != '' && $('#AFP').val() != '' && $('#titulo').val() != '' && $('#mension').val() != '' && $("#fNacimiento").val() != '')
            {
                $.post(base_url+'sigeca/guardarNuevoProfesor',{
                    rut     :$('#rut').val(),
                    digito  :$('#digito').val(),
                    nombres :$('#nombres').val(),
                    aPaterno:$('#aPaterno').val(),
                    aMaterno:$('#aMaterno').val(),
                    direccion   :$('#direccion').val(),
                    telefono    :$('#telefono').val(),
                    prevision   :$('#prevision').val(),
                    AFP         :$('#AFP').val(),
                    titulo      :$('#titulo').val(),
                    mension     :$('#mension').val(),
                    fNacimiento :$("#fNacimiento").val()
                });
                $('#msjError').html('<label class="msjOK">Profesor ingresado correctamente</label>');
                $('#msjError').show();
            }
            else
            {
                $('#msjError').html('<label class="msjError">Debe completar todos los campos</label>');
                $('#msjError').show();
            }
        }
    );
    $("#selectEditarProfesor").change(
        function()
        {
            $.ajax({
                url     :   base_url+'sigeca/datosEditarProfesor',
                data    :   {idprofesor:$("#selectEditarProfesor").val()},
                cache   :   false,
                type    :   "POST",
                success :   function(htmlresponse,data)
                    {
                        $("#divDatosEditarProfesor").html(htmlresponse,data);
                        $("#divDatosEditarProfesor").show();
                    }
            });
        }
    );
</script>