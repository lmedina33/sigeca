<table>
    <tr>
        <th>Cursos</th>
        <th>Asignaturas</th>
    </tr>
    <tr>
        <td>
            <select style="width:150px" id="cursos" class="ui-corner-all">                
                <?for($i=0;$i<$cantCursos;$i++):?>
                    <?foreach(${"cursos".$i} as $row):?>
                        <?if($row->IDCURSO == $idcurso):?>
                            <option selected value="<?=$row->IDCURSO;?>"><?=$row->NOMBRE.' '.$row->LETRA;?></option>
                        <?else:?>
                            <option value="<?=$row->IDCURSO;?>"><?=$row->NOMBRE.' '.$row->LETRA;?></option>
                        <?endif;?>
                    <?endforeach;?>
                <?endfor;?>
            </select>
        </td>
        <td>
            <select style="width:150px" id="asignaturas" class="ui-corner-all">
                <option selected></option>
                <?for($i=0;$i<$cantAsignaturas;$i++):?>
                    <?foreach(${"asignaturas".$i} as $row):?>
                        <option value="<?=$row->IDASIGNATURA;?>"><?=$row->NOMBREASIGNATURA?></option>
                    <?endforeach;?>
                <?endfor;?>
            </select>
        </td>
        <td><button id="verListadoAlumnos">Ver Listado</button></td>
    </tr>
</table>

<div id="tablaIngresarNota" style="display:none;" align="center">
</div>
<script>
    $("#cursos").change(function(){
        $.ajax({
            url:base_url+'sigeca/tabs_3Asignaturas',
            data:{curso:$("#cursos").val()},
            type:"POST",
            cache:false,
            success:function(htmlresponse,data){
                $("#tabs-3_notas").html(htmlresponse,data);
            }
        });
    });
    $("#verListadoAlumnos").button().click(function(){
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
    });
</script>