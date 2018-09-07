<?php
/**
 * Created by PhpStorm.
 * User: maximustan
 * Date: 2/11/16
 * Time: 6:19 PM
 */
class Home extends CI_Controller {
    function index(){
        $this->load->model('Psitable_model');
        $model = $this->Psitable_model->index();
        $data = [
                    'title' => 'Haze Information',
                    'table' => $model['table'],
                    'time' => $model['time']
                ];

        $this->load->view('header');
        $this->load->view('haze', $data);
    }
}