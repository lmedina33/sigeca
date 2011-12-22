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
            <th><?=$row->NOMBRE.' '.$row->LETRA;?><input type="hidden" id="idCursoSeleccionado" value="<?=$row->IDCURSO;?>"></input></th>
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
<div id="configAsignatura" title="Configurar Asignatura">
    <input type="hidden" id="idAsignatura"/>
    <table>
        <tr>
            <td><label>Nombre</label></td>
            <td>:</td>
            <td><input id="nombreAsignatura"     class="ancho180 ui-corner-all"     disabled/></td>
        </tr>
        <tr>
            <td><label>Cant. Evaluaciones</label></td>
            <td>:</td>
            <td><select id="cantEval"      class="ancho180 ui-corner-all"    >
                    <option selected></option>
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                    <option>6</option>
                </select>
        </tr>
    </table>
    <div id="configCalifi">
    </div>
    <p class="validateTips"></p>
</div>
<script>
    //QUEDA PENDIENTE CARGAR EL "CONFIGURARASIGNATURAS" CON LA INFORMACIÓN YA EXISTENDE DE LA ASIGNATURA!!
    function elClick(indice)
    {
        $("#configAsignatura").dialog("open");
        $("#configCalifi").html('');
        $("#nombreAsignatura").val($("#nombreasignatura"+indice).val());
        $("#idAsignatura").val($("#idasignatura"+indice).val());
        
    }
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
     var nombre = $( "#nombreAsignatura" ),
        cantEval = $("#cantEval"),
        allFields = $( [] ).add(nombre).add(cantEval),
        tips = $( ".validateTips" );
        
    $("#configAsignatura").dialog({
        autoOpen: false,
        height: 300,
        width: 400,
        modal: true,
        buttons: {'Guardar':function(){
                    var bValid = true;
                    allFields.removeClass( "ui-state-error" );
                    
                    bValid = bValid && checkLength( cantEval, "Cantidad Evaluaciones", 0, 1 );
                    if(bValid)
                    {
                        var  valor = parseInt($('#cantidadEval').val());
                        if($("#ponderacion").val() == 'si')
                        {
                            for(var i=0;i<valor;i++)
                            {
                                if($("#eval"+i).val() != ''  && $("#pond"+i).val() != ''){
                                    $.post(base_url+'sigeca/guardaFechaCalificacion',{
                                        ano:$("#seleccionAnoAcademico").val(),
                                        idasignatura:$("#idAsignatura").val(),
                                        idcalificacion:i+1,
                                        fecha:$("#eval"+i).val(),
                                        ponde:$("#pond"+i).val()
                                    });
                                }
                            }
                        }
                        else
                        {
                            for(var i=0;i<valor;i++)
                            {
                                if($("#eval"+i).val() != ''){
                                    $.post(base_url+'sigeca/guardaFechaCalificacion2',{
                                        ano:$("#seleccionAnoAcademico").val(),
                                        idasignatura:$("#idAsignatura").val(),
                                        idcalificacion:i+1,
                                        fecha:$("#eval"+i).val(),
                                        ponde:100/valor
                                    });
                                }
                            }
                        }
                        $( this ).dialog( "close" );
                    }
                },
                  'Cancelar':function(){
                      $( this ).dialog( "close" );
                  }
        },
        close: function() {
            nombre.val( "" ).removeClass( "ui-state-error" );
            cantEval.val( "" ).removeClass( "ui-state-error" );
            tips.val("").removeClass("validateTips2");
            tips.css('display','none');
            }
    });
    $("#cantEval").change(
        function ()
        {
            if($("#cantEval")!=''){
            $.ajax({
                url:base_url+'sigeca/cargaCantEvaluaciones',
                data:{cantidad:$("#cantEval").val(),curso:$("#cursoSeleccionado").val()},
                type:"POST",
                cache:false,
                success:function(htmlresponse,data){
                    $("#configCalifi").html(htmlresponse,data);
                }
            });
            }
        }
    );
</script>
