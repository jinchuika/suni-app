var js_general=document.getElementById("js_general");
var id_per_entrada=js_general.getAttribute("id_per");
var id_rol_entrada=js_general.getAttribute("id_rol");
var nivel_entrada=js_general.getAttribute("nivel");
function ventana_seleccion (mensaje, elemento) {
  window.prompt (mensaje, elemento);
}
function crear_filtro_grupo (id_objetivo) {
  $(id_objetivo).append('<label for="id_sede">Sede: </label><input id="id_sede" required="required"><label for="id_curso">Curso: </label><input id="id_curso"><label for="id_grupo">Grupo: </label><select name="id_grupo" id="id_grupo"></select><img src="http://funsepa.net/suni/js/framework/select2/select2-spinner.gif" class="hide" id="loading_gif"><input type="button" id="boton_busqueda_grupo" value="Seleccionar" class="btn btn-primary">');

}

/* Clase para mostrar barra de carga en la parte inferior de la pantalla */
function barra_carga_inf (texto, id_html){
  if(!id_html){
    id_html = "barra_carga_inf";
  }
  var contenido_div = '<div id="'+id_html+'" class="progress progress-striped active hide "><div class="bar" style="width: 100%">'+(texto ? texto : '')+'</div></div>';
  this.crear = function() {
    
    if(document.getElementById(id_html)==null){
      document.body.innerHTML += contenido_div;
    }
    document.getElementById(id_html).setAttribute("class", "progress progress-striped active hide navbar navbar-default navbar-fixed-bottom");
  }

  this.mostrar = function(duracion) {
    $("#"+id_html+"").show(duracion);
  }
  this.ocultar = function (duracion) {
    $("#"+id_html+"").hide(duracion);
  }
  return this;
};

function modal_carga_gn2 (id_html) {
  if(!id_html){
    id_html = "area_modal_carga";
  }
  var id_html = id_html;
  this.crear = function () {
    $.getScript('http://funsepa.net/suni/app/src/js-libs/general_variables.js')
    .done(function () {
      document.body.innerHTML += var_area_modal_carga(id_html);
    });
  }
  this.mostrar = function() {
    $("#"+nombre+"").modal('hide');
  }
  this.ocultar = function () {
    $("#"+nombre+"").modal('show');;
  }
  return this;
}

function modal_carga_gn (nombre) {
  if(!nombre){
    nombre = "area_modal_carga";
  }
  var modal_carga = bootbox.dialog("<h3>Procesando <img src='"+nivel_entrada+"js/framework/select2/select2-spinner.gif'></h3><p>Por favor espere...</p>");
  this.crear = function () {
    modal_carga.modal('hide');
    return this;
  }
  this.mostrar = function() {
    modal_carga.modal('show');
  }
  this.ocultar = function () {
    modal_carga.modal('hide');
  }
  return this;
}

/* Función para descargar una tabla, recibe como parámetro el ID de la tabla */
function descargar_tabla_excel (nombre, mail_para, mail_asunto, mail_body) {
  if(mail_para){
    $.getScript('http://funsepa.net/suni/app/src/js-libs/general_variables.js').done(function function_name (argument) {
      bootbox.confirm(var_formulario_excel_correo(), function (result) {
        if(result===true){
          in_descargar_tabla_excel();
          mail_para=$("#mail_para").val();
          mail_asunto=$("#mail_asunto").val();
          mail_body=$("#mail_body").val();
        }
      });
      
    });
  }
  else{
   in_descargar_tabla_excel(); 
 }
 function in_descargar_tabla_excel () {

  $.getScript('http://funsepa.net/suni/app/src/js-libs/general_variables.js')
  .done(function () {
    var url_excel = '', mail_valido=0;
    if(mail_para){
      if(validar_mail(mail_para)){
        url_excel = 'app/src/libs/crear_reporte_excel.php?correo=1&id_persona='+id_per_entrada;
        if(mail_asunto){
          url_excel += "&mail_asunto="+mail_asunto;
        }
        if(mail_body){
          url_excel += "&mail_body="+mail_body;
        }
        mail_valido = 1;
      }
    }
    else{
      url_excel = 'app/src/libs/crear_reporte_excel.php?descargar=1';
    }

    var barra_carga_excel = new barra_carga_inf(); /* Mostrar la barra inferior */
    barra_carga_excel.crear();
    barra_carga_excel.mostrar();

    var id_form_temp = nombre + "_formulario_temp"; /* el nombre del formulario temporal a crear */

    document.body.innerHTML += var_formulario_excel(id_form_temp, mail_para);
    if(mail_valido==1){
      $("#"+id_form_temp).append('<input type="text" id="dir_mail" required="required" name="dir_mail">');
      $("#dir_mail").attr('value', mail_para);
    }
    var tabla = setTimeout(function () {
      $("#datos_excel").val( $("<div>").append( $("#"+nombre).eq(0).clone()).html());
      $("#"+id_form_temp).attr('action', nivel_entrada+url_excel);
      $("#"+id_form_temp).submit();
    }, 1000);
    setTimeout(function () {
      $("#"+id_form_temp).remove();
      location.reload();
      barra_carga_excel.ocultar();
    }, 5000);
  });
}
}

function validar_mail(mail)
{
  var verif = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,3}$/
  if (verif.exec(mail) == null)
  {
    return false;
  }
  else
  {
    return true;
  }
}

/**
 * Convierte el primer caracter en mayúscula usando .capitalize
 * @return string Cadena con primer caracter en mayúscula
 */
 function cap_first(string)
 {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

/**
 * Cambiar los valores escritos como 'null' por ''
 * @param  {string} string el string a convertir
 * @return {string}        Valor cambiado por '' o el original
 */
 function nullToEmpty(string){
  if(string==='null' || string==null){
    return '';
  }
  else{
    return string;
  }
}

(function($) {
  $.fn.goTo = function() {
    $('html, body').animate({
      scrollTop: $(this).offset().top + 'px'
    }, 'fast');
        return this; // for chaining...
      }
    })(jQuery);

/**
 * Convertir campos de un formulario a un array sociativo
 * @return object Objeto de datos asociativo
 */
 $.fn.serializeObject = function()
 {
  var o = {};
  var a = this.serializeArray();
  $.each(a, function() {
    if (o[this.name] !== undefined) {
      if (!o[this.name].push) {
        o[this.name] = [o[this.name]];
      }
      o[this.name].push(this.value || '');
    } else {
      o[this.name] = this.value || '';
    }
  });
  return o;
};

function General() {
  this.nivel_creado = nivel_entrada;
  this.module = '';
  this.ctrl = '';
}

General.prototype.makeUrl = function(action, notExecute) {
  console.log('nivel:'+this.nivel_creado);
  var url = this.nivel_creado+'app/src/libs'+ this.module+'/'+this.ctrl+'.php';
  url += (notExecute ? '' : '?fn_nombre='+action);
  return url;
};