<?php
/**
* Clase para control del mapa de todas las escuelas
*/
class CtrlInfMapa extends Controller
{
	/**
     * Obtiene las coordenadas de una escuela usando la base de datos del MINEDUC
     * @param  string $udi El udi de la escuela a buscar
     * @return Array      {lng, lat}
     */
    public function parseCoordenada($udi)
    {
        $url = 'http://www.mineduc.gob.gt/ie/displayListn.asp?establecimiento=&codigoudi='.$udi;
        $contents = file_get_contents($url);
        preg_match("'<longitude>(.*?)</longitude>'", $contents, $longitude);
        preg_match("'<latitude>(.*?)</latitude>'", $contents, $latitude);
        return array('lng'=>$longitude[1], 'lat'=>$latitude[1]);
    }

    /**
     * Crea o modifica un registro de coordenadas en la base de datos
     * @param  Array  $escuela [description]
     * @param  Array  $mapa    [description]
     * @return [type]          [description]
     */
    public function crearCoordenada($id_escuela, $lat, $lng)
    {
        $gn_coordenada = new GnCoordenada();
        $gn_escuela = new GnEscuela();

        $coordenada = $gn_coordenada->crearCoordenada($lat, $lng);
        if(!empty($coordenada)){
            $gn_escuela->editarEscuela(array('mapa'=>$coordenada), array('id'=>$id_escuela));
            return 'Creado';
        }
        return 'Error';
    }

    public function actualizarCoordenada($id_coordenada, $lat, $lng)
    {
        $gn_coordenada = new GnCoordenada();
        $gn_coordenada->editarCoordenada(array('lat'=>$lat,'lng'=>$lng), array('id'=>$id_escuela));
        return 'Editado';
    }

    public function actualizarInfoEscuela($udi)
    {
        $gn_escuela = new GnEscuela();

        $mapa = $this->parseCoordenada($udi);
        $escuela = $gn_escuela->abrirEscuela(array('codigo'=>$udi), 'id, nombre, mapa as coordenada');

        if($escuela){
            $escuela['udi'] = $udi;

            $accion = !empty($escuela['coordenada'])
                ? $this->actualizarCoordenada($escuela['id'], $mapa['lat'], $mapa['long'])
                : $this->crearCoordenada($escuela['id'], $mapa['lat'], $mapa['lng']);

            $respuesta = array(
                'udi'=>$udi,
                'nombre'=>$escuela['nombre'],
                'coor'=>$escuela['coordenada'],
                'longitude'=>$mapa['lng'],
                'latitude'=>$mapa['lat'],
                'coordenada'=>$accion
                );
        }
        else{
            $respuesta = array(
                'udi'=>$udi,
                'nombre'=>'No se encontró',
                'longitude'=>$mapa['lng'],
                'latitude'=>$mapa['lat']
                );
        }

        return $respuesta;
    }
}
?>