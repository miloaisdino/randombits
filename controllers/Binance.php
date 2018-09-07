<?php
/**
 * Created by PhpStorm.
 * User: maximustan
 * Date: 18/8/17
 * Time: 11:54 PM
 */
class Binance extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Binance_api');
    }

    function table($array){
        $arraycontainingarray = [$array];
        $this->load->view('simpletable', $arraycontainingarray);
    }

    function model($modelfunc){
        //header("Content-Type: application/json");
        $tree = $this->uri->segment_array();
        $tree = array_slice($tree, 3);
        echo $this->Binance_api->arrayasargs($modelfunc, $tree);
        //echo $this->Binance_api->{$modelname}($tree);
        //echo "END";
    }

}
