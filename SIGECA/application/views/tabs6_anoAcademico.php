<div class="divEstilo1">
    <label>Último Año : </label><input id="ultimoAno" type="text" size="10" disabld value="<?=$ultimoAno;?>" />
    <button id="agregarAnoAcademico2">Nuevo Año</button>
</div>
<script>
$("#agregarAnoAcademico2").button().click(
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
    });
</script>
