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
        $this->initParse();
        $array = array();
        $array[0] =  "Fecha|Presentación|Origen|Destino|Precio Mín|Precio Max|Precio Frec|Obs.";
        $array[1] = array(0 =>  "Fecha|Presentación|Origen|Destino|Precio Mín|Precio Max|Precio Frec|Obs.",
            1 => '03/06/2016|Kilogramo|Campeche|Campeche: Mercado "Pedro Sáinz de Baranda", Campeche|35.00|40.00|40.00',
         2 => "03/06/2016|Kilogramo|Jalisco|Nayarit: Mercado de abasto 'Adolfo López Mateos' de Tepic|39.00|54.00|52.00",
         3 => "03/06/2016|Kilogramo|Jalisco|Nayarit: Mercado de abasto 'Adolfo López Mateos' de Tepic|39.00|54.00|52.00",
         4 => "03/06/2016|Kilogramo|Tamaulipas|Nuevo León: Mercado de Abasto 'Estrella' de San Nicolás de los Garza|50.00|60.00|50.00",
         5 =>  "03/06/2016|Kilogramo|Distrito Federal|Oaxaca: Módulo de Abasto de Oaxaca|40.00|44.00|42.00",
         6 => "03/06/2016|Kilogramo|Quintana Roo|Quintana Roo: Mercado de Chetumal, Quintana Roo|95.00|95.00|95.00",
         7 =>  "03/06/2016|Kilogramo|Tabasco|Tabasco: Central de Abasto de Villahermosa|50.00|60.00|50.00",
         8 => "03/06/2016|Kilogramo|Puebla|Tamaulipas: Módulo de Abasto de Tampico, Madero y Altamira|50.00|60.00|60.00",
         9 => "03/06/2016|Kilogramo|Veracruz|Veracruz: Central de Abasto de Jalapa|42.00|43.00|43.00",
         10 => "03/06/2016|Kilogramo|Veracruz|Veracruz: Central de Abasto de Minatitlán|50.00|55.00|55.00",
          11 => "03/06/2016|Kilogramo|Veracruz|Veracruz: Mercado Malibrán|60.00|65.00|65.00",
    12 => "03/06/2016|Kilogramo|Yucatán|Yucatán: Central de Abasto de Mérida|40.00|60.00|50.00",
    13 => "03/06/2016|Caja de 10 kg.|Yucatán|Yucatán: Mercado 'Casa del Pueblo'|600.00|600.00|600.00" );

        $this->createData($array);
    }

    private function initParse()
    {
        ParseClient::initialize( env('APP_ID'), '', env('MASTER_KEY'));
        ParseClient::setServerURL(env('URL_PARSE'));
    }

    public function createData($arrayData)
    {
        $object = ParseObject::create("registros");
        $headers = array();
        $headers[0] = "fecha";
        $headers[1] = "presentacion";
        $headers[2] = "origen";
        $headers[3] = "destino";
        $headers[4] = "precio_min";
        $headers[5] = "precio_max";
        $headers[6] = "precio_frec";
        $headers[7] = "precio_obs";
       //dd($arrayData);

        for($i = 0 ; $i < count($headers)-1; $i++){
            $array_registro = explode('|', $arrayData[1][$i]);

            for($j = 0; $j < count($array_registro); $j++){
                $object->getObjectId();
                if($headers[$i] == "fecha"){
                    $object->setArray( "fecha", ["__type" => "Date", "iso" => $this->getProperDateFormat($array_registro[$i]) ]);
                }else{
                    $object->set($headers[$i], $array_registro[$i]);
                }
            }
            $object->save();
        }
    }

    function getProperDateFormat($value)
    {
        date_default_timezone_set('UTC');
        $value = date_create("2016-03-06");
        $dateFormatString = 'Y-m-d H:i:s.u';

        $date = date_format($value, $dateFormatString);
        $date = substr($date, 0, -3) . 'Z';
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


    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}
