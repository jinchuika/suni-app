/**
 * Limpia todos los campos de la solicitud
 */
 function reiniciar_solicitud () {
  $('#form_udi').show();
  $('.dato_esc').remove();
  $('#info_gen_escuela').hide();
  $('#div_header_solicitud').hide();
  $('#div_contacto').hide();
  $('#div_edf').hide();
  $('#div_poblacion').hide();
  $('#div_req').hide();
  $('#div_medio').hide();
  $('#div_obs').hide();
  $('#contenedor_form').children().unbind();

  $('#lista_contactos').empty();
  $('.cnt_solicitud').remove();
  $('#form_udi').goTo();

}

if(!modal_c){
  var modal_c = modal_carga_gn();
  modal_c.crear();
}

/**
 * Abre los datos primordiales de la escuela
 * @param  {string} udi_escuela el UDI para buscar
 * @return {int}             el ID de la escuela en la base de datos
 */
 function abrir_datos_escuela (udi_escuela) {
  modal_c.mostrar();
  reiniciar_solicitud();
  $.getJSON(nivel_entrada+'app/src/libs_gen/gn_escuela.php', {
    fn_nombre: 'abrir_escuela',
    args: JSON.stringify({
      udi: udi_escuela,
      cyd: false
    })
  })
  .done(function (resp_escuela) {
    var arr_spn_escuela = document.getElementsByClassName('snp_escuela');
    $.each(arr_spn_escuela, function (index, item) {
      var campo_temp = $(item).data('campo');
      $(item).append('<a data-mode="inline" data-name="'+campo_temp+'" data-url="'+nivel_entrada+'app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" class="dato_esc dato_esc_'+campo_temp+'" href="#">'+resp_escuela[campo_temp]+'</a>');
    });
    $('.dato_esc_nombre').attr('href', nivel_entrada+'app/esc/perfil.php?id='+resp_escuela.id);
    $('.dato_esc_nombre').attr('target', '_blank');
    $('.dato_esc_direccion, .dato_esc_mail, .dato_esc_telefono').editable({
      type: 'text',
      pk: resp_escuela.id
    });
    $('.dato_esc_jornada').editable({
      type: 'select',
      source: nivel_entrada+'app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=jornada',
      pk: resp_escuela.id
    });
    $('.dato_esc_etnia').editable({
      type: 'select',
      name: 'id_etnia',
      source: nivel_entrada+'app/src/libs_gen/pr_etnia.php?fn_nombre=listar_etnia&args="{editable:1}"',
      pk: resp_escuela.id
    });
    $('#info_gen_escuela').show();
    $('#btn_nueva_solicitud').attr('onclick', 'buscar_proceso('+resp_escuela.id+', true,1);');
    $('#btn_abrir_solicitud').attr('onclick', 'abrir_solicitud("","",'+resp_escuela.id+');');
    $('#inp_id_escuela_cnt').val(resp_escuela.id);
    modal_c.ocultar();
    return resp_escuela.id;
  });
}

/**
 * Habilita un formulario como buscador de UDI
 * @param  {string} objetivo     El ID del formulario
 * @param  {string} inp_udi_form El ID del buscador dentro del formulario
 */
 function activar_form_udi (objetivo, inp_udi_form) {
  $('#'+objetivo).submit(function (e) {
    e.preventDefault();
    abrir_datos_escuela($('#'+inp_udi_form).val());
  });
};

/**
 * Busca un proceso en base al ID de la escuela
 * @param  {int} id_escuela el ID de la escuela, NO es el udi
 * @param  {bool} nuevo_form Indica si la función fue usada para crear un nuevo formulario
 * @param  {int} tipo_form  Indica el tipo de formulario
 *                            1 es para solicitud
 *                            2 es para validación
 * @return {array}            el proceso
 */
 function buscar_proceso (id_escuela, nuevo_form, tipo_form) {
  $.getJSON(nivel_entrada+'app/src/libs_gen/gn_proceso.php', {
    fn_nombre: 'abrir_proceso',
    args: JSON.stringify({
      id_escuela: id_escuela
    })
  })
  .done(function (data) {
    /**
     * Para crear una nueva solicitud
     * @type {int}
     */
    // si la escuela no tiene un proceso
    if(data.msj=='no'){
      if(nuevo_form && tipo_form=='1'){
        /**
         * Crear el proceso y una nueva solicitud
         * @param  {int} id_escuela el ID para el que se va a crear el proceso
         * @param  {Function} crear_solicitud() crea una solicitud en el CALLBACK de de crear_proceso()
         */
         crear_proceso(id_escuela, function (id_proceso_callback) {
          crear_solicitud(id_proceso_callback, function (id_solicitud_callback) {
            abrir_solicitud(id_solicitud_callback);
          });
        });
       }
     }
    // si la escuela ya tiene un proceso
    if(data.msj=='si'){
      if(nuevo_form && tipo_form=='1'){
        bootbox.confirm('Esta escuela ya envió una solicitud; ¿desea crear otra?', function (result) {
          if(result==true){
            /**
             * Crear el una nueva solicitud y la abre
             * @param  {int} data.id el ID del proceso al que se crea la solicitud
             * @param  {Function} abrir_solicitud() abre una solicitud en el CALLBACK de de crear_solicitud()
             */
             crear_solicitud(data.id, function (id_solicitud_callback) {
              abrir_solicitud(id_solicitud_callback);
            });
           }
           else{

           }
         });
      }
    }

  });
}

/**
 * Crea un nuevo proceso
 * @param  {int}   id_escuela el ID de la escuela donde se crea el nuevo proceso
 * @param  {Function} callback   La funcion a ejectuar tras terminar utilizando el id del proceso nuevo
 * @return {[type]}              [description]
 */
 function crear_proceso (id_escuela, callback) {
  modal_c.mostrar();
  $.getJSON(nivel_entrada+'app/src/libs_gen/gn_proceso.php', {
    fn_nombre: 'crear_proceso',
    args: JSON.stringify({
      id_escuela: id_escuela
    })
  })
  .done(function (data) {
    if(data.msj == 'si'){
      if(callback && typeof(callback) === "function") {  
        callback(data.id);  
      }
    }
    else{
      bootbox.alert('Error al crear el proceso');
    }
    modal_c.ocultar();
  });
  
}

function crear_solicitud (id_proceso, callback) {
  modal_c.mostrar();
  $.getJSON(nivel_entrada+'app/src/libs_me/me_solicitud.php', {
    fn_nombre: 'crear_solicitud',
    args: JSON.stringify({
      id_proceso: id_proceso
    })
  })
  .done(function (data) {
    if(data.id){
      if(callback && typeof(callback) === "function") {  
        callback(data.id);
      }
    }
    else{
      bootbox.alert('Error al crear la solicitud');
    }
    modal_c.ocultar();
  });
}

/**
 * Abre la solicitud desde la base de datos
 * @param  {int} id_solicitud ID del registro en la base de datos
 * @uses abrir_contactos_solicitud()
 * @uses abrir_requisito()
 * @uses abrir_medio()
 * @uses  abrir_edf()
 * @return {bool}              Informa el estado
 */
 function abrir_solicitud (id_solicitud, id_proceso, id_escuela, callback) {
  modal_c.mostrar();
  $.getJSON(nivel_entrada+'app/src/libs_me/me_solicitud.php', {
    fn_nombre: 'abrir_solicitud',
    args: JSON.stringify({
      id_solicitud: id_solicitud,
      id_proceso: id_proceso,
      id_escuela: id_escuela
    })
  })
  .done(function (solicitud) {
    if(solicitud.id_solicitud){
      $('#spn_id_solicitud').text(solicitud.id_solicitud);
      $('#spn_fecha_solicitud').html('<a data-type="date" id="a_fecha_solicitud" href="#">'+solicitud.fecha+'</a>');
      $('#spn_jornadas').html('<a class="dato_solicitud" data-type="number" data-name="jornadas" href="#">'+solicitud.jornadas+'</a>');
      $('#spn_obs_solicitud').html('<a class="dato_solicitud" data-type="textarea" data-name="obs" href="#">'+nullToEmpty(solicitud.obs)+'</a>');
      $('#chk_lab_actual').prop('checked', (solicitud.lab_actual == "1" ? true : false))
      .unbind().bind('change', function () {
        var accion = ($(this).is(':checked') ? "1" : "0");
        $.post(nivel_entrada+'app/src/libs_me/me_solicitud.php', {
          pk: solicitud.id_solicitud,
          name: 'lab_actual',
          value: accion,
          fn_nombre: 'editar_solicitud'
        });
      });
      $('#div_header_solicitud').show();
      $('#div_obs').show();
      $('.dato_solicitud').editable({
        pk: solicitud.id_solicitud,
        url: nivel_entrada+'app/src/libs_me/me_solicitud.php?fn_nombre=editar_solicitud',
        mode: 'inline'
      });
      $('#a_fecha_solicitud').editable({
        format: 'yyyy-mm-dd',
        pk: solicitud.id_solicitud,
        name: 'fecha',
        url: nivel_entrada+'app/src/libs_me/me_solicitud.php?fn_nombre=editar_solicitud',
        datepicker: {
          firstDay: 1,
          language: 'es'
        },
        mode: 'inline'
      });


      abrir_edf(solicitud.id_edf);
      abrir_contactos_solicitud(solicitud.id_solicitud);
      abrir_poblacion(solicitud.id_poblacion);
      abrir_requisito(solicitud.id_requisito);
      abrir_medio(solicitud.id_medio);
      $('#spn_id_solicitud').goTo();

      if(callback && typeof(callback) === "function") {  
        callback(solicitud.id);
      }
    }
    else{
      bootbox.alert('Error al abrir la solicitud');
    }
    modal_c.ocultar();
  });

}


function abrir_contactos_solicitud (id_solicitud) {
  $.getJSON(nivel_entrada+'app/src/libs_me/me_solicitud.php', {
    fn_nombre: 'abrir_contactos_solicitud',
    args: JSON.stringify({
      id_solicitud: id_solicitud
    })
  })
  .done(function (arr_contactos) {
    if(arr_contactos){
      $.each(arr_contactos, function (rol_contacto, datos_contacto) {
        $('#td_'+rol_contacto).html('<a data-type="select" data-name="id_'+rol_contacto+'" class="cnt_solicitud" href="#">'+(datos_contacto ? nullToEmpty(datos_contacto['nombre']) : '')+'</a>');
        $('#td_'+rol_contacto).append('<button data-content=" " data-idcontacto='+datos_contacto.id+' data-name="'+datos_contacto['id']+'" class="btn btn-mini btn-info btn_cnt pull-left">Ver</button>');
      });      
      $('#div_contacto').show();
      $('.cnt_solicitud').editable({
        mode: 'inline',
        source: nivel_entrada+'app/src/libs_me/me_solicitud.php?fn_nombre=listar_contacto_solicitud&args='+JSON.stringify({id_solicitud:id_solicitud})+'',
        url : nivel_entrada + 'app/src/libs_me/me_solicitud.php?fn_nombre=editar_contacto_solicitud',
        pk: id_solicitud,
        sourceCache: false,
        type: 'select',
        error: function(response, newValue) {
          if(response.status === 500) {
            return "Incorrecto: "+response.statusText;
          }
          else {
            return response.responseText;
          }
        }
      });
      var $popover = $('.btn_cnt').popover({
        html: true,
        placement: 'bottom',
        content: function () {
          var div_id =  "popover" + $(this).data('idcontacto');
          return '<div id="'+ div_id +'">cargando...</div>';
        }
      });
      $popover.unbind().bind("shown", function(e) {
        abrir_contacto_escuela($(this).data('idcontacto'), "popover" + $(this).data('idcontacto'), true);
      });
      $('body').unbind().bind('click', function (e) {
        if ($(e.target).data('toggle') !== 'popover'
          && $(e.target).parents('.popover.in').length === 0) { 
          $('[data-toggle="popover"]').popover('hide');
      }
    });
    }
    else{

    }
  });
  /*listar_contacto_escuela(id_escuela,'lista_contactos', function (id_contacto) {
    console.log('render para id: '+id_contacto);
    $('#blck_'+id_contacto).append('<small><button class="btn btn-mini btn-danger span12" data-idcontacto="'+id_contacto+'">Utilizar</button></small>');
  });*/
}

function abrir_poblacion (id_poblacion) {
  $.getJSON(nivel_entrada+'app/src/libs_me/me_poblacion.php', {
    fn_nombre: 'abrir_poblacion',
    args: JSON.stringify({
      id_poblacion: id_poblacion
    })
  })
  .done(function (poblacion) {
    if(poblacion){
      $('#td_alum_mujer').html('<a class="me_poblacion" data-name="alum_mujer" href="#">'+poblacion.alum_mujer+'</a>');
      $('#td_alum_hombre').html('<a class="me_poblacion" data-name="alum_hombre" href="#">'+poblacion.alum_hombre+'</a>');
      $('#td_maestro_mujer').html('<a class="me_poblacion" data-name="maestro_mujer" href="#">'+poblacion.maestro_mujer+'</a>');
      $('#td_maestro_hombre').html('<a class="me_poblacion" data-name="maestro_hombre" href="#">'+poblacion.maestro_hombre+'</a>');
      $('#div_poblacion').show();
      $('.me_poblacion').editable({
        url: nivel_entrada + 'app/src/libs_me/me_poblacion.php?fn_nombre=editar_poblacion',
        pk: poblacion.id
      });
    }
    else{
      bootbox.alert('Error al abrir la solicitud');
    }
    modal_c.ocultar();
  });
}

/**
 * Carga los requisitos desde el servidor
 * @param  {int} id_requisito ID del registro en la base de datos
 * @return {bool}              Informe sobre éxito en operación
 */
 function abrir_requisito (id_requisito) {
  $.getJSON(nivel_entrada+'app/src/libs_me/me_requisito.php', {
    fn_nombre: 'abrir_requisito',
    args: JSON.stringify({
      id_requisito: id_requisito
    })
  })
  .done(function (requisito) {
    $.each(requisito, function (index, item) {
      $('#chk_requisito'+index).val(item);
      $('#chk_requisito_'+index).prop('checked', ((item == "1") ? true : false));
    });
    $('.chk_requisito').unbind();
    $('.chk_requisito').unbind().bind('change', function () {
      var accion = ($(this).is(':checked') ? "1" : "0");
      $.post(nivel_entrada+'app/src/libs_me/me_requisito.php', {
        pk: id_requisito,
        name: $(this).data('name'),
        value: accion,
        fn_nombre: 'editar_requisito'
      });
    });
    $('#div_req').show();
    modal_c.ocultar();
  });
}

/**
 * Carga los medios desde el servidor
 * @param  {int} id_medio ID del registro en la base de datos
 * @return {bool}              Informe sobre éxito en operación
 */
 function abrir_medio (id_medio) {
  $.getJSON(nivel_entrada+'app/src/libs_me/me_medio.php', {
    fn_nombre: 'abrir_medio',
    args: JSON.stringify({
      id_medio: id_medio
    })
  })
  .done(function (medio) {
    $.each(medio, function (index, item) {
      $('#chk_medio'+index).val(item);
      $('#chk_medio_'+index).prop('checked', ((item == "1") ? true : false));
    });
    $('.chk_medio').unbind().bind('change', function () {
      var accion = ($(this).is(':checked') ? "1" : "0");
      $.post(nivel_entrada+'app/src/libs_me/me_medio.php', {
        pk: id_medio,
        name: $(this).data('name'),
        value: accion,
        fn_nombre: 'editar_medio'
      });
    });
    $('#div_medio').show();
    modal_c.ocultar();
  });
}

/**
 * Carga los datos sobre EDF desde el servidor
 * @param  {int} id_edf ID del registro en la base de datos
 * @return {bool}              Informe sobre éxito en operación
 */
 function abrir_edf (id_edf) {
  $.getJSON(nivel_entrada+'app/src/libs_me/me_edf.php', {
    fn_nombre: 'abrir_edf',
    args: JSON.stringify({
      id_edf: id_edf
    })
  })
  .done(function (edf) {
    $.each(edf, function (index, item) {
      $('#chk_edf'+index).val(item);
      $('#chk_edf_'+index).prop('checked', ((item == "1") ? true : false));
    });
    $('#spn_edf_fecha').html('<a class="edf_editable" data-name="fecha" href="#">'+edf.fecha+'</a>');
    $('#spn_edf_nivel').html('<a class="edf_editable" data-name="nivel" href="#">'+edf.nivel+'</a>');
    $('.chk_edf').unbind().bind('change', function () {
      var accion = ($(this).is(':checked') ? "1" : "0");
      $.post(nivel_entrada+'app/src/libs_me/me_edf.php', {
        pk: id_edf,
        name: $(this).data('name'),
        value: accion,
        fn_nombre: 'editar_edf'
      });
    });
    $('.edf_editable').editable({
      pk: id_edf,
      url: nivel_entrada+'app/src/libs_me/me_edf.php?fn_nombre=editar_edf'
    })
    $('#div_edf').show();
    modal_c.ocultar();
  });
}