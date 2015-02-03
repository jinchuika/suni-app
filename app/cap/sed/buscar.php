<?php
/**
* -> Buscador de sedes
*/
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar sede</title>
    <?php   $libs->defecto();
    $libs->incluir('jquery-ui');
    $libs->incluir('gn-listar');
    ?>
    <script>
    $(document).ready(function () {
        <?php
        if($sesion->get('rol')<3){
            ?>
            listar_campos_select('app/src/libs_gen/usr.php?fn=listar_usuario&filtros={"rol":"3"}', 'id_per', 'vacio');
            <?php
        }
        else{
            ?>
            $('#id_per').append('<option value="<?php echo $sesion->get('id_per'); ?>"><?php echo $sesion->get('nombre'); ?></option>');
            <?php
        }
        ?>  
        /* Para la búsqueda de sedes */
        $("#id_sede").select2({
            ajax: {
                url: function () {
                    var id_per = $('#id_per').val() ? 'id_per='+$('#id_per').val() : '';
                    return '../../src/libs/listar_sede.php?'+id_per;
                },
                dataType: 'json',
                data: function(term, page) {
                    return {
                        nombre: term,
                    };
                },
                results: function(data) {
                    var results = [];
                    $.each(data, function(index, item){
                        results.push({
                            id: item.id,
                            text: item.nombre
                        });
                    });
                    return {
                        results: results
                    };
                }
            }
        });
        $("#boton_busqueda_sede").click(function (){
            window.location = nivel_entrada+'app/cap/sed/sede.php?id='+$('#id_sede').val();
        });
    });
    </script>
</head>
<body>
    <?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir); ?>
    <div class="row row-fluid">
        <div class="span1"></div>
        <div class="span10">
            <form class="form-horizontal well">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label" for="id_per">Capacitador</label>
                        <div class="controls">
                            <select id="id_per" name="id_per" class="input-large">
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="id_sede">Sede</label>
                        <div class="controls">
                            <input id="id_sede" name="id_sede" type="text" placeholder="" class="input-xlarge" required="">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="boton_busqueda_sede"></label>
                        <div class="controls">
                            <input type="button" id="boton_busqueda_sede" name="boton_busqueda_sede" class="btn btn-primary" value="Abrir">
                        </div>
                    </div>

                </fieldset>
            </form>

        </div>
        <div class="span1"></div>
    </div>
</body>
</html>