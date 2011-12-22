<!-- 
    tabs7.php corresponde al tab de la modificación de los datos del usuario
    como lo son el nombre y la contraseña. Es llamada desde el controlador en la funcion tabs_7.
    Las funcionalidades de los botones y validaciones se encuentrar en el script en la parte 
    inferior de este archivo.
-->
<div id="tabs-7">
    <h3 align="center">Modificar Datos Personales</h3>
    <div class="divEstilo1" style="float:none; margin-left: 13%;">
        <div id="tipsTabs7" class="tipsTabs"><p>Para editar su contraseña debe escribir la anterior!</p></div>
        <?foreach($result as $row):?>
            <input type="hidden" id="idUsuario" value="<?=$row->IDUSUARIO;?>"></input>
            <input type="hidden" id="cambio" value='0'></input>
            <table id="tableUsuario">
                <tr>
                    <td><label>Nombre</label></td>
                    <td><label>:</label></td>
                    <td><input class="ancho180 ui-corner-all" type="text" id="nombreUsuario1" value="<?=$row->NOMBRE;?>"></input></td>
                </tr>
                <tr id="trPassActual">
                    <td><label>Contraseña Actual</label></td>
                    <td><label>:</label></td>
                    <td><input class="ancho180 ui-corner-all" type="password" id="passActual"></input></td>
                </tr>
            </table>
            <table id="tablePass" style="display:none;">
                <tr>
                    <td><label>Nombre</label></td>
                    <td><label>:</label></td>
                    <td><input class="ancho180 ui-corner-all" type="text" id="nombreUsuario2"></input></td>
                </tr>
                <tr>
                    <td><label>Nueva Contraseña</label></td>
                    <td><label>:</label></td>
                    <td><input class="ancho180 ui-corner-all" type="password" id="passNueva1"></input></td>
                </tr>
                <tr>
                    <td><label>Repita Nueva Contraseña</label></td>
                    <td><label>:</label></td>
                    <td><input class="ancho180 ui-corner-all" type="password" id="passNueva2"></input></td>
                </tr>
            </table>
        <?endforeach;?>
        <table>
            <tr>
                <td><button id="editarDatosUsuario">Guardar Cambios</button></td>
                <td><div id="msjError"></div></td>
            </tr>
        </table>
    </div>
</div>
<script>
    $('#msjError').hide();
    $("#editarDatosUsuario").button().click(
        function(){
            if( ($('#passNueva1').val() != $('#passNueva2').val() || $('#passNueva1').val() == '') && ($('#cambio').val() == '1') )
            {//si es que hay cambio y no coinciden las contraseñas nuevas o están vacias... ERROR
                $('#msjError').html('<p class="msjError">Error en nueva contraseña ingresada</p>');
                $('#msjError').show();
            }
            else
            {//no hay cambios de contrasenia... solo guardo el nombre de usuario
                $.post(base_url+'sigeca/actualizarNombreUsuario',
                    {idUsuario: $('#idUsuario').val(),nombreUsuario:$('#nombreUsuario1').val()}
                );
                $('#msjError').html('<p class="msjOK">Nombre de Usuario actualizado corréctamente</p>');
                $('#msjError').show();
            }
            if( ($('#passNueva1').val() == $('#passNueva2').val() && $('#passNueva1').val() != '') && ($('#cambio').val() == '1') )
            {//Se hizo cambio y está todo ok
                $.post(base_url+'sigeca/actualizarNombreUsuarioyContrasena',
                    {idUsuario: $('#idUsuario').val(),nombreUsuario:$('#nombreUsuario1').val(),contrasena:$('#passNueva2').val()}
                );
                $('#msjError').html('<p class="msjOK">Nombre de Usuario y contraseña actualizada corréctamente</p>');
                $('#msjError').show();
            }
        }
    );
    $("#editarDatosUsuario").css('margin-top','10px');
    $("#passActual").blur(function(){
        $('#msjError').hide();
        var id      = $("#idUsuario").val();
        var pass    = $("#passActual").val();
        $.post(base_url+'sigeca/validaContasena',
            {idusuario:id,contrasena:pass},
            function(data){
                if(data.msj=='ok'){
                    $('#tableUsuario').hide();
                    $('#tablePass').show();
                    $('#nombreUsuario2').val($('#nombreUsuario1').val());
                    $('#cambio').val('1');
                    $('#tipsTabs7').hide();
                }
                else
                {
                    $('#msjError').html('<p class="msjError">'+data.msj+'</p>');
                    $('#msjError').show();
                }
            },'json');
    });
</script>