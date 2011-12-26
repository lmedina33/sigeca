<?foreach($nombreCurso as $row):?>
    <table>
        <?if($profesorGuiaCant>0):?>
        <?foreach($profesorGuia as $row1):?>
            <tr><td><label>Profesor Guia </label></td><td><label>:</label></td><td><label><?=$row1->NOMBRES.' '.$row1->APELLIDOP.' '.$row1->APELLIDOM;?></label></td></tr>
        <?endforeach;?>
        <?else:?>
            <tr><td><label>Profesor Guia </label></td><td><label>:</label></td><td><label class="msjError">No se ha asignado!</label></td></tr>
        <?endif;?>
        <tr><td><label>Capacidad </label></td><td><label>:</label></td><td><label><?=$row->CAPACIDAD;?></label></td></tr>
    </table>
<?endforeach;?>

<table class="tabla">
    <?if($indice2 > 0):?>
    <?foreach($nombreCurso as $row):?>
        <tr>
            <th><?=$row->NOMBRE.' '.$row->LETRA;?><input type="hidden" id="idCursoSeleccionado" value="<?=$row->IDCURSO;?>"><input type="hidden" id="ordenCurso" value="<?=$row->ORDEN;?>"/></input></th>
            <th><label>Profesor Asignado</label></th>
            <th><label>Cambiar Profesor</label></th>
            <th><label>Eliminar</label></th>
            <th><label>Evaluaciones</label></th>
        </tr>
    <?endforeach;?>
    <?endif;?>
    <?for($i=0;$i<$indice2;$i++):?>
        <?foreach(${"asignaturasAsignadas".$i} as $row):?>
        <tr>
            <td><label><?=$row->NOMBREASIGNATURA;?></label><input type="hidden" id="idasignatura<?=$i;?>" value="<?=$row->IDASIGNATURA;?>"/><input type="hidden" id="nombreasignatura<?=$i;?>" value="<?=$row->NOMBREASIGNATURA;?>"/></td>
            <?if(${"profesorAsignadoCant".$i} == '1'):?>
                <?foreach(${"profesorAsignado".$i} as $row1):?>
                    <td><label><?=$row1->NOMBRES.' '.$row1->APELLIDOP.' '.$row1->APELLIDOM;?></label></td>
                <?endforeach;?>
            <?else:?>
                    <td><label></label></td>
            <?endif;?>
            <td><select id="asignarProfesor<?=$i;?>" name="<?=$row->IDASIGNATURA;?>" class="ui-corner-all ancho180" onchange="actualizarProfesorCursoAsignatura(this.name,this.value)">
                    <option selected></option>
                    <?foreach($profesores as $row2):?>
                    <option value="<?=$row2->IDPROFESOR;?>"><?=$row2->NOMBRES.' '.$row2->APELLIDOP.' '.$row2->APELLIDOM;?></option>
                    <?endforeach;?>
                </select></td>
            <td><img src="<?=base_url()?>images/cancel.png" src="Eliminar" id="cancel<?=$i;?>" value="<?=$row->IDASIGNATURA;?>" name="<?=$row->IDASIGNATURA;?>" onclick="actualizarProfesorCursoAsignatura(this.name,'11111111');"></img></td>
            <td>
                <?if(${"configurada".$i} == 'si'):?>
                    <button id="configEvaluaciones" onclick="elClick(<?=$i;?>)" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span style="color:green;" class="ui-button-text">Configurar</span></button>
                <?else:?>
                    <button id="configEvaluaciones" onclick="elClick(<?=$i;?>)" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span style="color:red;"  class="ui-button-text">Configurar</span></button>
                <?endif;?>
        </tr>
        <?endforeach;?>
    <?endfor;?>
</table>
<div id="configAsignatura2" title="Configurar Asignatura">
</div>
<script>
    function actualizarProfesorCursoAsignatura(asignatura,idprofesor)
    {
        $.post(base_url+'sigeca/actualizaProfesorCursoAsignatura',{
                ano     :$("#seleccionAnoAcademico").val(),
                curso   :$("#idCursoSeleccionado").val(),
                profesor:idprofesor,
                asignatura: asignatura
            },function(){
                $.ajax({
                    url:base_url+'sigeca/cargaConfigAsignaturas',
                    data:{ano:$("#seleccionAnoAcademico").val(),curso:$("#cursoSeleccionado").val()},
                    cache:false,
                    type:"POST",
                    success:function(htmlresponse,data){
                        $("#tablaConfigAsignaturas").html(htmlresponse,data);
                        $("#tablaConfigAsignaturas").show();
                    }
            });
        });
    }
    function elClick(indice2)
    {
        $("#configAsignatura2").dialog("open");
        $.post(base_url+'sigeca/cargaConfigurarAsignatura',
            {ordenCurso:$("#ordenCurso").val(),nombreAsignatura:$("#nombreasignatura"+indice2).val(),idAsignatura:$("#idasignatura"+indice2).val()},
            function (htmlresponse,data)
            {
                $("#configAsignatura2").html(htmlresponse,data);
            }
        );
    }
    function updateTips( t) {
        $( ".validateTips" )
                .text( t )
                .addClass( "ui-state-highlight validateTips2" )
                .css('display','block');
        setTimeout(function() {
                $( ".validateTips" ).removeClass( "ui-state-highlight", 1500 );
            }, 500 );
    }
    $("#configAsignatura2").dialog({
        autoOpen: false,
        height: 300,
        width: 400,
        modal: true,
        resizable: false,
        closeOnEscape:false,
        open:function(event, ui) { 
                //hide close button.
                $(this).parent().children().children('.ui-dialog-titlebar-close').hide();
                },
        buttons: {'Guardar':function(){
                    var bValid = true,suma=0;
                    var  valor = parseInt($('#contadorCalif').val());
                    var cant = parseInt($("#ultimaFila").val());
                    for (var i=0;i<valor;i++)
                    {
                        if($("#fechaCalif"+i).val() != undefined)
                        {
                            var fecha = $("#fechaCalif"+i);
                            fecha.removeClass("ui-state-error");
                            bValid = bValid && checkLength( fecha, "Fecha", 8, 10 );
                        }
                        if($("#ponderacionCalif"+i).val() != undefined && $("#ponderacion").val() == 'si')
                        {
                            var ponderacion = $("#ponderacionCalif"+i);
                            ponderacion.removeClass("ui-state-error");
                            bValid = bValid && checkLength( ponderacion, "Ponderacion", 1, 3 );
                        }
                    }
                    if($("#ponderacion").val() == 'si')
                    {
                        for(var i=0;i<valor;i++)
                            if($("#ponderacionCalif"+i).val()!=undefined && $("#ponderacionCalif"+i).val()!= '' )
                                suma = suma + parseInt($("#ponderacionCalif"+i).val());
                        if (suma != 100){
                            bValid = false;
                            updateTips('La ponderación está en '+suma+'%');   
                        }
                    }
                    if(bValid)
                    {   
                        for(var i=0;i<valor;i++)
                        {
                            if($("#fechaCalif"+i).val() != undefined && $("#ponderacionCalif"+i).val()!=undefined && $("#idCalif"+i).val() != undefined )
                            {
                                var ponde =0;
                                if($("#ponderacion").val() == 'si')
                                    ponde = $("#ponderacionCalif"+i).val();
                                else
                                    ponde = 100/cant;
                                $.post(base_url+'sigeca/guardaFechaCalificacion',{
                                    idasignatura:$("#idAsignatura").val(),
                                    idcalificacion:$("#idCalif"+i).val(),
                                    fecha:$("#fechaCalif"+i).val(),
                                    ponde:ponde,
                                    tipo:$("#selectTipo"+i).val()
                                });
                            }
                        }
                        $( this ).dialog( "close" );
                    }
                }/*,
                  'Cancelar':function(){
                      $( this ).dialog( "close" );
                  }*/
        }/*,
        close: function() {
            $( ".validateTips" ).val("").removeClass("validateTips2");
            $( ".validateTips" ).css('display','none');
        }*/
    });
</script>
