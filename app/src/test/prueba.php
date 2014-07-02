<?php
include '../libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

interface IUserPermissions
{
    const p_ver   = 1;
    const p_crear = 2;
    const p_editar   = 4;
    const p_eliminar = 8;
    const p_global = 15;
    const a_ver    = 16;
    const a_crear  = 32;
    const a_editar    = 64;
    const a_eliminar  = 128;
    const a_global  = 192; 
    const super_usr    = 255; 
}

class UserPermissions implements IUserPermissions
{

    private $Mask = 0;

    public function __construct($Mask = 0)
    {
        $this->Mask = $Mask;
    }

    public function InvokePermission($Bit)
    {
        return $this->Mask & $Bit;
    }

    public function RevokePermission($Bit)
    {
        $this->Mask &= ~$Bit;
    }

    public function AssignPermission($Bit)
    {
        $this->Mask |= $Bit;
    }

    public function GetStorableMask()
    {
        return decbin($this->Mask);
    }
}
$arrayName = array('PUBLIC_VIEW'    => 1,
    'PUBLIC_CREATE' => 2,
    'PUBLIC_EDIT'   => 4,
    'PUBLIC_DELETE' => 8,
    'PUBLIC_GLOBAL' => 15, 
    'ADMIN_VIEW'    => 16,
    'ADMIN_CREATE'  => 32,
    'ADMIN_EDIT'    => 64,
    'ADMIN_DELETE'  => 128,
    'ADMIN_GLOBAL'  => 192, 
    'SUPER_USER'    => 255 );

/*$Guest = new UserPermissions(UserPermissions::PUBLIC_VIEW);
foreach ($arrayName as $rol => $valor_dec) {
    if($Guest->InvokePermission($valor_dec))
    {
        echo $rol."<br />";
    }
}

/*for($i = 0; $i <9; $i++){
    //echo "REPETICIÓN: ".$i."<br />";
    $Guest ->AssignPermission(pow(2, $i));
    echo "asignado: ".$Guest->GetStorableMask()."<br />";
    foreach ($arrayName as $rol => $valor_dec) {
        if($Guest->InvokePermission($valor_dec))
        {
            //echo $rol."<br />";
        }
    }
    echo "<br />";
    if(($i % 2) == 0){
        $Guest ->RevokePermission(pow(2, ($i-1)));
        echo "revocado: ".$Guest->GetStorableMask()."<br />";
        foreach ($arrayName as $rol => $valor_dec) {
            if($Guest->InvokePermission($valor_dec))
            {
                //echo $rol."<br />";
            }
        }
        echo "<br /><br />";
    }   
}*/

//$sesion = permiso::getInstance(41, Db::getInstance());
print_r($sesion->mostrar_permisos())."<br>";

echo "<br /> 8: ".($sesion->has(2, 8, 1) ? "tiene" : "No tiene");
echo "<br />4: ".($sesion->has(2, 4, 1) ? "tiene" : "No tiene");
$sesion->give(2, 8, 1);
$sesion->give(2, 4, 1);
echo "<br /> g: ".($sesion->has(2, 8) ? "tiene" : "No tiene");
echo "<br />4: ".($sesion->has(2, 4) ? "tiene" : "No tiene");
//$sesion->has(2, 8) ? $sesion->take(2,8) : $sesion->give(2,8);
echo "<br /> SESION 8: ".($sesion->has(2, 8) ? "tiene" : "No tiene");
echo "<br />SESION 4: ".($sesion->has(2, 4) ? "tiene" : "No tiene");
$sesion->take(2, 4, 1);
$sesion->take(2, 8, 1);
echo "<br /> 8: ".($sesion->has(2, 8, 1) ? "tiene" : "No tiene");
echo "<br />4: ".($sesion->has(2, 4, 1) ? "tiene" : "No tiene");
echo "permisos actuales: ";
print_r($sesion->mostrar_permisos());

//echo $sesion->has(2, 8, 1) ? "<br /> tiene" : "<br />No tiene";
//echo $sesion->mostrar_permisos(2, 1);
//echo $sesion->take(2, 8, 1) ? "<br /> tiene" : "<br />No tiene";
//print_r($sesion->mostrar_permisos())."<br>";
?>