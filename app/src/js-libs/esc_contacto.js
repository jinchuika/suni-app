var arr_roles_contacto = [
{value: '4', text: 'Maestro'},
{value: '5', text: 'Director'},
{value: '6', text: 'Supervisor'},
{value: '7', text: 'Director departamental'},
{value: '8', text: 'Alumno'},
{value: '11', text: 'Personal administrativo'},
{value: '12', text: 'Personal de la escuela'}
];
/**
* Hace la solicitud del listado de TODOS los contactos asociados
* @param  {int} id_escuela El id de la escuela a la que se solicitan los datos
* @param  {string}  objetivo    el ID de la lista donde se agregan  los contactos
* @return {json}            [description]
*/
function listar_contacto_escuela (id_escuela, objetivo) {
    $('#'+objetivo).empty();
    $.getJSON( nivel_entrada+'app/src/libs_gen/esc_contacto.php', {
        fn_nombre: 'listar_contacto_escuela',
        args: JSON.stringify({id:id_escuela})
    })
    .done(function (resp) {
        $.each(resp, function (index, item) {
            render_contacto(item, objetivo);
        });
    });
}
/**
 * Abre un único contacto y lo agrega al final de una lista
 * @param  {int} id_contacto El ID para buscar
 * @param  {string} objetivo    la lista a la que se añade
 */
 function abrir_contacto_escuela (id_contacto, objetivo) {
    $.getJSON(nivel_entrada+'app/src/libs_gen/esc_contacto.php', {
        fn_nombre: 'abrir_contacto',
        args: JSON.stringify({id:id_contacto})
    })
    .done(function (resp) {
        render_contacto(resp, objetivo);
    });
}
/**
 * Crea la vista para la info del contacto
 * @param  {Array} contacto {id, id_persona, nombre, apellido, tel_movil, mail}
 * @param  {String} objetivo Una UL a la que se agregan items
 * @param  {Bool} editable Permite que la info de este contacto sea editable
 * @return {[type]}          [description]
 */
 function render_contacto (contacto, objetivo, editable) {
    $('#'+objetivo).append('<li id="li_contacto_'+contacto.id+'"></li>');
    var string_blockquote = '<blockquote class="well well-small" id="blck_'+contacto.id+'"></blockquote>';
    var string_rol = '<p><strong><span data-type="select" data-source=\''+JSON.stringify(arr_roles_contacto)+'\' data-name="id_rol" class="cnt_'+contacto.id+'">'+nullToEmpty(contacto.rol)+'</span></strong> - <i class="icon-edit" id="icon_edit_'+contacto.id+'" onclick="activar_edicion_contacto('+contacto.id+', \'cnt_'+contacto.id+'\');"></i>  <a href="tel:'+nullToEmpty(contacto.tel_movil)+'">[ <i class="icon-phone text-center"></i> ]</a></p>';
    var string_close = '<button class="close" onclick="eliminar_contacto_escuela('+contacto.id+');" type="button">×</button>';
    var string_nombre = '<small><strong>Nombre: </strong><span data-name="nombre" data-type="text" class="cnt_'+contacto.id+'">'+contacto.nombre+'</span> <span data-name="apellido" data-type="text" class="cnt_'+contacto.id+'">'+contacto.apellido+'</span> </small>';
    var string_tel = '<small><strong>Teléfono: </strong><span data-name="tel_movil" data-type="text" class="cnt_'+contacto.id+'">'+nullToEmpty(contacto.tel_movil)+'</span></small>';
    var string_mail = '<small><strong>Correo electrónico: </strong><span data-name="mail" data-type="text" class="cnt_'+contacto.id+'">'+nullToEmpty(contacto.mail)+'</span></small>';
    $('#li_contacto_'+contacto.id).append(string_blockquote);
    $('#blck_'+contacto.id).append(string_rol+string_close+string_nombre+string_tel+string_mail);
}
 /**
  * Habilita la x-editable para el contacto
  * @param  {int} pk         La ID que se le agrega al campo para enviar
  * @param  {string} clase_cnt  La clase en la que se basa para reconocer el contacto
  * @param  {int} desactivar Si es 1 desactiva la edicion
  */
  function activar_edicion_contacto (pk, clase_cnt, desactivar) {
    var datos_contacto = document.getElementsByClassName(clase_cnt);
    for (var i = datos_contacto.length - 1; i >= 0; i--) {
        var data_name = $(datos_contacto[i]).attr('data-name');
        if(desactivar==1){
            $('.'+clase_cnt+'_edit').contents().unwrap();
            $('#icon_edit_'+pk).attr('onclick', "activar_edicion_contacto("+pk+", '"+clase_cnt+"');");
        }
        else{
            datos_contacto[i].innerHTML = '<a href="#" data-type="'+$(datos_contacto[i]).attr('data-type')+'" class="'+clase_cnt+'_edit" data-name="'+data_name+'" data-pk="'+pk+'">'+$(datos_contacto[i]).text()+'</a>';
            $('#icon_edit_'+pk).attr('onclick', "activar_edicion_contacto("+pk+", '"+clase_cnt+"', 1);");
        }
    };
    $('.'+clase_cnt+'_edit').editable({
        url: nivel_entrada+'app/src/libs_gen/esc_contacto.php?fn_nombre=editar_contacto',
        mode: 'inline',
        source: arr_roles_contacto
    });
}
/**
 * Oculta o muestra el formulario para nuevo contacto
 * @param  {int} mostrar Si es 1 muestra, de lo contrario oculta
 */
 function nuevo_contacto (mostrar, objetivo) {
    document.getElementById('form_contacto').reset();
    if(mostrar==1){
        $('#'+objetivo).show();
    }
    else{
        $('#'+objetivo).hide();
    }
}
 /**
  * Solicita eliminar el contacto y remueve el elemento si la transacción se hace con éxito
  * @param  {int} id_contacto   el ID del contacto a eliminar
  * @return {bool}            Indica se la transacción se completó
  */
  
  function eliminar_contacto_escuela (id_contacto) {
    bootbox.confirm("¿Está seguro de querer eliminar el contacto?", function(result) {
        if(result===true){
            $.getJSON(nivel_entrada+'app/src/libs_gen/esc_contacto.php', {
                fn_nombre: 'eliminar_contacto',
                args: JSON.stringify({id:id_contacto})
            })
            .done(function (resp) {
                if(resp.msj=='si'){
                    $('#li_contacto_'+resp.id).remove();
                    return true;
                }
                else{
                    bootbox.alert("El contacto no se eliminó");
                }
            });
        }
    });
    return false;
}
/**
 * [activar_form_contacto description]
 * @param  {[type]} objetivo [description]
 * @return {[type]}          [description]
 */
 function activar_form_contacto (objetivo) {
    $.each(arr_roles_contacto, function (index, item) {
        $('#inp_rol_cnt').append('<option value="'+item['value']+'">'+item.text+'</option>');
    });
    $('#'+objetivo).submit(function (e) {
        e.preventDefault();
        modal_c.mostrar();
        $.ajax({
            url: nivel_entrada+'app/src/libs_gen/esc_contacto.php',
            data: {
                fn_nombre: 'crear_contacto',
                args: JSON.stringify($('#'+objetivo).serializeObject())
            },
            dataType: 'json',
            success: function (data) {
                if(data.msj=='si'){
                    var id_contacto = $.parseJSON(data.contacto);
                    abrir_contacto_escuela((id_contacto['id']), 'lista_contacto');
                }
                modal_c.ocultar();
            }
        });
        nuevo_contacto(false, objetivo);
    });
}