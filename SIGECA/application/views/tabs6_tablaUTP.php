<?if($cantCurso>0):?>
<table id="tabla1">
    <?for($i=0;$i<$cantCurso;$i++):?>
        <?if($i==0):?>
            <tr>
                <th>Cursos</th>
                <th>Eliminar</th>
            </tr>
        <?endif;?>
        <?foreach(${"curso".$i} as $row1):?>
            <tr>
                <td>
                    <label><?=$row1->NOMBRE.' '.$row1->LETRA;?></label>
                </td>
                <td align="center">
                    <img id="eliminarCursoUTP<?=$i?>" onclick="eliminarCursoUTP(<?=$row1->IDCURSO;?>);" alt="Eliminar" src="<?=base_url()?>images/cancel.png"></img>
                </td>
            </tr>
        <?endforeach;?>
    <?endfor;?>
</table>
<?endif;?>
<script>
    function eliminarCursoUTP(curso)
    {
        $.post(
            base_url+"sigeca/eliminarCursoUTP",
            {idprofesor:$("#adminProfesores").val(),idcurso:curso},
            function(htmlresponse,data)
            {
                $.post(
                    base_url+"sigeca/tablaUTP",
                    {idprofesor:$("#adminProfesores").val()},
                    function(htmlresponse,data){
                        $("#tablaUTP").html(htmlresponse,data);
                        $("#divConfigurarUTP").css('height','auto');
                    }
                );
                $.post(
                    base_url+"sigeca/selectDivAdminCurso",
                    function(htmlresponse,data){
                        $("#divAdminCursos").html(htmlresponse,data);
                    }
                );
                $("#divConfigurarUTP").css('height','auto');
            }
        );
    }
</script>