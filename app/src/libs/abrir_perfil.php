<?php

$id_usr = $_POST['busqueda'];
$id_usr2 = $_GET['id'];

/* En caso de haber sido llamado desde la página de búsqueda*/
if(isset($id_usr)){
	if(is_numeric($id_usr)){
		header("Location: ../../usr/perfil.php?id_per=".$id_usr."");
	}
	else{
		header("Location: ../../usr/perfil.php?id_usr=".$id_usr."");
	}
}
/* En caso de ser una redirección genérica con la variable "id" en la url */
elseif (isset($id_usr2)) {
	if(is_numeric($id_usr2)){
		header("Location: ../../usr/perfil.php?id_per=".$id_usr2."");
	}
	else{
		header("Location: ../../usr/perfil.php?id_usr=".$id_usr2."");
	}
}

?>