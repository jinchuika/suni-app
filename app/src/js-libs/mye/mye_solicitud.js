/**
 * Clase para control de solicitud
 */
 function MyeSolicitud (nivel_creado) {
    this.module = '_me';
    this.ctrl = 'me_solicitud';
}

if(!modal_c){
  var modal_c = modal_carga_gn();
  modal_c.crear();
}

MyeSolicitud.prototype = new General();


MyeSolicitud.prototype.crearInforme = function(formulario) {
    $("#tabla_solicitud").find("tr:gt(0)").remove();
    
    var args = $(formulario).serializeObject();
    args.requisito = new Array();
    
    $.each($('.chk_requisito:checked'), function (index, item) {
        args.requisito[$(item).data('name')] = 1;
    });
    modal_c.mostrar();
    $.ajax({
        url: this.makeUrl('crear_informe'),
        data: {
            ctrl: this.getInfo('crear_informe'),
            args: JSON.stringify(args)
        },
        dataType: 'json',
        success: function (respuesta) {
            var texto_append = '';
            $.each(respuesta, function (index, item) {
                texto_append += '<tr><td>'+ crear_enlace(item['id_solicitud'], 'app/mye/?udi='+item['udi'], item['udi']) +'</td>';
                texto_append += '<td>'+ crear_enlace(item['nombre_escuela'], 'app/esc/perfil.php?id='+item['id_escuela'], item['udi']) +'</td>';
                texto_append += '<td>'+(item['nombre_municipio'] ? item['nombre_municipio'] : '' )+'</td>';
                texto_append += '<td>'+(item['director'] ? item['director']['nombre']+' '+ item['director']['apellido'] : '');
                texto_append += '<td>'+(item['director']['tel_movil'] ? item['director']['tel_movil'] : '' )+'</td>';
                texto_append += '<td>'+(item['cant_alumno'] ? item['cant_alumno'] : '' )+'</td>';
                texto_append += '<td>'+(item['equipada']=="1" ? 'Equipada' : 'Sin equipar' )+'</td>';
                texto_append += '</tr>';
            });
            $('#tabla_solicitud').append(texto_append);
            modal_c.ocultar();
        }
    });
};

crear_enlace = function(texto, url, titulo) {
    var texto_append = '';
    if(texto){
        texto_append = '<a title="'+titulo+'" href="'+nivel_entrada+url+'">'+texto+'</a>';
    }
    return texto_append;
};

MyeSolicitud.prototype.getInfo = function(action) {
    return {module: this.module, ctrl: this. ctrl, action: action};
};


$(document).ready(function () {
    var mye_solicitud = new MyeSolicitud(nivel_entrada);
    
    listar_campos_select('app/src/libs_gen/gn_departamento.php?fn_nombre=listar_departamento', 'id_departamento', 'vacio');
    $('#id_departamento').on('change', function () {
        var args = {'id_departamento': $(this).val()};
        listar_campos_select('app/src/libs_gen/gn_municipio.php?fn_nombre=listar_municipio&args='+JSON.stringify(args), 'id_municipio', 'vacio');
    })
    .trigger('change');
    
    $.getScript(nivel_entrada+'app/src/js-libs/general_datetime.js', function () {
        input_rango_fechas('fecha_inicio','fecha_fin');
    });
    
    $('#btn_informe_solicitud').on('click', function () {
        mye_solicitud.crearInforme($('#form_informe_solicitud'));
    });
});