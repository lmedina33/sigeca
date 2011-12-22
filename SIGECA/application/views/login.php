<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>SIGECA</title>
        <link type="text/css" href="<?=base_url();?>CSS/cupertino/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
        <script type="text/javascript" src="<?=base_url();?>JS/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="<?=base_url();?>JS/jquery-ui-1.8.16.custom.min.js"></script>
        <script type="text/javascript" src="<?=base_url();?>JS/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="<?=base_url();?>JS/funciones.js"></script>
        <link type="text/css" href="<?=base_url();?>CSS/login.css" rel="stylesheet"/>
        <link type="text/css" href="<?=base_url();?>CSS/principal.css" rel="stylesheet" />
        <link href="<?=base_url();?>images/insignia.ico" rel="shortcut icon"/>
        <script type="text/javascript">
            $(document).ready(function(){
                base_url = '<?=base_url();?>index.php/';
                $.post(base_url+"sigeca/verificaLogin",{}, function(data){
                    if(data.msj=='true') //Está logeado!
                    {
                        $("#login").hide();
                        $.ajax({
                            url: base_url+'sigeca/cargaPrincipal',
                            cache:false,
                            success:function(htmlresponse,data){
                                $("#principal").html(htmlresponse,data);
                                funcionesInicio();
                            }
                        });
                    }
                    else
                    {
                        $("#principal").hide();
                        $("#login").show();
                    }
                },'json');
                $("#btn1").button().click(
                    function(){
                        $('#msj').hide();
                        $.post(base_url+'sigeca/login',{usuario:$("#usuario").val(),clave:$("#clave").val()},
                            function(data){
                                if(data.msj == 'false') //No logeado!
                                {
                                    $('#msj').html('<p>Usuario o Clave no válidos</p>');
                                    $('#msj').show();
                                }
                                else //usuario válido
                                {
                                    $("#login").hide();
                                    $.ajax({
                                        url: base_url+'sigeca/cargaPrincipal',
                                        cache:false,
                                        success:function(htmlresponse,data){
                                           $("#principal").html(htmlresponse,data);
                                           funcionesInicio();
                                        }
                                    });
                                }
                            },'json'
                        );
                    }
                );
            });
        </script>
    </head>
    <body>
        <div id="login" style="display:none;">
            <div id="entradas">
                <input id="usuario" type="text" size="25"/>
                <input id="clave" type="password" size="25"/>
            </div>
            <div id="msj" style="display:none;"></div>
            <button id="btn1">Conectar</button>
        </div>
        
        <div id="principal" style="display:none;"></div>
    </body>
</html>