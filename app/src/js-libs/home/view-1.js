/**
 * Gestión del rol ADMINISTRADOR
 */

$(document).ready(function () {
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
	    eventSources: [{
	        events: function (start, end, callback) {
	            $.ajax({
	                url: 'src/libs/listar_evento_actual.php?ejecutar_diario=1',
	                type: 'post',
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
	        }
	    }]
	});
});