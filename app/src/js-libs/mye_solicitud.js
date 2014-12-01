function cargar_tabla () {
    var args = $('#form_informe_solicitud').serializeObject();
    args.requisito = new Array();
    $.each($('.chk_requisito:checked'), function (index, item) {
        args.requisito[$(item).data('name')] = 1;
    });
    console.log(args);
}
$(document).ready(function () {
    listar_campos_select('app/src/libs_gen/gn_departamento.php?fn_nombre=listar_departamento', 'departamento', 'vacio');
    $('#departamento').on('change', function () {
        var args = {'id_departamento': $(this).val()};
        listar_campos_select('app/src/libs_gen/gn_municipio.php?fn_nombre=listar_municipio&args='+JSON.stringify(args), 'municipio', 'vacio');
    })
    .trigger('change');

    $.getScript(nivel_entrada+'app/src/js-libs/general_datetime.js', function () {
        input_rango_fechas('fecha_inicio','fecha_fin');
    });
    
});