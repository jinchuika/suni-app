function input_rango_fechas (input_inicio, input_fin) {
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

	var input_fecha_inicio = $('#'+input_inicio).datepicker({
		format: "dd/mm/yyyy",
		todayBtn: true,
		language: "es",
		autoclose: true,
		endDate: '+1d',
		onRender: function(date) {
			return date.valueOf() < now.date.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev) {
		if (ev.date.valueOf() <= input_fecha_fin.date.valueOf()) {
			var newDate = new Date(ev.date);
			newDate.setDate(newDate.getDate());
			input_fecha_fin.setStartDate(input_fecha_inicio.getDate());
			console.log(input_fecha_inicio.getDate());
		}
		input_fecha_inicio.hide();
		$('#'+input_fin)[0].focus();
	}).data('datepicker');
	var input_fecha_fin = $('#'+input_fin).datepicker({
		format: "dd/mm/yyyy",
		todayBtn: true,
		language: "es",
		autoclose: true,
		endDate: '+1d',
		onRender: function(date) {
			return date.valueOf() <= input_fecha_inicio.date.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev) {
		input_fecha_fin.hide();
	}).data('datepicker');
}