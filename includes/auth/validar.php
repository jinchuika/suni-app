<?php
require_once("Conf.class.php");
require_once("Db.class.php");

function validarUsuario($usuario, $password, $mail)
{
	$bd=Db::getInstance();
	
	if(!empty($mail)){
		$consulta = "select * from usr where mail='".$mail."'";
		
		$stmt=$bd->ejecutar($consulta);
		$result=$bd->obtener_fila($stmt,0);
		if($result)
		{
			if($result[7]==1){
				$resultado=array();
				$consulta2 = "select * from gn_persona where id = '$result[6]';";
				$resultado2=array();
				$stmt2=$bd->ejecutar($consulta2);
				$result2=$bd->obtener_fila($stmt2,0);

				$resultado[0]=3;
				$resultado[1]=$result["nombre"];	//nombre (desde usr)
				$resultado[2]=$result["apellido"];	//apellido (desde usr)

				$resultado[3]=$result2["mail"];		//mail (desde gn_persona)
				
				$resultado[4]=$result["id_usr"];	//desde usr
				$resultado[5]=$result["rol"];		//desde usr

				$resultado[6]=$result2["id"];		//id_persona (desde gn_persona)

				$query_avatar = "SELECT * FROM gn_archivo WHERE id=".$result2["avatar"]."";
				$stmt_avatar = $bd->ejecutar($query_avatar);
				$x = $bd->obtener_fila($stmt_avatar, 0);

				$resultado[7] = "http://funsepa.net/suni/app/src/img/user_data/".$x[2];

				return $resultado;
			}
			else{
				$resultado[0]=1;
				return $resultado;
			}
		}
		else{
			echo $consulta." - ".$result[1];
			//echo "no";
			echo($result);
			$resultado[0]=1;
			return $resultado;
		}
	}
	else{
		$consulta = "select * from usr where id_usr = '$usuario';";
		$resultado=array();
		$stmt=$bd->ejecutar($consulta);
		$result=$bd->obtener_fila($stmt,0);


		if($result)
		{
			if($result[7]==1){	
				if( strcmp($password,$result[1]) == 0 ){

					$consulta2 = "select * from gn_persona where id = '$result[6]';";
					$resultado2=array();
					$stmt2=$bd->ejecutar($consulta2);
					$result2=$bd->obtener_fila($stmt2,0);



					$resultado[0]=3;
				$resultado[1]=$result["nombre"];	//nombre (desde usr)
				$resultado[2]=$result["apellido"];	//apellido (desde usr)

				$resultado[3]=$result2["mail"];		//mail (desde gn_persona)
				
				$resultado[4]=$result["id_usr"];	//desde usr
				$resultado[5]=$result["rol"];		//desde usr

				$resultado[6]=$result2["id"];		//id_persona (desde gn_persona)

				$query_avatar = "SELECT * FROM gn_archivo WHERE id=".$result2["avatar"]."";
				$stmt_avatar = $bd->ejecutar($query_avatar);
				$x = $bd->obtener_fila($stmt_avatar, 0);

				$resultado[7] = "http://funsepa.net/suni/app/src/img/user_data/".$x[2];

				return $resultado;						

			}else					
			$resultado[0]=2;
			return $resultado;
		}
		else{
			$resultado[0]=1;
			return $resultado;
		}
	}
	else
		$resultado[0]=1;
	return $resultado;
}
}
function validarUsuarioG($usuario)
{
	
	$bd = Db::getInstance();
	$consulta = "select * from usr where id_usr='lcontreras'";

	$resultado=array();
	$stmt=$bd->ejecutar($consulta);
	$result=$bd->obtener_fila($stmt,0);
	echo $consulta." - ".$result[1];

	if($result)
	{
	if($result[7]==1){	//Valida que el usuario esté activo
		

		$consulta2 = "select * from gn_persona where id = '$result[6]';";
		$resultado2=array();
		$stmt2=$bd->ejecutar($consulta2);
		$result2=$bd->obtener_fila($stmt2,0);



		$resultado[0]=3;
				$resultado[1]=$result["nombre"];	//nombre (desde usr)
				$resultado[2]=$result["apellido"];	//apellido (desde usr)

				$resultado[3]=$result2["mail"];		//mail (desde gn_persona)
				
				$resultado[4]=$result["id_usr"];	//desde usr
				$resultado[5]=$result["rol"];		//desde usr

				$resultado[6]=$result2["id"];		//id_persona (desde gn_persona)

				$query_avatar = "SELECT * FROM gn_archivo WHERE id=".$result2["avatar"]."";
				$stmt_avatar = $bd->ejecutar($query_avatar);
				$x = $bd->obtener_fila($stmt_avatar, 0);

				$resultado[7] = "http://funsepa.net/suni/app/src/img/user_data/".$x[2];

				return $resultado;						


			}
			else{
				$resultado[0]=1;
				return $resultado;
			}
		}
		else{
			echo "no";
			$resultado[0]=1;
			return $resultado;
		}
	}
	?>