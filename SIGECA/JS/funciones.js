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

    if(calendario != seleccionado)
    {
        alert('Fecha seleccionada fuera del año en que está trabajando!');
        $("#"+boton).attr("disabled",true);
        $(selcalendario).val('');
        $(selcalendario).addClass("ui-state-error ui-state-highlight");
        return 1;
        
    }
    else
    {
        if(selcalendario != "#feriadosCal"){
            $.post(base_url+'sigeca/verificaFeriado',
                {fecha:$(selcalendario).val()},
                function(data){
                    if(data.msj == 'si') //es feriado
                    {
                        alert('Fecha seleccionada es feriado por: '+data.tipo);
                        $("#"+boton).attr("disabled",true);
                        $(selcalendario).val('');
                        $(selcalendario).addClass("ui-state-error ui-state-highlight");
                        return 1;
                    }
                    else //no es feriado
                    {
                        $(selcalendario).removeClass("ui-state-error ui-state-highlight");
                        $("#"+boton).attr("disabled",false);
                        return 0;
                    }
                },"json");
        }
        return 0;
    }
}
function validaAnoAcademico1(selcalendario){    
    var ano = new Date();
    var dia1 = parseInt($(selcalendario).val().substr(0,2));
    var mes1 = parseInt($(selcalendario).val().substr(3,2));
    var ano1 = parseInt($(selcalendario).val().substr(6));
    var dia2 = parseInt(ano.getDate());
    var mes2 = parseInt(ano.getMonth())+1;
    var ano2 = parseInt(ano.getFullYear());
    
    var seleccionado    = parseInt(ano.getFullYear());
    var calendario      = parseInt($(selcalendario).val().substr(6));

    if(calendario != seleccionado)
    {
        $(selcalendario).val('');
        $(selcalendario).addClass("ui-state-error ui-state-highlight");
        updateTips('Fecha seleccionada fuera del año en que está trabajando!');
        return 1;
    }
    else
    {
        if(dia1<dia2 || mes1<mes2 || ano1<ano2)
        {
            $(selcalendario).val('');
            $(selcalendario).addClass("ui-state-error ui-state-highlight");
            updateTips('Imposible configurar en una fecha posterior!');
            return 1;
        }
        else
        {
            if(selcalendario != "#feriadosCal"){
                $.post(base_url+'sigeca/verificaFeriado',
                    {fecha:$(selcalendario).val()},
                    function(data){
                        if(data.msj == 'si') //es feriado
                        {
                            $(selcalendario).val('');
                            $(selcalendario).addClass("ui-state-error ui-state-highlight");
                            updateTips('Fecha seleccionada es feriado por: '+data.tipo);
                            return 1;
                        }
                        else //no es feriado
                        {
                            $( ".validateTips" ).css('display','none');
                            $(selcalendario).removeClass("ui-state-error ui-state-highlight");
                            return 0;
                        }
                    },"json");
            }
            return 0;
            /*$( ".validateTips" ).css('display','none');
            $(selcalendario).removeClass("ui-state-error ui-state-highlight");
            return 0;*/
        }            
    }
}
function eliminaFilaTabla()
{
    $(this).parent().parent().remove();
    var tbl = document.getElementById('configCalificaciones');
    $("#ultimaFila").val(tbl.rows.length-1);
    if(tbl.rows.length == 1){
        $("#configCalificaciones").hide();
        $("#contadorCalif").val(0);
    }
}
function eliminaFilaTabla2(valor,idevaluacion)
{
    $("#fechaCalif"+valor).datepicker( "destroy" )
    $("#fechaCalif"+valor).remove();
    $("#selectTipo"+valor).remove();
    if($("#ponderacion").val() == 'si')
        $("#ponderacionCalif"+valor).remove();
    $("#"+valor).parent().parent().remove();
    
    
    //Funcion que se encarga de eliminar una fila de la tabla!
    var tbl = document.getElementById('configCalificaciones');
    $("#ultimaFila").val(tbl.rows.length-1);
    $(this).parent().parent().remove();
    if(tbl.rows.length == 1){
        $("#configCalificaciones").hide();
        $("#contadorCalif").val(0);
    }
    
    $.post(base_url+'sigeca/eliminaConfigCalificacion',{idEvaluacion:idevaluacion,idAsignatura:$("#idAsignatura").val()})
}

function validaFormatoNota(nota)
{
    if( $("#"+nota).val().length >2 || $("#"+nota).val().length ==1){
        alert("Debe respetar el formato, dos cifras sin separación. Ejemplo: 70  "+nota);
        $("#1").focus();
        //$("#"+nota).select();
        //$("#"+nota).focus();
        
    }
}