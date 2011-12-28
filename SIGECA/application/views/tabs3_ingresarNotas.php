<label class="msjError">Ingresar nota sin punto ni coma, Ejemplo: 65</label>
<table class="ui-widget-content ui-corner-all" id="tabla1">
    <tr>
        <th>Nº</th>
        <th>A. Paterno</th>
        <th>A. Materno</th>
        <th>Nombres</th>
        <?foreach($datosCalificacion as $row):?>
            <?if($verPonde == 'si'):?>
                <th><?=$row->TIPOCALIFICACION;?><br><?=$row->PONDERACION;?>%<br><?=$row->FECHA;?></th>
            <?else:?>
                <th><?=$row->TIPOCALIFICACION;?><br><?=$row->FECHA;?></th>  
            <?endif;?>
        <?endforeach;?>
        <th>Promedios</th>
    </tr>
    
    <?$i=1;foreach($alumnos as $row):?>
    <tr>
        <td><label><?=$i;?><input id="idalumno<?=$i;?>" type="hidden" value="<?=$row->IDALUMNO;?>" /></label></td>
        <td><label><?=$row->APELLIDOP;?></label></td>
        <td><label><?=$row->APELLIDOM;?></label></td>
        <td><label><?=$row->NOMBRES;?></label></td>
        <?$j=$i;$k=0;$l=0;foreach($datosCalificacion as $row1):?>
              <?if(${"bloqueo".$k} == 'si'):?>
                <td align="center">
                    <?if(${'cantNota'.$i.$k} == '1'):?>
                        <?foreach(${"nota".$i.$k} as $row2):?>
                            <?if($row2->BLOQUEO =='si'):?>
                                <input value="<?=$row2->NOTAS;?>" disabled class="ui-corner-all" type="text" size="3" tabindex="<?=$j;?>"/>
                            <?else:?>
                                <input id="<?=$j;?>" value="<?=$row2->NOTAS;?>" class="ui-corner-all" type="text" size="3" tabindex="<?=$j;?>">
                            <?endif;?>
                        <?endforeach;?>
                    <?else:?>
                        <input disabled class="ui-corner-all" type="text" size="3" tabindex="<?=$j;?>"/>
                    <?endif;?>
                </td>
            <?else:?>
                <td align="center">
                    <?if(${'cantNota'.$i.$k} == '1'):?>
                        <?foreach(${"nota".$i.$k} as $row2):?>
                            <?if($row2->BLOQUEO =='si'):?>
                                <input value="<?=$row2->NOTAS;?>" disabled id="<?=$j;?>" class="ui-corner-all" type="text" size="3" tabindex="<?=$j;?>"/>
                            <?else:?>
                                <input id="<?=$j;?>" value="<?=$row2->NOTAS;?>" class="ui-corner-all" type="text" size="3" tabindex="<?=$j;?>">
                            <?endif;?>
                        <?endforeach;?>
                    <?else:?>
                        <input id="<?=$j;?>" class="ui-corner-all" type="text" size="3" tabindex="<?=$j;?>"/>
                    <?endif;?>
                    <?if($i==1):?>
                        <input id="calificacion<?=$l;?>" type="hidden" size="2" value="<?=$row1->IDCALIFICACION;?>" />
                    <?$l++;endif;?>
                </td>
            <?endif;?>
        <?$j=$largo+$j;$k++;endforeach;?>
        <td>
            <input disabled class="ui-corner-all" type="text" size="3" value='<?=${"promedio".$i};?>'></input>
        </td>
    </tr>
    <?$i++;endforeach;?>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <?$m=0;?>
        <?foreach($datosCalificacion as $row):?>
            <td><input type="checkbox" id="checkbox<?=$m?>">Guardar</input></td>
            <?$m++;?>
        <?endforeach;?>
        <td></td>
    </tr>
</table>
<input type="hidden" id="cantNotas" value="<?=($j-$largo);?>"/>
<input type="hidden" id="cantAlumnos" value="<?=$i-1;?>"/>
<input type="hidden" id="cantPonde" value="<?=$l;?>"/>
<input type="hidden" id="cantCheckBox" value="<?=$m;?>">
<div style="padding-top: 10px;">
    <button id="guardarNotas">Guardar Notas</button>
</div>

<script>
    tb = $('input');
    
    if ($.browser.mozilla) {
        $(tb).keypress(enter2tab);
    } else {
        $(tb).keydown(enter2tab);
    }
    
    function enter2tab(e) { //Validación de las calificaciones, además permite avanzar con tecla Enter
        if (e.keyCode == 13 || e.keyCode == 40) {
            cb = parseInt($(this).attr('tabindex'));
            nota = $(this).val();
            if ((nota.length == 2 && nota<=70 && nota>=10) || nota.length==0  ) {
                $(':input[tabindex=\'' + (cb + 1) + '\']').focus();
                $(':input[tabindex=\'' + (cb + 1) + '\']').select();
                e.preventDefault();
                return false;
            }
            else{
                if(nota>70 || nota <10)
                    alert('La calificacion solo puede estar entre [10 - 70]');
                else
                    alert("Debe respetar el formato, dos cifras sin separación. Por Ejemplo: 70");
            }           
        }
        if(e.keyCode==38){
            cb = parseInt($(this).attr('tabindex'));
            $(':input[tabindex=\'' + (cb - 1) + '\']').focus();
            $(':input[tabindex=\'' + (cb - 1) + '\']').select();
            e.preventDefault();
            return false;
        }
    }
    
    $("#guardarNotas").button().click(function(){
        var i=1,j=1,k=0,m=0,kini=0,kfin=0;
        for(m=0;m<parseInt($("#cantCheckBox").val());m++)
        {
            if($('input[id=checkbox'+m+']').is(':checked'))
            {
                kini=parseInt($("#cantAlumnos").val())*m +1;
                kfin=parseInt($("#cantAlumnos").val())*(m+1);
                for(i=kini;i<=kfin;i++)
                {
                    if($("#"+i).val() == undefined)
                        $("#"+i).val('');
                    //alert('Alumno '+$("#idalumno"+j).val()+'  Nota '+$("#"+i).val());
                    $.post(
                        base_url+"sigeca/almacenarCalificaciones",
                        {
                            idAlumno:$("#idalumno"+j).val(),
                            Nota: $("#"+i).val(),
                            idCalif:$("#calificacion"+m).val(),
                            idCurso: $("#cursos").val(),
                            idAsignatura: $("#asignaturas").val()
                        }
                    );
                    j++;
                    if(j==(parseInt($("#cantAlumnos").val()))+1){
                        j=1;
                        k++;
                    }
                }
                $("#tablaIngresarNota").hide('fast');
                $.ajax({
                    url:base_url+'sigeca/cargaListadoAlumnos',
                    data:{curso:$("#cursos").val(),asignatura:$("#asignaturas").val()},
                    type:"POST",
                    cache:false,
                    success:function(htmlresponse,data){
                        $("#tablaIngresarNota").html(htmlresponse,data);
                        $("#tablaIngresarNota").show('fast');
                        $("#tabs-3_notas").css('width','auto');
                        $("#tabs-3_notas").css('margin-left','0px');
                    }
                });
            }
        }
    });
</script>
