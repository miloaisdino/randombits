<?php
/**
 * Created by PhpStorm.
 * User: maximustan
 * Date: 21/12/16
 * Time: 11:47 PM
 */
class Hs extends CI_Controller {
    function start(){
            $disabled = 0;
            $challenge = $this->input->post('challenge');
            $userurl = $this->input->post('userurl');
            $uamport = $this->input->post('uamport');
            $uamip = $this->input->post('uamip');
            $msg = $this->input->post('reply');
            if( !isset($msg)){
            $msg = 'Not registered? <a href="/radius/signup">Sign up now!</a>';
            }
            if( !isset($challenge)){ 
                  $msg = "Direct access to login page isn't allowed.";
		  header('Location: status');
            $disabled = 1;
            }
            $query = [
                "challenge" => $challenge,
                "userurl" => $userurl,
                "uamport" => $uamport,
                "uamip" => $uamip,
            ];
            $data = [
                "controller" => $query,
                "msg" => $msg,
                "disabled" => $disabled
            ];
            $this->load->view('hs/start', $data);
    }

    function login(){
        //$this->load->helper('url');
        $controller = $this->input->post();
        /*$controller['UserName'] = $this->input->post('UserName');
        $controller['Password'] = $this->input->post('Password');
        $controller['challenge'] = $this->input->post('challenge');
        $controller['userurl'] = $this->input->post('userurl');
        $controller['uamport'] = $this->input->post('uamport');
        $controller['uamip'] = $this->input->post('uamip'); */
        $controller['button'] = 'Login';
        $data = [
            'controller' => $controller,
            'method' => 'post',
            'action' => '/ci/application/controllers/hotspotlogin.php'
        ];
        $this->load->view('hs/formsubmit', $data);
    }

    function status(){
        $this->load->view('hs/status', [ 'msg' => 'Logged In']);
    }
}
