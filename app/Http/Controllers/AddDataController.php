<?php

namespace App\Http\Controllers;


use DateTime;
use Illuminate\Http\Request;

use App\Http\Requests;
use Parse\ParseClient;
use Parse\ParseObject;

class AddDataController extends Controller
{
    public function setInfo()
    {
        $data_location = array();

        $data_location[0] = array( 'name' => 'Yucatán','lat'=>20.809068,'lon'=>-89.049861);
        $data_location[1] = array( 'name' =>'Distrito Federal','lat' => 19.389224, 'lon' =>  -99.136798);
        $data_location[2] = array( 'name' => 'Veracruz','lat' => 19.179898, 'lon' => -96.146466);
        $data_location[3] = array( 'name' => 'Oaxaca','lat' => 16.960807, 'lon' => -96.699842);
        $data_location[4] = array( 'name' => 'Tamaulipas','lat' => 24.9262332, 'lon' => -100.8892395);
        $data_location[5] = array( 'name' => 'Tabasco','lat' => 18.4379224, 'lon' => -92.3948247);
        $data_location[6] = array( 'name' => 'Nuevo León','lat' =>  25.4638346, 'lon' => -102.0587768);
        $data_location[7] = array( 'name' => 'Jalisco','lat' => 20.8285822, 'lon' => -105.8473639);
        $data_location[8] = array( 'name' => 'Puebla','lat' => 18.4062289, 'lon' => -98.052264);
        $data_location[9] = array( 'name' => 'Quintana Roo','lat' => 19.7268228, 'lon' => -90.2484308);
        $data_location[10] = array( 'name' => 'Campeche','lat' => 19.062597, 'lon' => -90.180422);






        $this->initParse();
        $array = array();
        $array[0] =  "Fecha|Presentación|Origen|Destino|Precio Mín|Precio Max|Precio Frec|Obs.";
        $array[1] = array(
            0 => "03/06/2016|Kilogramo|Campeche|Campeche: Mercado 'Pedro Sáinz de Baranda' Campeche|35.00|40.00|40.00",
         1 => "03/06/2016|Kilogramo|Jalisco|Nayarit: Mercado de abasto 'Adolfo López Mateos' de Tepic|39.00|54.00|52.00",
         2 => "03/06/2016|Kilogramo|Jalisco|Nayarit: Mercado de abasto 'Adolfo López Mateos' de Tepic|39.00|54.00|52.00",
         3 => "03/06/2016|Kilogramo|Tamaulipas|Nuevo León: Mercado de Abasto 'Estrella' de San Nicolás de los Garza|50.00|60.00|50.00",
         4 =>  "03/06/2016|Kilogramo|Distrito Federal|Oaxaca: Módulo de Abasto de Oaxaca|40.00|44.00|42.00",
         5 => "03/06/2016|Kilogramo|Quintana Roo|Quintana Roo: Mercado de Chetumal, Quintana Roo|95.00|95.00|95.00",
         6 =>  "03/06/2016|Kilogramo|Tabasco|Tabasco: Central de Abasto de Villahermosa|50.00|60.00|50.00",
         7 => "03/06/2016|Kilogramo|Puebla|Tamaulipas: Módulo de Abasto de Tampico, Madero y Altamira|50.00|60.00|60.00",
         8 => "03/06/2016|Kilogramo|Veracruz|Veracruz: Central de Abasto de Jalapa|42.00|43.00|43.00",
         9 => "03/06/2016|Kilogramo|Veracruz|Veracruz: Central de Abasto de Minatitlán|50.00|55.00|55.00", 10 => "03/06/2016|Kilogramo|Veracruz|Veracruz: Mercado Malibrán|60.00|65.00|65.00",
        11 => "03/06/2016|Kilogramo|Yucatán|Yucatán: Central de Abasto de Mérida|40.00|60.00|50.00",
        12 => "03/06/2016|Caja de 10 kg.|Yucatán|Yucatán: Mercado 'Casa del Pueblo'|600.00|600.00|600.00" );

        $this->createData($array, $data_location);
    }

    private function initParse()
    {
        ParseClient::initialize( env('APP_ID'), '', env('MASTER_KEY'));
        ParseClient::setServerURL(env('URL_PARSE'));
    }

    public function createData($arrayData, $array_location)
    {

        $headers = array();
        $headers[0] = "fecha";
        $headers[1] = "presentacion";
        $headers[2] = "origen";
        $headers[3] = "destino";
        $headers[4] = "precio_min";
        $headers[5] = "precio_max";
        $headers[6] = "precio_frec";
        $headers[7] = "precio_sucursal";
        $headers[8] = "precio_obs";

        $size = count($arrayData[1]);
        
        for($i = 0 ; $i < $size; $i++){

            $object = ParseObject::create("registros");
            $array_registro = explode('|', $arrayData[1][$i]);
            $object->getObjectId();

            $date = $this->change_order($array_registro[0]);
            $object->set( "fecha", $this->getProperDateFormat($date));
            $object->set("presentacion",$array_registro[1]);

            $array_destino = explode(':', $array_registro[3]);
            $destino_general = $array_destino[0];
            $destino_especifico = $array_destino[1];
            $bandera = false;
            $bandera2 = false;
            for($j = 0; $j < count($array_location); $j++){
               // print($array_location[$j]['name']);
                if(strcmp($array_location[$j]['name'], $array_registro[2])){
                    $object->setArray('origen', array('nombre' =>  $array_registro[2], 'lat' => $array_location[$j]['lat'], 'lon' => $array_location[$j]['lon']));
                  //  $object->set('destino_general', $destino_general);
                    $object->set('destino_especifico', $destino_especifico);
                    $bandera = true;
                    break;
                }
                if($bandera){
                    break;
                }
            }


            for($j = 0; $j < count($array_location); $j++){
                if(strcmp($array_location[$j]['name'],$destino_general)){

                    $object->setArray('destino', array('nombre' =>  $destino_general, 'lat' => $array_location[$j]['lat'], 'lon' => $array_location[$j]['lon']));
                    $bandera2= true;
                    break;
                }
                if($bandera2){
                    break;
                }
            }

            $object->set("precio_min",(double)$array_registro[4]);
            $object->set("precio_max",(double)$array_registro[5]);
            $object->set("precio_frec",(double)$array_registro[6]);
            $object->save();
        }
        $object2 = ParseObject::create("sucursal");

        $precio_waltmar = $this->waltmar();
        $object2->set("precio_sucursal",(double)$precio_waltmar);
        $object2->save();
    }

    function getProperDateFormat($value)
    {
        $date = new DateTime($value);
        return $date;
    }

    public function change_order($date){
        $date = explode('/', $date);
        $date = $date[2]."/".$date[1]."/".$date[0];
        return $date;
    }

    function stripAccents($string){
        return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
            'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    }
    
    public function map()
    {
        return View('web.map');
    }

    public function getInfoWeb()
    {
        $query ='http://www.economia-sniim.gob.mx/NUEVO/Consultas/MercadosNacionales/PreciosDeMercado/Agricolas/ResultadosConsultaFechaFrutasYHortalizas.aspx?fechaInicio=03/06/2016&fechaFinal=04/06/2016&ProductoId=230&OrigenId=-1&Origen=Todos&DestinoId=-1&Destino=Todos&PreciosPorId=1&RegistrosPorPagina=500';
        $homepage = file_get_contents($query);

        // se obtienen los resultados
        // echo
        $results = $this->get_string_between($homepage,'<table id="tblResultados" cellspacing="0" cellpadding="0" bordercolor="#FFAB73" border="0" height="8" width="800">', "</table></TD>");

        // de este universo hacemos ingenieria inversa para extraer datos
        $porciones = explode("<tr>", $results);

        // print_r($porciones);

        foreach($porciones as $key => $value){
            // encabezados
            if ($key == 1){
                $arrayElements = array('<td class="titDATtab2">','</td>','</tr>','|');
                $headers = $this->getHeaders($arrayElements,$value);
                echo $headers;
            }
            // datos
            if ($key >2){
                $arrayElements2 = array('<td class="Datos2" colspan="1">','<td class="Datos2">','</td>','</tr>','|');
                $datos[] = $this->getElements($arrayElements2,$value);
            }

        }
        // fin

        // $headers // Encabezados para las columnas
        // $datos // Arreglo con la información
        // print_r($datos);

        $arrayData = array($headers,$datos);
        return $arrayData;
    }

    public function getChileHabanero()
    {
        $query ='https://www.walmart.com.mx/super/verduras/chile-habanero-por-kilo-0000000003125/';
        $homepage = file_get_contents($query);
        // echo $homepage;
        $results = get_string_between($homepage,'lblPrice', "</span>");
        $results;
        $precio = str_replace('" itemprop="price" class="price-prod&#32;">', "", $results);
        return $precio;
    }

    function getHeaders($array,$string){
        $resultado = str_replace($array[0], "", $string);
        $resultado2 = str_replace($array[1], "|", $resultado);
        $resultado3 = str_replace($array[2], "", $resultado2);
        $final0 = trim($resultado3);
        $final = rtrim($final0, $array[3]);
        return $final;
    }
    function getElements($array,$string){
        $resultado = str_replace($array[0], "", $string);
        $resultado1 = str_replace($array[1], "", $resultado);
        $resultado2 = str_replace($array[2], "|", $resultado1);
        $resultado3 = str_replace($array[3], "", $resultado2);
        $final0 = trim($resultado3);
        $final = rtrim($final0, $array[4]);
        return $final;
    }

    public function waltmar()
    {
        $query ='https://www.walmart.com.mx/super/verduras/chile-habanero-por-kilo-0000000003125/';
        $homepage = file_get_contents($query);
        // echo $homepage;
        $results = get_string_between($homepage,'lblPrice', "</span>");
        $results;
        $precio = str_replace('" itemprop="price" class="price-prod&#32;">', "", $results);

        $precio = ltrim($precio, '$');
        return $precio;
    }
}
