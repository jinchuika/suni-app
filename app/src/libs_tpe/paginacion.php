<?php
require_once('../../includes/auth/Db.class.php');
require_once('../../includes/auth/Conf.class.php');

function paginacion($nombre_tabla, $cant_por_pagina)
{
	$bd = Db::getInstance();
	$query_cuenta = "select count(*) from ".$nombre_tabla." ";
	$stmt_cuenta = $bd->ejecutar($query_cuenta);
	$cuenta = $bd->obtener_fila($stmt_cuenta, 0);

	$num_paginas = ceil($cuenta[0]/$cant_por_pagina);
	$paginas = "";
	if($num_paginas >= 1){
		$paginas = '<div class="pagination pagination-small">
			<ul>';
		for ($i=0; $i < $num_paginas; $i++) { 
			$paginas .= '<li><a href="#" class="num_pagina" id="'.$i.'-'.$nombre_tabla.'">'.($i+1).'</a></li>
			';
		}
		$paginas .= '</ul>
		</div>';
	}
	return $paginas;
}
?>