<div id="tabs-8">
    <div id="divCursoSeleccionado2" class="divEstilo1" style="float:none; margin-left: 13%;">
        <div class="tipsTabs" style="float:none; width: 50%;">
            <label>Seleccione Curso:</label>
            <select id="cursoSeleccionado2" class="ui-corner-all ancho180">
                <option selected></option>
                <?for($i=0;$i<$cantCurso;$i++):?>
                    <?foreach(${"curso".$i} as $row):?>
                        <option value="<?=$row->IDCURSO;?>"><?=$row->NOMBRE.' '.$row->LETRA;?></option>
                    <?endforeach;?>
                <?endfor;?>
            </select>
        </div>
        <div id="tablaConfigAsignaturas2" class="divEstilo1" style="float:none; display:none; width: auto;">
        </div>
    </div>
</div>

<script>
    $("#cursoSeleccionado2").change(function(){
        $.ajax({
            url:base_url+'sigeca/tabs_8CargaConfigAsignaturas',
            data:{curso:$("#cursoSeleccionado2").val()},
            cache:false,
            type:"POST",
            success:function(htmlresponse,data){
                $("#tablaConfigAsignaturas2").html(htmlresponse,data);
                $("#tablaConfigAsignaturas2").show();
                $("#divCursoSeleccionado2").css('height','auto');
            }
        });
    });
</script>