<?php
/**
 * Created by PhpStorm.
 * User: maximustan
 * Date: 2/11/16
 * Time: 4:51 PM
 */
class Pages extends CI_Controller {

    public function view($page = 'home')
    {
        if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php'))
        {
            // Whoops, we don't have a page for that!
            //show_404();
            echo APPPATH.'views/pages/'.$page.'.php';
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        $this->load->view('templates/header', $data);
        $this->load->view('pages/'.$page, $data);
        $this->load->view('templates/footer', $data);
    }
}