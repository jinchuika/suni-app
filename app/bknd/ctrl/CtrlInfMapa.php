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
     * @param  string $id_escuela el ID de la escuela
     * @param  string $lat        latitud de las coordenadas
     * @param  string $lng        longitud de las coordenadas
     * @return string             'Creado'|'Error' TODO: que sea boolean
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

    /**
     * Actualiza las coordenadas de los registros que recibas
     * @param  string $id_coordenada el ID de la coordenada en la DB
     * @param  string $lat           Latitud de las coordenadas
     * @param  string $lng           Longitud de las coordenadas
     * @return string                'Editado' para indicar que se hizo
     */
    public function actualizarCoordenada($id_coordenada, $lat, $lng)
    {
        $gn_coordenada = new GnCoordenada();
        $gn_coordenada->editarCoordenada(array('lat'=>$lat,'lng'=>$lng), array('id'=>$id_escuela));
        return 'Editado';
    }

    /**
     * ACtualiza la información de la escuelas
     * @param  string $udi el UDI de la escuela a editar
     * @return Array      La información actualizada de la escuela
     */
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

    /**
     * Lista las escuelas usando V_InformeMapa
     * @param  Array|null $arr_filtros Los filtros para las escuelas
     * @return Array
     */
    public function listarEscuelas(Array $arr_filtros = null)
    {
        $v_mapa = new V_InformeMapa();
        return $v_mapa->listarEscuelas($arr_filtros);
    }

    /**
     * Lista los departamentos de la DB
     * @return Array La lista de los departamentos
     */
    public function listarDepartamento()
    {
        $gn_departamento = new GnDepartamento();
        return $gn_departamento->listarDepartamento();
    }

    /**
     * Lista los municipios de la DB
     * @param  Array|null $arr_filtros Los filtros para abrir los registros
     * @return Array                  La lista de municipios
     */
    public function listarMunicipio(Array $arr_filtros = null)
    {
        $gn_municipio = new GnMunicipio();
        return $gn_municipio->listarMunicipio($arr_filtros);
    }

    /**
     * Lista los departamentos y municipios de la DB
     * @return Array Las dos listas juntas
     */
    public function listarGeografia()
    {
        return array('arr_departamento'=>$this->listarDepartamento(), 'arr_municipio'=>$this->listarMunicipio());
    }
}
?>