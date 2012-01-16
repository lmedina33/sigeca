<input type="hidden" id="idAsignatura"  value="<?=$idAsignatura;?>"/>
<input type="hidden" id="contadorCalif" value="<?=$cantEvaluaciones;?>"/>
<input type="hidden" id="ponderacion"   value="<?=$ponderacion?>"/>
<input type="hidden" id="ultimaFila"    value="<?=$ultimaFila;?>"/>
<?$i=0;?>
<table>
    <tr>
        <td><label>Nombre</label></td>
        <td>:</td>
        <td><input id="nombreAsignatura"     class="ancho180 ui-corner-all"  value="<?=$nombreAsignatura;?>"    disabled/></td>
        <td><img id="agregarCalificacion" src="<?=base_url();?>images/add.png" alt="Agregar Evaluación" style="height:20px; width: 20px;"></img></td>
    </tr>
</table>
<?if($cantEvaluaciones==0):?>
    <table id="configCalificaciones" class="tabla1" style="display:none;">
        <tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <?if($ponderacion=='si'):?>
                <th>%</th>
            <?endif;?>
            <th>Eliminar</th>
        </tr>
    </table>
<?else:?>
    <label class="msjError">La eliminación será inmediata!</label>
    <table id="configCalificaciones" class="tabla1">
        <tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <?if($ponderacion=='si'):?>
                <th>%</th>
            <?endif;?>
            <th>Eliminar</th>
        </tr>
        <?foreach($evaluaciones as $row):?>
            <tr>
                <td>
                    <input type="text" class="ui-corner-all ui-widget-content ancho100" id="fechaCalif<?=$i;?>" value="<?=$row->FECHA;?>" onchange="validaAnoAcademico1('#fechaCalif'+<?=$i?>)"/>
                </td>
                <td>
                    <select class="ui-corner-all ui-widget-content ancho100" id="selectTipo<?=$i;?>">
                        <option selected><?=$row->TIPOCALIFICACION;?></option>
                        <?if($row->TIPOCALIFICACION == 'Nota Parcial'):?>
                            <option>C/2</option>
                            <option>Complementaria</option>
                        <?endif;?>
                        <?if($row->TIPOCALIFICACION == 'C/2'):?>
                            <option>Nota Parcial</option>
                            <option>Complementaria</option>
                        <?endif;?>
                        <?if($row->TIPOCALIFICACION == 'Complementaria'):?>
                            <option>Nota Parcial</option>
                            <option>C/2</option>
                        <?endif;?>
                    </select>
                </td>
                <?if($ponderacion=='si'):?>
                    <td>
                        <input type="text" size="5" class="ui-corner-all ui-widget-content" id="ponderacionCalif<?=$i;?>" value="<?=$row->PONDERACION;?>" />
                    </td>
                <?endif;?>
                <td align="center">
                    <?if(${"bloqueo".$i} == 'no'):?>
                        <img alt="Eliminar" src="<?=base_url();?>images/cancel.png" id="<?=$i;?>" name="<?=$row->IDCALIFICACION;?>" width="16px" heigth="16px" onclick="eliminaFilaTabla2(this.id,this.name);"></img>
                    <?else:?>
                        <img alt="Eliminar" src="<?=base_url();?>images/lock.png" id="<?=$i;?>" name="<?=$row->IDCALIFICACION;?>" width="16px" heigth="16px"></img>
                    <?endif;?>
                </td>
                <td>
                    <input type="hidden" id="idCalif<?=$i;?>" value="<?=$row->IDCALIFICACION;?>"/>
                </td>
                <?if($ponderacion=='no'):?>
                    <td>
                        <input type="hidden" size="5" class="ui-corner-all ui-widget-content" id="ponderacionCalif<?=$i;?>" value="<?=$row->PONDERACION;?>" />
                    </td>
                <?endif;?>
            </tr>
            <?$i++;?>
        <?endforeach;?>
    </table>
<?endif;?>
<?if($i>0):?>
    <input type="hidden" id="indice" value="<?=$i;?>"/>
<?else:?>
    <input type="hidden" id="indice" value="0"/>
<?endif;?>
<p class="validateTips"></p>
<script>
    var indice = parseInt($("#indice").val());
    for (var i=0;i<indice;i++)
    {
        $("#fechaCalif"+i).datepicker({
            showOn: "button",
            buttonImage: "images/calendar.gif",
            buttonImageOnly: true,
            dateFormat: 'dd-mm-yy'
        });
    }
    $("#agregarCalificacion").click(function(){
        var tabla = document.getElementById('configCalificaciones');
        var ultimaFila = tabla.rows.length;
        var fila = tabla.insertRow(ultimaFila);
        var indice = parseInt($("#contadorCalif").val());
        $("#contadorCalif").val(indice+1);
        $("#ultimaFila").val(ultimaFila);
        var fecha = fila.insertCell(0);
        var col1 = document.createElement('input');
        col1.type = 'text';
        col1.size = 15;
        col1.name = 'fecha'+indice;
        col1.id = 'fechaCalif'+indice;
        col1.disabled=false;
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
            opt.value = myarray[i];
            opt.innerHTML = myarray[i];
            col2.appendChild(opt);
        }
        tipo.appendChild(col2);

        $('#selectTipo'+indice).addClass('ui-widget-content ui-corner-all ancho100');
        if($("#ponderacion").val() == 'si')
        {
            var ponderacion = fila.insertCell(2);
            var col3 = document.createElement('input');
            col3.type = 'text';
            col3.size = 5;
            col3.name = 'ponderacion'+indice;
            col3.id = 'ponderacionCalif'+indice;
            ponderacion.appendChild(col3);

            $('#ponderacionCalif'+indice).addClass('ui-widget-content ui-corner-all');
            
            var elimina = fila.insertCell(3);
            var col4 = document.createElement('img');
            col4.id = indice;
            col4.src = base_url+"../images/cancel.png";
            col4.width=16;
            col4.heigth=16;
            col4.onclick=eliminaFilaTabla;
            elimina.align = 'center';
            elimina.appendChild(col4);

            var idcalif = fila.insertCell(4);
            var col5 = document.createElement('input');
            col5.type='hidden';
            col5.id = 'idCalif'+indice;
            col5.value=indice+1;
            idcalif.appendChild(col5);
        }
        else
        {
            var elimina = fila.insertCell(2);
            var col4 = document.createElement('img');
            col4.id = indice;
            col4.src = base_url+"../images/cancel.png";
            col4.width=16;
            col4.heigth=16;
            col4.onclick=eliminaFilaTabla;
            elimina.align = 'center';
            elimina.appendChild(col4);

            var idcalif = fila.insertCell(3);
            var col5 = document.createElement('input');
            col5.type='hidden';
            col5.id = 'idCalif'+indice;
            col5.value=indice+1;
            idcalif.appendChild(col5);
            
            var ponderacion = fila.insertCell(4);
            var col3 = document.createElement('input');
            col3.type = 'hidden';
            col3.name = 'ponderacion'+indice;
            col3.id = 'ponderacionCalif'+indice;
            col3.value=100/ultimaFila;
            ponderacion.appendChild(col3);
            
        }
        
        
        $("#configCalificaciones").show();
    });
</script>