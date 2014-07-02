(function ($) {
  

  /* Edición de grupo */
  $('.editar_grupo').editable({
    url: '../../src/libs/ca_editar_grupo.php?id_par=' + localStorage.id_par + '&id_asignacion=' + localStorage.id_asignacion + '&id_grupo=' + localStorage.id_grupo + '&id_curso=' + localStorage.id_curso,
    type: "post"
  });
})(jQuery);