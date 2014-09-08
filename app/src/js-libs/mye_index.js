/**
 * Limpia todos los campos de la solicitud
 */
function reiniciar_solicitud () {
  $('#info_gen_escuela').hide();
  $('.dato_esc').remove();
}

/**
 * Abre los datos primordiales de la escuela
 * @param  {string} udi_escuela el UDI para buscar
 * @return {int}             el ID de la escuela en la base de datos
 */
function abrir_datos_escuela (udi_escuela) {
  reiniciar_solicitud();
  $.getJSON(nivel_entrada+'app/src/libs_gen/gn_escuela.php', {
    fn_nombre: 'abrir_escuela',
    args: JSON.stringify({
      udi: udi_escuela,
      cyd: false
    })
  })
  .done(function (data) {
    var arr_spn_escuela = document.getElementsByClassName('snp_escuela');
    $.each(arr_spn_escuela, function (index, item) {
      var campo_temp = $(item).data('campo');
      $(item).append('<a class="dato_esc" href="#">'+data[campo_temp]+'</a>');
    });
    $('#info_gen_escuela').show();
    listar_contacto_escuela(data.id,'lista_contactos');
  });
  return data.id;
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
 * Abre la solicitud desde la base de datos
 * @param  {int} id_solicitud ID del registro en la base de datos
 * @uses abrir_requisito()
 * @uses abrir_medio()
 * @uses  abrir_edf()
 * @return {bool}              Informa el estado
 */
function abrir_solicitud (id_solicitud) {
  var estado_op = false;
  return estado_op;
}

/**
 * Carga los requisitos desde el servidor
 * @param  {int} id_requisito ID del registro en la base de datos
 * @return {bool}              Informe sobre éxito en operación
 */
function abrir_requisito (id_requisito) {
  var estado_op = false;
  return estado_op;
}

/**
 * Carga los medios desde el servidor
 * @param  {int} id_medio ID del registro en la base de datos
 * @return {bool}              Informe sobre éxito en operación
 */
function abrir_medio (id_medio) {
  var estado_op = false;
  return estado_op;
}

/**
 * Carga los datos sobre EDF desde el servidor
 * @param  {int} id_edf ID del registro en la base de datos
 * @return {bool}              Informe sobre éxito en operación
 */
function abrir_edf (id_edf) {
  var estado_op = false;
  return estado_op;
}