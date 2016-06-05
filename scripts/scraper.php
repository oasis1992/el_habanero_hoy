<?php
    /**
     * Created by lucks.
     * Date: 04/06/16
     * Time: 10:03 PM
     * This software is confidential information. You may not
     * disclose confidential information and should be used only in accordance with the customer.
     * The misuse of the code is out of reach of the creator.
     */
/* functions */
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

/*
* End functions
*/

    $query ='http://www.economia-sniim.gob.mx/NUEVO/Consultas/MercadosNacionales/PreciosDeMercado/Agricolas/ResultadosConsultaFechaFrutasYHortalizas.aspx?fechaInicio=03/06/2016&fechaFinal=04/06/2016&ProductoId=230&OrigenId=-1&Origen=Todos&DestinoId=-1&Destino=Todos&PreciosPorId=1&RegistrosPorPagina=500';
	$homepage = file_get_contents($query);

	// se obtienen los resultados
	// echo
	$results = get_string_between($homepage,'<table id="tblResultados" cellspacing="0" cellpadding="0" bordercolor="#FFAB73" border="0" height="8" width="800">', "</table></TD>");

	// de este universo hacemos ingenieria inversa para extraer datos
	$porciones = explode("<tr>", $results);

	// print_r($porciones);

	foreach($porciones as $key => $value){
        // encabezados
        if ($key == 1){
            $arrayElements = array('<td class="titDATtab2">','</td>','</tr>','|');
            $headers = getHeaders($arrayElements,$value);
            echo $headers;
        }
        // datos
        if ($key >2){
            $arrayElements2 = array('<td class="Datos2" colspan="1">','<td class="Datos2">','</td>','</tr>','|');
            $datos[] = getElements($arrayElements2,$value);
        }

    }
	// fin

	// $headers // Encabezados para las columnas
	// $datos // Arreglo con la información
	// print_r($datos);

	$arrayData = array($headers,$datos);
	// mandar a parse $arrayData

	
?>