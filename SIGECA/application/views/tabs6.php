<div id="tabs-6">
    <div id="accordionADMIN">
        <h3><a href="#">Año Académico</a></h3>
        <div id="divAnioAcademico">
            <div class="divEstilo1">
                <label>Último Año : </label><input id="ultimoAno" type="text" size="10" disabld value="<?=$ultimoAno;?>" />
                <button id="agregarAnoAcademico">Nuevo Año</button>
            </div>
        </div>
        <h3><a href="#">Cursos</a></h3>
        <div id="divDatosEditarCursos">
            <div class="divEstilo1">
                <table>
                    <tr>
                        <td><label>Editar Curso</label></td>
                        <td><label>:</label></td>
                        <td>
                            <select id="editarCurso" class="ancho180 ui-corner-all">
                                <option></option>
                                <?foreach($cursos as $row):?>
                                    <option value="<?=$row->IDCURSO;?>"><?=$row->NOMBRE.' '.$row->LETRA;?></option>
                                <?endforeach;?>
                            </select>
                        </td>
                        <td><button id="nuevoCurso">Crear Nuevo Curso</button></td>
                    </tr>
                </table>
                <div id="datosEditarCurso" style="display:none;">
                </div>
            </div>
        </div>
        <h3><a href="#">Asignaturas</a></h3>
        <div id="divDatosEditarAsignatura">
            <div class="divEstilo1">
                <table>
                    <tr>
                        <td><label>Editar Asignatura</label></td>
                        <td><label>:</label></td>
                        <td>
                            <select id="editarAsignatura" class="ancho180 ui-corner-all">
                                <option></option>
                                <?foreach($asignaturas as $row):?>
                                    <option value="<?=$row->IDASIGNATURA;?>"><?=$row->NOMBREASIGNATURA;?></option>
                                <?endforeach;?>
                            </select>
                        </td>
                        <td><button id="nuevaAsignatura">Crear Nueva Asignatura</button></td>
                    </tr>
                </table>
                <div id="datosEditarAsignatura" style="display:none;">
                </div>
            </div>
        </div>
        <h3><a href="#">Configurar UTP</a></h3>
        <div id="divConfigurarUTP">
            <div class="divEstilo1">
                <table>
                    <tr>
                        <th>Profesores</th>
                        <th>Cursos</th>
                    </tr>
                    <tr>
                        <td>
                            <select id="adminProfesores" class="ancho150 ui-corner-all">
                                <option selected></option>
                                <?foreach($profesores as $row):?>
                                    <option value="<?=$row->IDPROFESOR;?>"><?=$row->NOMBRES.' '.$row->APELLIDOP.' '.$row->APELLIDOM;?></option>
                                <?endforeach;?>
                            </select>
                        </td>
                        <td>
                            <div id="divAdminCursos">
                                <select id="adminCursos" class="ancho150 ui-corner-all">
                                    <option selected></option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <button id="asignaCurso">Asignar</button>
                        </td>
                    </tr>
                </table>
                <div id="tablaUTP">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#adminProfesores").change(function(){
        $.post(base_url+"sigeca/tablaUTP",{idprofesor:$("#adminProfesores").val()},
        function(htmlresponse,data){
            $("#tablaUTP").html(htmlresponse,data);
            $("#divConfigurarUTP").css('height','auto');
            $.post(base_url+"sigeca/selectDivAdminCurso",
                function(htmlresponse,data){
                    $("#divAdminCursos").html(htmlresponse,data);
                }
            );
        }
        );
    });
    $("#asignaCurso").button().click(function(){
        $.post(
            base_url+"sigeca/asignarUTP",
            {profesor:$("#adminProfesores").val(),curso:$("#adminCursos").val()},
            function(htmlresponse,data){
                $.post(base_url+"sigeca/tablaUTP",{idprofesor:$("#adminProfesores").val()},
                    function(htmlresponse,data){
                        $("#tablaUTP").html(htmlresponse,data);
                        $("#divConfigurarUTP").css('height','auto');
                    });
                $.post(base_url+"sigeca/selectDivAdminCurso",
                    function(htmlresponse,data){
                        $("#divAdminCursos").html(htmlresponse,data);
                    }
                );
                $("#divConfigurarUTP").css('height','auto');
            }
        );
    });
    $("#accordionADMIN").accordion({collapsible:true});
    
    $("#nuevoCurso").button().click(function(){
            $("#crearCurso").dialog("open");
        });
    $("#nuevaAsignatura").button().click(function(){
            $("#crearAsignatura").dialog("open");
        });
    //Dialog
    $("#dialog:ui-dialog").dialog("destroy");
    
    var nombre = $( "#nombreCurso" ),
        letra = $("#letraCurso"),
        jornada = $("#jornadaCurso"),
        capacidad = $("#capacidadCurso"),
        orden = $("#ordenCurso"),
        allFields = $( [] ).add(nombre).add(letra).add(jornada).add(capacidad).add(orden),
        tips = $( ".validateTips" );

    $("#crearCurso").dialog({
        autoOpen: false,
        height: 300,
        width: 350,
        modal: true,
        buttons: {'Guardar':function(){
                    var bValid = true;
                    allFields.removeClass( "ui-state-error" );
                    bValid = bValid && checkLength( nombre, "Nombre Curso", 1, 20 );
                    bValid = bValid && checkLength( letra, "Letra", 1, 1 );
                    bValid = bValid && checkLength( jornada, "Jornada", 1, 20 );
                    bValid = bValid && checkLength( capacidad, "Capacidad", 1, 2 );
                    bValid = bValid && checkLength( orden, "Orden", 1, 2 );
                    if(bValid)
                    {
                        $.post(
                            base_url+'sigeca/guardarNuevoCurso',
                            {nombre:nombre.val(),letra:letra.val(),jornada:jornada.val(),capacidad:capacidad.val(),orden:orden.val()}
                        );
                        $( this ).dialog( "close" );
                    }
                },
                  'Cancelar':function(){
                      $( this ).dialog( "close" );
                  }
        },
        close: function() {
            nombre.val( "" ).removeClass( "ui-state-error" );
            letra.val( "" ).removeClass( "ui-state-error" );
            jornada.val( "" ).removeClass( "ui-state-error" );
            capacidad.val( "" ).removeClass( "ui-state-error" );
            orden.val( "" ).removeClass( "ui-state-error" );
            tips.val("").removeClass("validateTips2");
            tips.css('display','none');
            }
    });
     var nombreAS = $( "#nombreAsignatura" ),
        allFields = $( [] ).add(nombreAS),
        tips = $( ".validateTips" );
        
    $("#crearAsignatura").dialog({
        autoOpen: false,
        height: 220,
        width: 310,
        modal: true,
        buttons: {'Guardar':function(){
                    var bValid = true;
                    allFields.removeClass( "ui-state-error" );
                    bValid = bValid && checkLength( nombreAS, "Nombre Asignatura", 1, 100 );
                    if(bValid)
                    {
                        var electivo = $("input[name='electivo']:checked").val();
                        var calificacion = $("input[name='calificacion']:checked").val();
                       
                        $.post(
                            base_url+'sigeca/guardarNuevaAsignatura',
                            {
                                nombre:nombreAS.val(),
                                electivo:electivo,
                                calificacion:calificacion
                            },function(htmlresponse, data){
                                $("#divDatosEditarAsignatura").html(htmlresponse,data);
                            }
                        );
                        $( this ).dialog( "close" );
                    }
                },
                  'Cancelar':function(){
                      $( this ).dialog( "close" );
                  }
        },
        close: function() {
            nombreAS.val( "" ).removeClass( "ui-state-error" );
            tips.val("").removeClass("validateTips2");
            $('#electivoNo').attr('checked','true');
            $('#califNota').attr('checked','true');
            tips.css('display','none');
            }
    });
    
    $("#editarCurso").change(
        function (){
            $.ajax({
                url:base_url+'sigeca/cargarDatosEditarCursos',
                type:'POST',
                cache:false,
                data:{idcurso: $("#editarCurso").val()},
                success: function(htmlresponse,data){
                    $('#divDatosEditarCursos').css('float','none');
                    $('#divDatosEditarCursos').css('height','250px');
                    $("#datosEditarCurso").html(htmlresponse,data);
                    $("#datosEditarCurso").show();
                }
            });
        }
    );
    $("#editarAsignatura").change(
        function (){
            $.ajax({
                url:base_url+'sigeca/cargarDatosEditarAsignatura',
                type:'POST',
                cache:false,
                data:{idasignatura: $("#editarAsignatura").val()},
                success: function(htmlresponse,data){
                    $('#divDatosEditarAsignatura').css('float','none');
                    $('#divDatosEditarAsignatura').css('height','250px');
                    $("#datosEditarAsignatura").html(htmlresponse,data);
                    $("#datosEditarAsignatura").show();
                }
            });
        }
    );
    $("#agregarAnoAcademico").button().click(
        function(){
            var ultimo = parseInt($("#ultimoAno").val());
            $.ajax({
                    url:base_url+'sigeca/guardaNuevoAnoAcademico',
                    data:{ano:ultimo+1},
                    type:"POST",
                    cache:false,
                    success:
                        function(htmlresponse,data){
                            $("#divAnioAcademico").html(htmlresponse,data);
                        }
                    }
                );
        }
    );
</script>
<div id="crearAsignatura" title="Crear Nueva Asignatura">
    <table>
        <tr>
            <td><label>Nombre</label></td>
            <td>:</td>
            <td><input id="nombreAsignatura"     class="ancho180 ui-corner-all"    /></td>
        </tr>
        <tr>
            <td>
                <label>Electivo</label>
            </td>
            <td>:</td>
            <td>
                <input id="electivoNo" type="radio" name="electivo" value="No" checked></input><label>No</label>
                &ensp;&ensp;<input type="radio" name="electivo" value="Si"></input><label>Si</label>
            </td>
        </tr>
        <tr>
            <td>
                <label>Calificación</label>
            </td>
            <td>:</td>
            <td>
                <input id="califNota" type="radio" name="calificacion" value="Nota" checked></input><label>Nota</label>
                <input type="radio" name="calificacion" value="Concepto"></input><label>Concepto</label>
            </td>
        </tr>
    </table>
    <p class="validateTips"></p>
</div>
<div id="crearCurso" title="Crear Nuevo Curso">
    <table>
        <tr>
            <td><label>Nombre</label></td>
            <td>:</td>
            <td><input id="nombreCurso"     class="ancho180 ui-corner-all"    /></td>
        </tr>
        <tr>
            <td><label>Letra</label></td>
            <td>:</td>
            <td><input id="letraCurso"      class="ancho180 ui-corner-all"    /></td>
        </tr>
        <tr>
            <td><label>Jornada</label></td>
            <td>:</td>
            <td><input id="jornadaCurso"    class="ancho180 ui-corner-all"    /></td>
        </tr>
        <tr>
            <td><label>Capacidad</label></td>
            <td>:</td>
            <td><input id="capacidadCurso"  class="ancho180 ui-corner-all"    /></td>
        </tr>
        <tr>
            <td><label>Orden</label></td>
            <td>:</td>
            <td><input id="ordenCurso"      class="ancho180 ui-corner-all"    /></td>
        </tr>
    </table>
    <p class="validateTips"></p>
</div>