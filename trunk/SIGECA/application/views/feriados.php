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
<div>
    <?if($cantFeriados > 0):?>
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
    <?endif;?>
</div>
<script>
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
    }).change( function (){validaAnoAcademico("#feriadosCal",'guardarFeriado')});
</script>