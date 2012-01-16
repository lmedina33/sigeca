<table class="tabla1" align="center">
    <tr>
        <th>Seleccionar</th>
        <th>Fecha</th>
    </tr>
    <?$i=0;foreach($fechas as $row):?>
    <tr>
        <td align="center"><input type="checkbox" id="check<?=$i?>" value="<?=$row->FECHA;?>"></input></td>
        <td><label><?=$row->FECHA;?></label></td>
    </tr>
    <? $i++;endforeach; ?>

</table>
<input type="hidden" id="indice" value="<?=$i;?>"></input>
<button id="modificar" align="right">Modificar</button>
<script>
    $("#modificar").button().click(
        function(){
            $("#msjMod1").hide();
            $("#msjMod").hide();
            for(var i=0;i<parseInt($('#indice').val());i++)
            {
                if($('input[id=check'+i+']').is(':checked')){
                    $.post(base_url+'sigeca/modificacionCalificacion',
                    {   
                        idasignatura:$("#asignaturaModCal").val(),
                        idcurso:$("#cursoModCal").val(),
                        idalumno:$("#alumnoModCal").val(),
                        fecha:$("#check"+i).val()
                    },function(){$("#msjMod1").html('<p>Registro habilitado correctamente</p>')});
                }
            }
            $("#msjMod1").show();
        }
    );
</script>