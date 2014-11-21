$(function() { //ready
	$.fn.editable.defaults.mode = 'inline';
	$('#dpi').editable({
	    type: 'text',
	    url: '../../app/src/libs/editar_persona.php',
	    //pk: <?php echo $id_per;?>,
	    title: 'Cambiar DPI'
	});
	$('#nombre').editable({
	    type: 'text',
	    url: '../../app/src/libs/editar_persona.php',
	    //pk: <?php echo $id_per;?>,
	    name: 'nombre',
	    title: 'Cambiar nombre'
	});
	$('#apellido').editable({
	    type: 'text',
	    url: '../../app/src/libs/editar_persona.php',
	    //pk: <?php echo $id_per;?>,
	    title: 'Cambiar nombre'
	});
	$('#fecha_nac').editable({
	    type:  'date',
	    url: '../../app/src/libs/editar_persona.php',
	    //pk: <?php echo $id_per;?>,
	    title: 'Editar la fecha de nacimiento'
	});
	$('#direccion').editable({
	    type: 'text',
	    url: '../../app/src/libs/editar_persona.php',
	    //pk: <?php echo $id_per;?>,
	    title: 'Editar la direccion'
	});
	$('#mail').editable({
	    type: 'text',
	    url: '../../app/src/libs/editar_persona.php',
	    //pk: <?php echo $id_per;?>,
	    title: 'Editar el email'
	});
	$('#tel_casa').editable({
	    type: 'text',
	    url: '../../app/src/libs/editar_persona.php',
	    //pk: <?php echo $id_per;?>,
	    title: 'Editar telefono1'
	});
	$('#tel_movil').editable({
	    type: 'text',
	    url: '../../app/src/libs/editar_persona.php',
	    //pk: <?php echo $id_per;?>,
	    title: 'Editar telefono2'
	});						
});