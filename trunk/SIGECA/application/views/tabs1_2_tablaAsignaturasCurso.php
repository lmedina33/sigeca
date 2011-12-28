<h3 align="center" class="h3Modificados">
    Asignaturas por Curso
</h3>
<table class="tabla1">
    <?if($indice2 > 0):?>
    <?foreach($nombreCurso as $row):?>
        <tr>
            <th><?=$row->NOMBRE.' '.$row->LETRA;?><input type="hidden" id="idCursoSeleccionado" value="<?=$row->IDCURSO;?>"></input></th>
            <th><label>Eliminar</label></th>
        </tr>
    <?endforeach;?>
    <?endif;?>
    <?for($i=0;$i<$indice2;$i++):?>
        <?foreach(${"asignaturasAsignadas".$i} as $row):?>
        <tr>
            <td><label><?=$row->NOMBREASIGNATURA;?></label></td>
            <td align="center"><img src="<?=base_url()?>images/cancel.png" src="Eliminar" name="<?=$row->IDASIGNATURA;?>" onclick="eliminaAsignaturaCurso(this.name);"></img></td>
        </tr>
        <?endforeach;?>
    <?endfor;?>
</table>
<script>
function eliminaAsignaturaCurso(idasignatura)
{
    $.post(base_url+"sigeca/eliminarAsignaturaCurso",
        {
            idasignatura:idasignatura,
            ano:$("#seleccionAnoAcademico").val(),
            curso:$("#idCursoSeleccionado").val()
        },function ()
        {
            $.ajax({
                url:base_url+"sigeca/tabs1_2_asignaturas",
                data:{ano:$("#seleccionAnoAcademico").val(),curso:$("#cursosNoAsignados").val()},
                type:"POST",
                cache:false,
                success:function(htmlresponse,data){
                    $("#asignaturasDisponibles").html(htmlresponse,data);
                }
            });
            $.ajax({
                url:base_url+"sigeca/tabs1_2_tablaAsignaturasCurso",
                data:{ano:$("#seleccionAnoAcademico").val(),curso:$("#cursosNoAsignados").val()},
                type:"POST",
                cache:false,
                success:function(htmlresponse,data){
                    $("#tablaPlanificacionCurso").html(htmlresponse,data);
                }
            });
        }
    );
        
}
</script>
