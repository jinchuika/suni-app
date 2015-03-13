<?php
/**
* -> Página de inicio
*/
include 'src/libs/incluir.php';
$nivel_dir = 1;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
if($sesion->get("rol") == 9){
    /* Redireccionar FundRaising */
    header("Location: dir");
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width">
    <?php
    $libs->defecto();
    //$libs->incluir('jquery');
    //$libs->incluir('bs');
    $libs->incluir('calendario');
    ?>

    <meta charset="UTF-8">
    <title>SUNI</title>
    <style>
    .popover {
        z-index: 1010;
    }
    </style>
</head>
<body>
    <?php
    include 'src/libs/cabeza.php';
    $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);
    ?>
    <div class="row">
        <div class="span1"></div>
        <div class="span8">
            <div class="well">
                
                <?php if($sesion->get("rol") < 3){ ?>
                <h1> En estos momentos</h1>

                <table class="table hide" id="tabla_home">
                    
                </table>
                <?php } ?>
                <div id='calendar'></div>
            </div>
        </div>
        <div class="span3">
            <!-- start feedwind code -->
            <script type="text/javascript">document.write('<script type="text/javascript" src="' + ('https:' == document.location.protocol ? 'https://' : 'http://') + 'feed.mikle.com/js/rssmikle.js"><\/script>');</script>
            <script type="text/javascript">(function() {var params = {rssmikle_url: "http://funsepa-suni.tumblr.com",rssmikle_frame_width: "300",rssmikle_frame_height: "400",rssmikle_target: "_blank",rssmikle_font: "Geneva, Arial, sans-serif",rssmikle_font_size: "12",rssmikle_border: "off",responsive: "on",rssmikle_css_url: "",text_align: "left",corner: "on",autoscroll: "off",scrolldirection: "up",scrollstep: "3",mcspeed: "20",sort: "New",rssmikle_title: "on",rssmikle_title_sentence: "Actualizaciones",rssmikle_title_link: "",rssmikle_title_bgcolor: "#4073CD",rssmikle_title_color: "#FFFFFF",rssmikle_title_bgimage: "",rssmikle_item_bgcolor: "#FFFFFF",rssmikle_item_bgimage: "",rssmikle_item_title_length: "55",rssmikle_item_title_color: "#666666",rssmikle_item_border_bottom: "on",rssmikle_item_description: "on",rssmikle_item_description_length: "150",rssmikle_item_description_color: "#666666",rssmikle_item_date: "off",rssmikle_timezone: "Etc/GMT",datetime_format: "%b %e, %Y %l:%M:%S %p",rssmikle_item_description_tag: "on_flexcroll",rssmikle_item_description_image_scaling: "off",rssmikle_item_podcast: "off"};feedwind_show_widget_iframe(params);})();</script>
            <!-- end feedwind code -->
        </div>
    </div>
    <?php
    $id_rol = $sesion->get('rol');
    $id_per = $sesion->get("id_per");
    $extras = array('id'=>'id_view_'.$id_rol, 'id_per'=>$id_per);
    $libs->imprimir('js', 'app/src/js-libs/home/view-'.$id_rol.'.js', $extras);
    ?>
</body>
</html>