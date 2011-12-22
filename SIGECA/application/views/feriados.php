<h3 class="h3Modificados">Feriados</h3>
<input class="ui-corner-all" type="text" id="feriadosCal" size="15"/>
<button id="guardarFeriado">Guardar</button>
<div>
    <?if($cantFeriados > 0):?>
    <table class="tabla" align="center">
        <tr><th>Fecha</th>
        <th>Eliminar</th></tr>
        <?foreach($datosFeriados as $row):?>
        <tr>
            <td><input value="<?=$row->FECHAS;?>" size="12" disabled></input></td>
            <td><img src="<?=base_url()?>images/cancel.png" src="Eliminar" name="<?=$row->IDFERIADOS;?>" onclick="eliminaFeriados(this.name);"></img></td>
        </tr>
        <?endforeach;?>
    </table>
    <?endif;?>
</div>
<script>
    $("#guardarFeriado").button().click(function(){
        $.post(base_url+'sigeca/guardarFeriados',{ano:$("#seleccionAnoAcademico").val(),fecha:$("#feriadosCal").val()},
        function (){
            $.ajax({
                url:base_url+'sigeca/cargaFeriados',
                data:{ano:$("#seleccionAnoAcademico").val()},
                cache:false,
                type:"POST",
                success:function(htmlresponse,data){
                    $("#feriados").html(htmlresponse,data);
                    $("#divDatosEditarCursos").css('height','auto');
                }
            });
        });

    });
    $("#feriadosCal").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    });
</script>