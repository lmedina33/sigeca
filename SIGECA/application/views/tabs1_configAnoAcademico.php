<div id="accordionUTP">
    <h3><a href="#">Planificación Anual</a></h3>
    <div id="divConfigFechas" style="height: auto;">
        <div class="divEstilo1" style="width:57%;">
            <table width="100%">
                <tr>
                    <td><label>Inicio Año Escolar </label></td>
                    <td>
                        <?if($bandera==0):?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker"/>
                        <?else:?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker" value="<?=$fechaInicioA;?>"/>
                        <?endif;?>
                    </td>
                    <td><label>Cierre Año Escolar </label></td>
                    <td>
                        <?if($bandera==0):?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker1"/>
                        <?else:?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker1" value="<?=$fechaFinA;?>"/>
                        <?endif;?>
                    </td>
                </tr>
                <tr>
                    <td><label>Inicio Primer Semestre </label></td>
                    <td>
                        <?if($bandera==0):?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker2"/>
                        <?else:?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker2" value="<?=$fechaInicioPS;?>"/>
                        <?endif;?>
                    </td>
                    <td><label>Cierre Primer Semestre </label></td>
                    <td>
                        <?if($bandera==0):?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker3"/>
                        <?else:?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker3" value="<?=$fechaFinPS;?>"/>
                        <?endif;?>
                    </td>
                </tr>
                <tr>
                    <td><label>Inicio Segundo Semestre </label></td>
                    <td>
                        <?if($bandera==0):?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker4"/>
                        <?else:?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker4" value="<?=$fechaInicioSS;?>"/>
                        <?endif;?>
                    </td>
                    <td><label>Cierre Segundo Semestre </label></td>
                    <td>
                        <?if($bandera==0):?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker5"/>
                        <?else:?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker5" value="<?=$fechaFinSS;?>"/>
                        <?endif;?>
                    </td>
                </tr>
                <tr>
                    <td><label>Cierre Segundo Semestre 4º </label></td>
                    <td>
                        <?if($bandera==0):?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker6"/>
                        <?else:?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker6" value="<?=$fechaFinS4;?>"/>
                        <?endif;?>
                    </td>
                    <td><label>Cierre Año Academico 4º</label></td>
                    <td>
                        <?if($bandera==0):?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker7"/>
                        <?else:?>
                            <input class="ui-corner-all ancho80" type="text" id="datepicker7" value="<?=$fechaFinA4;?>"/>
                        <?endif;?>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td><button id="guardarFechasLimites">Guardar</button></td>
                    <td><div id="msjError" style="display:none;"></div></td>
                </tr>
            </table>
        </div>
        <div class="divEstilo2" id="feriados" style="width:35%;">
            <h3 class="h3Modificados">Feriados</h3>
            <table>
                <tr>
                    <th>Motivo</th>
                    <th>Fecha</th>
                </tr>
                <tr>
                    <td><input class="ui-corner-all ancho100" type="text" id="feriadosMotivo"/></td>
                    <td><input class="ui-corner-all ancho80" type="text" id="feriadosCal"/></td>
                    <td><button id="guardarFeriado">Guardar</button></td>
                </tr>
            </table>
            <?if($cantFeriados>0):?>
            <div>
                <table class="tabla1" align="center">
                    <tr>
                        <th>Motivo</th>
                        <th>Fecha</th>
                        <th>Eliminar</th>
                    </tr>
                    <?foreach($datosFeriados as $row):?>
                    <tr>
                        <td><label><?=$row->MOTIVO;?></label></td>
                        <td><input value="<?=$row->FECHAS;?>" size="12" disabled></input></td>
                        <td align="center"><img src="<?=base_url()?>images/cancel.png" src="Eliminar" name="<?=$row->IDFERIADOS;?>" onclick="eliminaFeriados(this.name);"></img></td>
                    </tr>
                    <?endforeach;?>
                </table>
            </div>
            <?endif;?>
        </div>
    </div>
    <h3><a href="#">Planificación Cursos</a></h3>
    <div id="divTabla">
        <div id="tabs2" class="divEstilo12" style="margin-bottom: -10%;">
            <ul>
                <li><a href="#tabs-1-1" onclick="actualizaTabla()">Profesor Guía</a></li>
                <li><a href="#tabs-1-2" onclick="actualizaTabla2()">Asignatura por Curso </a></li>
            </ul>
            <div id="tabs-1-1" class="divEstilo1">
            </div>
            <div id="tabs-1-2" class="divEstilo1">
            </div>
        </div>
        <div id="tablaPlanificacionCurso" class="divEstilo2"></div>
        <div id="divOculto" style="height: 150px;"></div>
    </div>
    <h3><a href="#">Planificación Asignatura</a></h3>
    <div id="divCursoSeleccionado">
        <div class="tipsTabs" style="float:none; width: 40%;">
            <label>Seleccione Curso:</label>
            <select id="cursoSeleccionado" class="ui-corner-all ancho180">
                <option selected></option>
                <?for($i=0;$i<$cantCursos;$i++):foreach(${"cursos".$i} as $row):?>
                <option value="<?=$row->IDCURSO;?>"><?=$row->NOMBRE.' '.$row->LETRA;?></option>
                <?endforeach;endfor;?>
            </select>
        </div>
        <div id="tablaConfigAsignaturas" class="divEstilo1" style="display:none; width: auto;">
        </div>
    </div>
</div>

<script>
    
    $("#accordionUTP").accordion({collapsible: true});
    
    //Calendarios
    $("#datepicker").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    }).change( function (){validaAnoAcademico("#datepicker",'guardarFechasLimites')});
    $("#datepicker1").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    }).change( function (){validaAnoAcademico("#datepicker1",'guardarFechasLimites')});
    $("#datepicker2").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    }).change( function (){validaAnoAcademico("#datepicker2",'guardarFechasLimites')});
    $("#datepicker3").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    }).change( function (){validaAnoAcademico("#datepicker3",'guardarFechasLimites')});
    $("#datepicker4").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    }).change( function (){validaAnoAcademico("#datepicker4",'guardarFechasLimites')});
    $("#datepicker5").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    }).change( function (){validaAnoAcademico("#datepicker5",'guardarFechasLimites')});
    $("#datepicker6").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    }).change( function (){validaAnoAcademico("#datepicker6",'guardarFechasLimites')});
    $("#datepicker7").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    }).change( function (){validaAnoAcademico("#datepicker7",'guardarFechasLimites')});
    $("#feriadosCal").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    }).change( function (){validaAnoAcademico("#feriadosCal",'guardarFeriado')});
    $("#tabs2").tabs();
    $("#guardarFeriado").button().click(function(){
        $.post(base_url+'sigeca/guardarFeriados',{ano:$("#seleccionAnoAcademico").val(),fecha:$("#feriadosCal").val(),motivo:$("#feriadosMotivo").val()},
        function (){
            $.ajax({
                url:base_url+'sigeca/cargaFeriados',
                data:{ano:$("#seleccionAnoAcademico").val()},
                cache:false,
                type:"POST",
                success:function(htmlresponse,data){
                    $("#feriados").html(htmlresponse,data);
                    $("#divConfigFechas").css('height','auto');
                }
            });
        });
    });
    $("#msjError").hide();
    $("#guardarFechasLimites").button().click(function(){
        if($("#datepicker").val() != '' && $("#datepicker1").val() != '' && $("#datepicker2").val() != '' && $("#datepicker3").val() != '' && $("#datepicker4").val() != '' && $("#datepicker5").val() != '' && $("#datepicker6").val()!= '' && $("#datepicker7").val() != '')
        {
            var calendario;
            var error;
            var guardar=0;
            for(var i=0;i<6;i++)
            {
                if(i==0)
                    calendario = "#datepicker";
                else
                    calendario = "#datepicker"+i;
                error = validaAnoAcademico(calendario,'guardarFechasLimites');
                if(error==1)
                {
                    guardar =1;
                }
            }
            if(guardar == 0)
                $.post(base_url+'sigeca/guardarConfiguracionUTP',{
                    ano             :$("#seleccionAnoAcademico").val(),
                    fechaInicioA    :$("#datepicker").val(),
                    fechaFinA       :$("#datepicker1").val(),
                    fechaInicioPS   :$("#datepicker2").val(),
                    fechaFinPS      :$("#datepicker3").val(),
                    fechaInicioSS   :$("#datepicker4").val(),
                    fechaFinSS      :$("#datepicker5").val(),
                    fechaFinS4      :$("#datepicker6").val(),
                    fechaFinA4      :$("#datepicker7").val()
                });
            $("#msjError").html('<label class="msjOK">Fechas almacenadas correctamente</label>')
            $("#msjError").show();
        }
        else
        {
            $("#msjError").html('<label class="msjError">Falta completar fechas!</label>')
            $("#msjError").show();
        }
        
    });
    function eliminaFeriados(idferiado)
    {
        $.post(base_url+'sigeca/eliminaFeriado',{idferiado:idferiado},function(){
            $.ajax({
                url:base_url+'sigeca/cargaFeriados',
                data:{ano:$("#seleccionAnoAcademico").val()},
                cache:false,
                type:"POST",
                success:function(htmlresponse,data){
                    $("#feriados").html(htmlresponse,data);
                }
            });
        });
    }

    $("#cursoSeleccionado").change(function(){
        $.ajax({
            url:base_url+'sigeca/cargaConfigAsignaturas',
            data:{ano:$("#seleccionAnoAcademico").val(),curso:$("#cursoSeleccionado").val()},
            cache:false,
            type:"POST",
            success:function(htmlresponse,data){
                $("#divCursoSeleccionado").css('height','auto');
                $("#tablaConfigAsignaturas").html(htmlresponse,data);
                $("#tablaConfigAsignaturas").show();
            }
        });
    });
</script>