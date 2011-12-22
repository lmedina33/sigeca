<?if($cantidad>0):?>
    <input id="cantidadEval" value="<?=$cantidad;?>" type="hidden"/>
    <table>
        <tr><th>Evaluación</th><th>Fecha</th><?if($ponderacion == 'si'):?><th>Ponderación</th><?endif;?><th>Tipo</th>
        </tr>

        <?for($i=0;$i<$cantidad;$i++):?>
        <tr>
            <td align="center"><?=($i+1)?></td>
            <td><input class="ui-corner-all ancho150" type="text" id="eval<?=$i;?>"/></td>
            <?if($ponderacion == 'si'):?><td align="center"><input type="text" class="ui-corner-all" size="3" id="pond<?=$i;?>"/></td><?endif;?>
            <td>
                <select id="tipoCalif<?=$i;?>" class="ui-corner-all ancho150">
                    <option selected>Nota Parcial</option>
                    <option>C/2</option>
                    <option>Complementaria</option>
                </select>
            </td>
        </tr>
        <?endfor;?>
    </table>
<?endif;?>
<input type="hidden" id="ponderacion" value="<?=$ponderacion;?>" />
<script>
//Calendarios
    $("#eval0").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    }).change(function (){validaAnoAcademico1("#eval0")});
    $("#eval1").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    }).change(function (){validaAnoAcademico1("#eval1")});
    $("#eval2").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    }).change(function (){validaAnoAcademico1("#eval2")});
    $("#eval3").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    }).change(function (){validaAnoAcademico1("#eval3")});
    $("#eval4").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    }).change(function (){validaAnoAcademico1("#eval4")});
    $("#eval5").datepicker({
        showOn: "button",
        buttonImage: "images/calendar.gif",
        buttonImageOnly: true,
        dateFormat: 'dd-mm-yy'
    }).change(function (){validaAnoAcademico1("#eval5")});

</script>