<?php
/**
 * Created by PhpStorm.
 * User: maximustan
 * Date: 8/1/17
 * Time: 10:56 AM
 */
class Iptv extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database('iptv');
    }

    public function admin($reload = NULL){
        $title = 'Iptv last usage by userid';
        if (!$reload) {
            $this->load->view('smrt', ['station' => $title, 'title' => $title]);
            return;
        }

        $query = $this->db->query('select username, logintime from oneperson');
        $array = (array) $query->result_array();
        foreach ($array as $key => $row)
        {
            $array[$key]['logintime'] = date('d M Y  h:i', $row['logintime']);
        }

        $data = [
            //'title' => 'Iptv last usage by userid',
            'model' => $array
            //'time' => $model['time']
        ];

            $this->load->view('smrt_container', $data);


        //echo 'Total Results: ' . $row['logintime'];
    }
}