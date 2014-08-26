/**
 * Crea un listado con filtro a partir de un ul
 * @param  {string} lista_objetivo       el ID de la lista a crear
 * @param  {string} input_buscador       el ID del input para crear el buscador
 * @param  {string} url_listar     description
 * @param  {string} onclick_accion la funcion que asignada al onclick
 * @param  {string || object} campo_mostrado          Campo a mostrar en el texto, si es objeto concatena
 * @param  {string} p_accion       parametros extra para que realice la funcion adjunta
 * @param  {string} plantilla_id=listado      plantilla para el ID de los elementos de la lista
 */
function fn_listar (lista_objetivo, input_buscador, url_listar, onclick_accion, campo_mostrado, p_accion, plantilla_id) {
  /**
   * Compone los métodos 
   * @param  {Object || String} campo Los campos que se van a mostrar
   * @return {String}       Campos que se muestran en la lista
   */
  function f_campo_mostrado (campo) {
    var resp = '';
    if(campo_mostrado instanceof Object){
      for (var i in campo_mostrado) {
        if(campo.hasOwnProperty(i)){
          resp += campo[campo_mostrado[i]]+' ';
        }
      };
    }
    else{
      return campo[campo_mostrado];
    }
    return resp;
  }
  var contenedor_lista = document.getElementById(lista_objetivo).parentNode;
  plantilla_id = typeof plantilla_id !== 'undefined' ? plantilla_id : 'listado';
  $("#"+lista_objetivo).empty();
  if(!($('#barra_carga_'+lista_objetivo).val())){
    $(contenedor_lista).append('<div id="barra_carga_'+lista_objetivo+'" class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div>');
  }
  else{
    $('#barra_carga_'+lista_objetivo).show();
  }
  
  $.ajax({
    url: nivel_entrada+url_listar,
    success: function (data) {
      var data = $.parseJSON(data);
      $.each(data, function (index, item) {
        /* Cada item que regresa debe ser un array de modo
        array(_id, _texto)*/
        var entry = document.createElement('li');
        entry.innerHTML = "<a id='a_"+plantilla_id+"_"+item[0]+"' href='#' onclick='"+onclick_accion+"("+item[0]+(p_accion ? p_accion : '')+");'>"+(campo_mostrado ? f_campo_mostrado(item) : item[1])+"</a>";
        entry.id = 'li_listado_'+item[0];
        document.getElementById(lista_objetivo).appendChild(entry);
        $('#barra_carga_'+lista_objetivo).remove();
      });
      /**
       * Asigna al input designado en input_buscador como buscador para la lista
       */
      if(input_buscador){
        $('#'+input_buscador).filtrar_lista_ul('#'+lista_objetivo,
        {
          callback: function(total) { 
            var contador_lista = document.getElementById("contador_lista");
            if(contador_lista!==null){
              contador_lista.innerHTML="Mostrando "+total+" elementos";
            }
          }
        }
        );
      }
      $('#'+lista_objetivo).attr('class', 'nav nav-list lista_filtrada');
    }

  });
}

function insertar_item_listado (objetivo, onclick_accion, id, texto) {
  var entry = document.createElement('li');
  entry.innerHTML = "<a id='a_listado_"+id+"' href='#' onclick='"+onclick_accion+"("+id+");'>"+texto+"</a>";
  entry.id = 'li_listado_'+id;
  document.getElementById(objetivo).appendChild(entry);
}

function cambiar_nombre_listado (id, nombre) {
  document.getElementById("a_listado_"+id).innerHTML = nombre;
}

function listar_campos_select (src_remote, objetivo, vacio) {
  $("#"+objetivo).find('option').remove();
  $.ajax({
    url: nivel_entrada+src_remote,
    success: function (data) {
      var data = $.parseJSON(data);
      var resultado = vacio ? '<option value=""></option>' : '';
      $.each(data, function (index, item) {
        resultado += '<option value="'+item[0]+'">'+item[1]+'</option>';
      });
      $("#"+objetivo).html(resultado);
    }
  });
}

function llenar_select2 (objetivo, src_remote, campo) {
  function format_select2(item) { 
    return item[campo];
  }
  $.ajax({
    url: nivel_entrada+src_remote,
    success: function (datos) {
      var datos = $.parseJSON(datos);
      $("#"+objetivo).select2({
        data:{ results: datos, text: campo},
        allowClear: true,
        placeholder: 'Seleccione...',
        formatSelection: format_select2,
        formatResult: format_select2
      });
    }
  });
}