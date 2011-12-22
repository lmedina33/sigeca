<div id="tabs-3">
    
    <div class="divEstilo1" id="tabs-3_notas" align="center" style="float:none; margin-left: 13%;">
        <?if($cantCursos>0):?>
            <table>
                <tr>
                    <th>Cursos</th>
                </tr>
                <tr>
                    <td>
                        <select style="width:150px" id="cursos" class="ui-corner-all">
                            <option selected></option>
                            <?for($i=0;$i<$cantCursos;$i++):?>
                                <?foreach(${"cursos".$i} as $row):?>
                                    <option value="<?=$row->IDCURSO;?>"><?=$row->NOMBRE.' '.$row->LETRA;?></option>
                                <?endforeach;?>
                            <?endfor;?>
                        </select>
                    </td>
                </tr>
            </table>
        <?else:?>
        <label class="msjError">No tiene cursos registrados</label>
        <?endif;?>
    </div>
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
</script>
    
