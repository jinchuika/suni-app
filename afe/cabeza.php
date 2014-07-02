<?php
function Imprimir_cabeza($entrada,$nombre,$apellido){
	
	if($entrada==1){

?>
		<div class="navbar" >
		    <div class="navbar-inner">
		      <div class="container">
		   
		        <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
		        <a class="btn btn-navbar " data-toggle="collapse" data-target=".nav-collapse" id="">
		          <span class="icon-bar"></span>
		          <span class="icon-bar"></span>
		          <span class="icon-bar"></span>
		        </a>
		   
		        <!-- Be sure to leave the brand out there if you want it shown -->
		        <img src="../media/img/biblio2.png">
		   
		        <!-- Everything you want hidden at 940px or less, place within here -->
		        <div class="nav-collapse collapse">
		          <!-- .nav, .navbar-search, .navbar-form, etc -->

		          <ul class="nav nav-list">
		            <li >
		              <a href=<?echo "\"http://funsepa.net/suni/app/usr/perfil.php?id_per=".$sesion->get("id_per")."\"";?>>
		                <i class="icon-user"></i> Bienvenido: <?echo $nombre; echo " ".$apellido;?>

		              </a>
		            </li>

		            <li>
		              <a href="../principal.php">
		                <i class="icon-home"></i> Men&uacute; Principal
		              </a>
		            </li>

		            <li>
		              <a href="./consulta_capa.php">
		                <i class="icon-info-sign"></i> Informaci&oacute;n de Evaluaciones
		              </a>
		            </li>

		            <li>
		              <a href="../cerrarsesion.php">
		                <i class="icon-off"></i> Cerrar Sesi&oacute;n
		              </a>
		            </li>
		            
		          </ul>
		        </div>
		   
		      </div>
		    </div>
		  </div>
<?php
	}

		if($entrada==2){

?>
		<div class="navbar" >
		    <div class="navbar-inner">
		      <div class="container">
		   
		        <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
		        <a class="btn btn-navbar " data-toggle="collapse" data-target=".nav-collapse" id="">
		          <span class="icon-bar"></span>
		          <span class="icon-bar"></span>
		          <span class="icon-bar"></span>
		        </a>
		   
		        <!-- Be sure to leave the brand out there if you want it shown -->
		        <img src="../media/img/biblio2.png">
		   
		        <!-- Everything you want hidden at 940px or less, place within here -->
		        <div class="nav-collapse collapse">
		          <!-- .nav, .navbar-search, .navbar-form, etc -->

		          <ul class="nav nav-list">
		            <li >
		              <a href=<?echo "\"http://funsepa.net/suni/app/usr/perfil.php?id_per=".$sesion->get("id_per")."\"";?>>
		                <i class="icon-user"></i> Bienvenido: <?echo $nombre; echo " ".$apellido;?>
		              </a>
		            </li>

		            <li>
		              <a href="../principal.php">
		                <i class="icon-home"></i> Men&uacute; Principal
		              </a>
		            </li>

		            <li>
		              <a href="./evaluacion.php">
		                <i class="icon-list-alt"></i> Evaluaci&oacute;n a Capacitaci&oacute;n
		              </a>
		            </li>

		            <li>
		              <a href="../cerrarsesion.php">
		                <i class="icon-off"></i> Cerrar Sesi&oacute;n
		              </a>
		            </li>
		            
		          </ul>
		        </div>
		   
		      </div>
		    </div>
		  </div>
<?php
	}
		if($entrada==3){
?>
		<div class="navbar" >
		    <div class="navbar-inner">
		      <div class="container">
		   
		        <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
		        <a class="btn btn-navbar " data-toggle="collapse" data-target=".nav-collapse" id="">
		          <span class="icon-bar"></span>
		          <span class="icon-bar"></span>
		          <span class="icon-bar"></span>
		        </a>
		   
		        <!-- Be sure to leave the brand out there if you want it shown -->
		        <img src="../../media/img/biblio2.png">
		   
		        <!-- Everything you want hidden at 940px or less, place within here -->
		        <div class="nav-collapse collapse">
		          <!-- .nav, .navbar-search, .navbar-form, etc -->

		          <ul class="nav nav-list">
		            <li >
		              <a href="">
		                <i class="icon-home"></i> Bienvenido: <?echo $nombre; echo " ".$apellido;?>
		              </a>
		            </li>

		            <li>
		              <a href="../../principal.php" onclick="parent.location='../../principal.php'">
		                <i class="icon-home"></i> Men&uacute; Principal
		              </a>
		            </li>

		            <li>
		              <a href="javascript::parent.location('../../afe/evaluacion.php')" onclick="parent.location='../../afe/evaluacion.php'">
		                <i class="icon-home"></i> Evaluaci&oacute;n a Capacitaci&oacute;n
		              </a>
		            </li>

		            <li>
		              <a href="../../cerrarsesion.php onclick="parent.location='../../cerrarsesion.php'"">
		                <i class="icon-home"></i> Cerrar Sesi&oacute;n
		              </a>
		            </li>
		            
		          </ul>
		        </div>
		   
		      </div>
		    </div>
		  </div>
<?php }
}
?>