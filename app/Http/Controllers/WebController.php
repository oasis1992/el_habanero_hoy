<?php

namespace App\Http\Controllers;

use Faker\Provider\DateTime;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use Parse\ParseClient;
use Parse\ParseQuery;

class WebController extends Controller
{
    public function getByLocation($lat, $lon, $all=null)
    {


        $this->initParse();
        if($all != null){
            $result = $this->getAll();
            $promedio_nacional = 0;
        }else{
            $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lon."&sensor=false";
            $geocode=file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng=20.573795,-88.718247&sensor=false');

            $output= json_decode($geocode);
            $array_location =  $output->results[0]->formatted_address;
            $state = explode(',', $array_location);
            $result = $this->queryAllOrFirst("registros","origen", trim($state[1]));
            $promedio_nacional = $this->queryAllOrFirst("registros", "origen", trim($state[1]), true /* promedio nacional */);
        }
        


        // waltmar query

        $query = new ParseQuery("sucursal");
        $sucursal = $query->first();


        $array_result = array();
        if($all != null){

            for($i =0; $i < count($result); $i++){
                $array_result[$i] = array('id' => $result[$i]->getObjectId(),
                    'fecha' => $result[$i]->get('fecha')->format('d-m-Y'),
                    'presentacion' => $result[$i]->get('presentacion'),
                    'origen' => $result[$i]->get('origen'),
                    'destino_especifico' => $result[$i]->get('destino_especifico'),
                    'destino_general' => $result[$i]->get('destino_especifico'),
                    'precio_min' => $result[$i]->get('precio_min'),
                    'precio_max' => $result[$i]->get('precio_max'),
                    'destino' => $result[$i]->get('destino'),
                    'precio_frec' => $result[$i]->get('precio_frec'),
                    'promedio_nacional' => $promedio_nacional,
                    'sucursal' => $sucursal->get('precio_sucursal'));
            }
        }else{

            for($i =0; $i < count($result); $i++){
                $array_result[$i] = array('id' => $result->getObjectId(),
                    'fecha' => $result->get('fecha')->format('d-m-Y'),
                    'presentacion' => $result->get('presentacion'),
                    'origen' => $result->get('origen'),
                    'destino_especifico' => $result->get('destino_especifico'),
                    'destino_general' => $result->get('destino_especifico'),
                    'destino' => $result->get('destino'),
                    'precio_min' => $result->get('precio_min'),
                    'precio_max' => $result->get('precio_max'),
                    'precio_frec' => $result->get('precio_frec'),
                    'promedio_nacional' => $promedio_nacional,
                    'sucursal' => $sucursal->get('precio_sucursal'));
            }
        }



        return (new Response($array_result, 200))->header('Content-Type', 'application/json');
    }

    private function initParse()
    {
        ParseClient::initialize( env('APP_ID'), '', env('MASTER_KEY'));
        ParseClient::setServerURL(env('URL_PARSE'));
    }

    public function queryAllOrFirst($parseQuery, $column, $query_string, $all= false){
        $query1 = new ParseQuery($parseQuery);

        if($all){
            $query1->equalTo('presentacion', 'Kilogramo');
            $query1->ascending('fecha');
            $results = $query1->find();
            $count = 0;
            for ($i =0; $i < count($results); $i++){
                $count = $results[$i]->get('precio_frec') + $count;
            }
            return $count / count($results);
        }else{
            $query1->equalTo($column, $query_string);
            $query1->equalTo('presentacion', 'Kilogramo');
            $query1->ascending('fecha');
            return $query1->first();
        }
    }

    public function getAll(){
        $query = new ParseQuery("registros");
        $query->ascending('fecha');
       return  $query->find();
    }
    
    public function querySpecialField()
    {
        $query = new ParseQuery("registros");
        //$query->select(["origen", "destino"]);
        $results = $query->find();
        return $results;
    }
}
