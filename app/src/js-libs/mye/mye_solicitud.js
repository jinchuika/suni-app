/**
 * Clase para control de solicitud
 */
function MyeSolicitud (nivel_creado) {
    this.module = '_me';
    this.ctrl = 'me_solicitud';
}

MyeSolicitud.prototype = new General();

MyeSolicitud.prototype.checkUndef = function(texto) {
    return texto ? texto : '';
};


MyeSolicitud.prototype.crearInforme = function(formulario) {
    $("#tabla_solicitud").find("tr:gt(0)").remove();
    
    var args = $(formulario).serializeObject();
    args.requisito = new Array();
    
    $.each($('.chk_requisito:checked'), function (index, item) {
        args.requisito[$(item).data('name')] = 1;
    });
    $.ajax({
        url: this.makeUrl('crear_informe'),
        data: {
            ctrl: this.getInfo('crear_informe'),
            args: JSON.stringify(args)
        },
        dataType: 'json',
        success: function (respuesta) {
            console.log(respuesta);
            var texto_append = '';
            $.each(respuesta, function (index, item) {
                texto_append += '<tr><td>'+(item['id_solicitud'] ? item['id_solicitud'] : '' )+'</td>';
                texto_append += '<td>'+(item['escuela'] ? item['escuela'] : '' )+'</td>';
                texto_append += '<td>'+(item['municipio'] ? item['municipio'] : '' )+'</td>';
                texto_append += '<td>'+(item['director'] ? item['director']['nombre']+' '+ item['director']['apellido'] : '');
                texto_append += '<td>'+(item['director']['tel_movil'] ? item['director']['tel_movil'] : '' )+'</td>';
                texto_append += '<td>'+(item['cant_alumno'] ? item['cant_alumno'] : '' )+'</td>';
                texto_append += '</tr>';
            });
            $('#tabla_solicitud').append(texto_append);
        }
    });
};

MyeSolicitud.prototype.getInfo = function(action) {
    return {module: this.module, ctrl: this. ctrl, action: action};
};


$(document).ready(function () {
    var mye_solicitud = new MyeSolicitud(nivel_entrada);
    
    listar_campos_select('app/src/libs_gen/gn_departamento.php?fn_nombre=listar_departamento', 'departamento', 'vacio');
    $('#departamento').on('change', function () {
    var args = {'id_departamento': $(this).val()};
    listar_campos_select('app/src/libs_gen/gn_municipio.php?fn_nombre=listar_municipio&args='+JSON.stringify(args), 'municipio', 'vacio');
    })
    .trigger('change');
    
    $.getScript(nivel_entrada+'app/src/js-libs/general_datetime.js', function () {
        input_rango_fechas('fecha_inicio','fecha_fin');
    });
    
    $('#btn_informe_solicitud').on('click', function () {
        mye_solicitud.crearInforme($('#form_informe_solicitud'));
    });
});