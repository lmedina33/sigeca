<div id="tabs-1">
    <div class="tipsTabs" style="float:none; width: 40%;">
        <label>Seleccione Año Académico:</label>
        <input type="hidden" id="idUTP" value="<?=$idusuario;?>"/>
        <select id="seleccionAnoAcademico" class="ui-corner-all ancho180">
            <option selected></option>
            <?foreach($anios as $row):?>
                <option><?=$row->ANOACADEMICO;?></option>
            <?endforeach;?>
        </select>
    </div>
    <div id="anoAcademicoSeleccionado"></div>
    <br></br>
    <div id="accordionUTP2">
        <h3><a href="#">Cierre de Semestre</a></h3>
        <div>
            <p>Realización del Cierre de Semestre en Curso</p>
            <p>Activará la generación de reportes estadísticos del Semestre</p>
        </div>
        <h3><a href="#">Cierre Año Escolar</a></h3>
        <div>
            <p>Realización del Cierre de Año Escolar</p>
            <p>Activará la generación de reportes estadísticos Anuales</p>
        </div>
        <h3><a href="#">Modificar Calificación</a></h3>
        <div id="modificarCalificaciones">
            <div  class="divEstilo1">
                <label>Selecione Curso:&ensp;&ensp;&ensp;&ensp;</label>
                <select class="ui-corner-all ancho150" id="cursoModCal">
                    <option selected></option>
                    <?for($i=0;$i<$cantCursos;$i++):?>
                        <?foreach(${"cursos".$i} as $row):?>
                            <option value="<?=$row->IDCURSO;?>"><?=$row->NOMBRE.' '.$row->LETRA;?></option>
                        <?endforeach;?>
                    <?endfor;?>
                </select>    
                <div id="datosModCal"></div>
            </div>
        </div>
    </div>
</div>
<script>
$("#accordionUTP2").accordion({collapsible: true});
$("#seleccionAnoAcademico").change(
        function(){
            if($("#seleccionAnoAcademico").val()!=''){
            $.ajax({
                url:base_url+'sigeca/cargaConfigAnoAcademico',
                data:{ano:$("#seleccionAnoAcademico").val()},
                type:"POST",
                cache:false,
                success:
                    function(htmlresponse,data){
                        $("#anoAcademicoSeleccionado").html(htmlresponse,data);
                        $.ajax({
                            url:base_url+'sigeca/cargaTabs1_1',
                            data:{ano:$("#seleccionAnoAcademico").val(),idutp:$("#idUTP").val()},
                            type:"POST",
                            cache:false,
                            success:
                                function(htmlresponse,data){
                                    $("#tabs-1-1").html(htmlresponse,data);
                                } 
                        });
                        $.ajax({
                            url:base_url+'sigeca/cargaTabs1_12',
                            data:{ano:$("#seleccionAnoAcademico").val(),idutp:$("#idUTP").val()},
                            type:"POST",
                            cache:false,
                            success:
                                function(htmlresponse,data){
                                    $("#tablaPlanificacionCurso").html(htmlresponse,data);
                                } 
                        });
                        $.ajax({
                            url:base_url+'sigeca/cargaTabs1_2',
                            data:{ano:$("#seleccionAnoAcademico").val()},
                            type:"POST",
                            cache:false,
                            success:
                                function(htmlresponse,data){
                                    $("#tabs-1-2").html(htmlresponse,data);
                                } 
                        });
                    }
            });
        }
        }
    );
    $("#cursoModCal").change(function(){
        $.post(base_url+'sigeca/modificarCalificacion',
            {curso:$("#cursoModCal").val()},
            function(htmlresponse,data){
                $('#datosModCal').html(htmlresponse,data);
                $("#modificarCalificaciones").css('height','auto');
            });
    });
</script>