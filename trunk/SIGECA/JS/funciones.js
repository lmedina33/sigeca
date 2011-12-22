function funcionesInicio()
{
    $("#principal").show();
    
    //Acordiones
        $("#accordionACA").accordion({collapsible: true});
        $("#accordionINF").accordion({collapsible: true});
        $("#accordionUTP2").accordion({collapsible: true});
    
    //Botones
        $("#generarInformeNotasParciales").button();
        $("#guardarNotas").button();
        
    
    //Tabs    
        $("#tabs").tabs();
        
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
                            data:{ano:$("#seleccionAnoAcademico").val()},
                            type:"POST",
                            cache:false,
                            success:
                                function(htmlresponse,data){
                                    $("#tabs-1-1").html(htmlresponse,data);
                                } 
                        });
                        $.ajax({
                            url:base_url+'sigeca/cargaTabs1_12',
                            data:{ano:$("#seleccionAnoAcademico").val()},
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
}
function updateTips( t ) {
    tips
            .text( t )
            .addClass( "ui-state-highlight validateTips2" )
            .css('display','block');
    setTimeout(function() {
            tips.removeClass( "ui-state-highlight", 1500 );
        }, 500 );
}
function checkLength( o, n, min, max ) {
    if ( o.val().length > max || o.val().length < min ) {
            o.addClass( "ui-state-error" );
            updateTips( "Largo de " + n + " debe estar entre " +
                    min + " y " + max + "." );
            return false;
    } else {
            return true;
    }
}
function actualizaTabla()
{
    $.ajax({
        url:base_url+'sigeca/cargaTabs1_1',
        data:{ano:$("#seleccionAnoAcademico").val()},
        type:"POST",
        cache:false,
        success:
            function(htmlresponse,data){
                $("#tabs-1-1").html(htmlresponse,data);
            } 
    });
    $.ajax({
        url:base_url+'sigeca/cargaTabs1_12',
        data:{ano:$("#seleccionAnoAcademico").val()},
        type:"POST",
        cache:false,
        success:
            function(htmlresponse,data){
                $("#tablaPlanificacionCurso").html(htmlresponse,data);
            } 
    });
    $('#divTabla').css('height','auto');
}
function actualizaTabla2()
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
        $('#divTabla').css('height','auto');
}
function validaAnoAcademico(selcalendario,boton){
    var seleccionado    = parseInt($("#seleccionAnoAcademico").val());
    var calendario      = parseInt($(selcalendario).val().substr(6));

    if(calendario > seleccionado)
    {
        alert('Fecha seleccionada fuera del año en que está trabajando!');
        $("#"+boton).attr("disabled",true);
        $(selcalendario).val('');
        $(selcalendario).addClass("ui-state-error ui-state-highlight");
        return 1;
    }
    else
    {
        $(selcalendario).removeClass("ui-state-error ui-state-highlight");
        $("#"+boton).attr("disabled",false);
        return 0;
    }
}
function validaAnoAcademico1(selcalendario,boton){
    var seleccionado    = parseInt($("#seleccionAnoAcademico").val());
    var calendario      = parseInt($(selcalendario).val().substr(6));

    if(calendario > seleccionado)
    {
        alert('Fecha seleccionada fuera del año en que está trabajando!');
        $(selcalendario).val('');
        $(selcalendario).addClass("ui-state-error ui-state-highlight");
        return 1;
    }
    else
    {
        $(selcalendario).removeClass("ui-state-error ui-state-highlight");
        return 0;
    }
}
function eliminaFilaTabla()
{
    $(this).parent().parent().remove();
    var tbl = document.getElementById('configCalificaciones');
    if(tbl.rows.length == 1){
        $("#configCalificaciones").hide();
        $("#contadorCalif").val(0);
    }
}