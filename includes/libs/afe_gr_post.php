<?php
	$USR = $_POST['usr'];
	/*Regresa un valor JSON con el link al que se direccionará */
	echo json_encode(array('returned_val' => $USR));
	
?>