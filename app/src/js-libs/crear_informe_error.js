var url_error = "http://funsepa.net/suni/app/gen/nuevo_error.php";
var id_per = informe_error_js.getAttribute("id_per");
function fn_enviar_informe_error () {
  var mensaje_error = $('iframe[src="'+url_error+'"]').contents().find("#mensaje_informe_error").val();
  var tipo_error = $('iframe[src="'+url_error+'"]').contents().find('input[name=tipo_error_informe]:checked', '#formulario_errores').val();
  $.ajax({
    url: 'http://funsepa.net/suni/app/src/libs/crear_informe_error.php',
    type: 'post',
    dataType: 'json',
    data: {
      mensaje_error: mensaje_error,
      id_per: id_per,
      lugar: document.URL,
      tipo_error: tipo_error
    }
  });
  alert("Gracias por su comentario");
  $("#modal_error").modal('hide');
}
$("#boton_ayuda").click(function () {
  $("#modal_footer_error").html('<input class="btn btn-primary" id="enviar_informe_error" value="Enviar" onClick="fn_enviar_informe_error();" type="button"><a href="#" class="btn" data-dismiss="modal">Cancelar</a>');
  $('#modal_error_cuerpo').html('<iframe id="iframe_error" style="z-index: 1500;" width="100%" height="500px" frameborder="0" scrolling="no" allowtransparency="true" src="'+url_error+'"></iframe>');
  $("#modal_error").modal();
  $("#modal_error").css("z-index", "1500");
});