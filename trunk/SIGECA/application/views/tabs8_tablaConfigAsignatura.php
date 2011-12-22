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
                <th><label>Evaluaciones</label></th>
            </tr>
        <?endforeach;?>
        <?for($i=0;$i<$indice2;$i++):?>
            <?foreach(${"asignatura".$i} as $row):?>
            <tr>
                <td>
                    <label><?=$row->NOMBREASIGNATURA;?></label>
                    <input type="hidden" id="idasignatura<?=$i;?>" value="<?=$row->IDASIGNATURA;?>"/>
                    <input type="hidden" id="nombreasignatura<?=$i;?>" value="<?=$row->NOMBREASIGNATURA;?>"/>
                </td>
                <td>
                    <?if(${"configurada".$i} == 'si'):?>
                        <button id="configEvaluaciones" onclick="elClick(<?=$i;?>)" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span style="color:green;" class="ui-button-text">Configurar</span></button>
                    <?else:?>
                        <button id="configEvaluaciones" onclick="elClick(<?=$i;?>)" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span style="color:red;"  class="ui-button-text">Configurar</span></button>
                    <?endif;?>
                </td>
            </tr>
            <?endforeach;?>
        <?endfor;?>
    <?endif;?>
</table>
<div id="configAsignatura" title="Configurar Asignatura">
    <input type="hidden" id="idAsignatura"/>
    <input type="hidden" id="contadorCalif" value="1"/>
    <table>
        <tr>
            <td><label>Nombre</label></td>
            <td>:</td>
            <td><input id="nombreAsignatura"     class="ancho180 ui-corner-all"     disabled/></td>
            <td><img id="agregarCalificacion" src="<?=base_url();?>images/add.png" alt="Agregar Evaluación" style="height:20px; width: 20px;"></img></td>
        </tr>
    </table>
    <table id="configCalificaciones" class="tabla1" style="display:none;">
        <tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Eliminar</th>
        </tr>
    </table>
    <p class="validateTips"></p>
</div>
<script>
    $("#agregarCalificacion").click(function(){
        var tabla = document.getElementById('configCalificaciones');
        var ultimaFila = tabla.rows.length;
        var fila = tabla.insertRow(ultimaFila);
        var indice = parseInt($("#contadorCalif").val());
        $("#contadorCalif").val(indice+1);

        var fecha = fila.insertCell(0);
        var col1 = document.createElement('input');
        col1.type = 'text';
        col1.size = 15;
        col1.name = 'fecha'+indice;
        col1.id = 'fechaCalif'+indice;
        fecha.appendChild(col1);

        $('#fechaCalif'+indice).addClass('ui-widget-content ui-corner-all ancho100');
        $('#fechaCalif'+indice).datepicker({
            showOn: "button",
            buttonImage: "images/calendar.gif",
            buttonImageOnly: true,
            dateFormat: 'dd-mm-yy'
        }).change(function (){validaAnoAcademico1("#fechaCalif"+indice)});

        var myarray=new Array(3)
        myarray[0] = "Nota Parcial"
        myarray[1] = "C/2"
        myarray[2] = "Complementaria"
 
        var tipo = fila.insertCell(1);
        var col2 = document.createElement('select');
        col2.name = 'select'+indice;
        col2.id   = 'selectTipo'+indice;
        for (var i=0; i<3; i++) {
            var opt = document.createElement('option');
            opt.value = i;
            opt.innerHTML = myarray[i];
            col2.appendChild(opt);
        }
        tipo.appendChild(col2);

        $('#selectTipo'+indice).addClass('ui-widget-content ui-corner-all ancho150');

        var elimina = fila.insertCell(2);
        var col3 = document.createElement('img');
        col3.id = indice;
        col3.src = base_url+"../images/cancel.png";
        col3.width=16;
        col3.heigth=16;
        col3.onclick=eliminaFilaTabla;
        elimina.align = 'center';
        elimina.appendChild(col3);
        
        $("#configCalificaciones").show();
    });
    //QUEDA PENDIENTE CARGAR EL "CONFIGURARASIGNATURAS" CON LA INFORMACIÓN YA EXISTENDE DE LA ASIGNATURA!!
    function elClick(indice2)
    {
        $("#configAsignatura").dialog("open");
        //$("#configCalifi").html('');
        $("#nombreAsignatura").val($("#nombreasignatura"+indice2).val());
        $("#idAsignatura").val($("#idasignatura"+indice2).val());
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
        width: 530,
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
                                        idasignatura:$("#idAsignatura").val(),
                                        idcalificacion:i+1,
                                        fecha:$("#eval"+i).val(),
                                        ponde:$("#pond"+i).val(),
                                        tipo:$("#tipoCalif"+i).val()
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
                                        idasignatura:$("#idAsignatura").val(),
                                        idcalificacion:i+1,
                                        fecha:$("#eval"+i).val(),
                                        ponde:100/valor,
                                        tipo:$("#tipoCalif"+i).val()
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
</script>
