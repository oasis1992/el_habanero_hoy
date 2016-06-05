<?php
    /**
     * Created by lucks.
     * Date: 05/06/16
     * Time: 4:31 AM
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

	 $query ='https://www.walmart.com.mx/super/verduras/chile-habanero-por-kilo-0000000003125/';
		$homepage = file_get_contents($query);
		// echo $homepage;
		 $results = get_string_between($homepage,'lblPrice', "</span>");
			  $results;
			$precio = str_replace('" itemprop="price" class="price-prod&#32;">', "", $results);
			echo $precio;


		?>