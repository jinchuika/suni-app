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
    $libs->incluir('jquery');
    $libs->incluir('bs');
    $libs->incluir('calendario');
    ?>

    <meta charset="UTF-8">
    <title>SUNI</title>
    <style>
    .popover {
        z-index: 1010;
    }
    </style>
    <script>
    /*$(document).ready(function () {
        <?php if($sesion->get("rol") < 3){ ?>
            $.ajax({
                url: 'src/libs/listar_evento_actual.php?ejecutar=1',
                success: function (data) {
                    var data = $.parseJSON(data);
                    $.each(data, function (index, item) {
                        if(item.tipo_resp==1){
                            $("#tabla_home").append("<tr><td>"+item[0]+" "+item[1]+" está impartiendo <a href='crs/curso.php?id_curso="+item[2]+"'>"+item[3]+"</a> al <a href='cap/grp/buscar.php?id_grupo="+item[9]+"'>grupo "+item[6]+"</a> en <a href='cap/sed/sede.php?id="+item[4]+"'>"+item[5]+"</a>. Terminará a las "+item[8]+"</td></tr>");
                        }
                        if(item.tipo_resp==2){
                            $("#tabla_home").append("<tr><td>"+item["nombre"]+" "+item["apellido"]+" está brindando asesoría en <a href='cap/sed/sede.php?id="+item["id_sede"]+"'>"+item["nombre_sede"]+"</a>. Terminará a las "+item["hora_fin"]+"</td></tr>");
                        }
                    });
                    $("#tabla_home").show(250);
                }
            });
        
<?php } ?>
$('#calendar').fullCalendar({
    header: {
        left: '',
        center: 'title',
        right: ''
    },
    defaultView: 'agendaDay',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
    timeFormat: 'H:mm',

    eventRender: function(calEvent, jsEvent, view) {
        $(this).attr('rel', 'popover');
        $(this).attr('data-toggle', 'popover');
    },
    eventMouseover: function(calEvent, jsEvent, view) {
        var $this = $(this);
        $this.popover({
            animation: true,
            placement: function (context, source) {
                var position = $(source).position();
                if (position.left > 315) {
                    if (position.top > 475){
                        return "top";
                    }
                    return "left";
                }
                if (position.left < 315) {
                    if (position.top > 475){
                        return "top";
                    }
                    return "right";
                }
                if (position.top < 250){
                    return "bottom";
                }
                return "top";
            },

            html: true,
            content: "<p>" + calEvent.other.curso +"</p><small>" + calEvent.other.inicio +" - " + calEvent.other.fin +" al Grupo " + calEvent.other.grupo +"<br>en " + calEvent.other.sede +"</small>" 
        });
        $(this).popover('show');
    },
    eventMouseout: function(calEvent, jsEvent, view) {
        $(this).popover('hide');
    },
    eventClick: function (event) {
        if (event.url) {
            window.open(event.url);
            return false;
        }
        $(this).tootlip('show');
    },
    contentHeight: 600,
    eventSources: [
    {
        events: function (start, end, callback) {
            $.ajax({
                url: 'src/libs/listar_evento_actual.php?ejecutar_diario=1',
                type: 'post',
                data: {
                    <?php
                    if($sesion->get('rol')>2){
                        echo "id_persona: ".$sesion->get('id_per');
                    }
                    ?>
                },
                beforeSend : function (){

                },
                success: function(data) {
                    var events = [];
                    var data = $.parseJSON(data);
                    $.each(data, function (index, item) {
                        events.push({
                            title: $(item).attr('title'),
                            start: $(item).attr('start'),
                            end: $(item).attr('end'),
                            other: $(item).attr('other'),
                            color: $(item).attr('color'),
                            allDay: $(item).attr('allDay'),
                            url: $(item).attr('url')
                        });
                    });

                    callback(events);
                }
            });
        }//,
        //cache: true
    }
    ]
});

});*/
</script>
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
        <div class="span3"><!-- start feedwind code --><script type="text/javascript">document.write('<script type="text/javascript" src="' + ('https:' == document.location.protocol ? 'https://' : 'http://') + 'feed.mikle.com/js/rssmikle.js"><\/script>');</script><script type="text/javascript">(function() {var params = {rssmikle_url: "http://funsepa-suni.tumblr.com",rssmikle_frame_width: "300",rssmikle_frame_height: "400",rssmikle_target: "_blank",rssmikle_font: "Geneva, Arial, sans-serif",rssmikle_font_size: "12",rssmikle_border: "off",responsive: "on",rssmikle_css_url: "",text_align: "left",corner: "on",autoscroll: "off",scrolldirection: "up",scrollstep: "3",mcspeed: "20",sort: "New",rssmikle_title: "on",rssmikle_title_sentence: "Actualizaciones",rssmikle_title_link: "",rssmikle_title_bgcolor: "#4073CD",rssmikle_title_color: "#FFFFFF",rssmikle_title_bgimage: "",rssmikle_item_bgcolor: "#FFFFFF",rssmikle_item_bgimage: "",rssmikle_item_title_length: "55",rssmikle_item_title_color: "#666666",rssmikle_item_border_bottom: "on",rssmikle_item_description: "on",rssmikle_item_description_length: "150",rssmikle_item_description_color: "#666666",rssmikle_item_date: "off",rssmikle_timezone: "Etc/GMT",datetime_format: "%b %e, %Y %l:%M:%S %p",rssmikle_item_description_tag: "on_flexcroll",rssmikle_item_description_image_scaling: "off",rssmikle_item_podcast: "off"};feedwind_show_widget_iframe(params);})();</script><div style="font-size:10px; text-align:center; "><a href="http://feed.mikle.com/" target="_blank" style="color:#CCCCCC;">RSS Feed Widget</a><!--Please display the above link in your web page according to Terms of Service.--></div><!-- end feedwind code --></div>
    </div>
    <?php
    $id_rol = $sesion->get('rol');
    $id_per = $sesion->get("id_per");
    $extras = array('id'=>'id_view_'.$id_rol, 'id_per'=>$id_per);
    $libs->imprimir('js', 'app/src/js-libs/home/view-'.$id_rol.'.js', $extras);
    ?>
</body>
</html>