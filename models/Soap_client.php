<?php
/**
 * Created by PhpStorm.
 * User: maximustan
 * Date: 3/12/16
 * Time: 6:55 PM
 */
class Soap_client extends CI_Model {
    function __construct(){
        parent::__construct();
        $this->load->model('Get_model');
    }

    function request($uri, $action, $body, $format = "text/xml; charset=utf-8"){
        $headers = ["SOAPAction: $action", "Content-Type: $format"];
        //$headers = [];
        $reply = $this->Get_model->curl_post($uri, $headers, $body);
        return $reply;
    }
}