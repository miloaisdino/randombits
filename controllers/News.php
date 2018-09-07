<?php
/**
 * Created by PhpStorm.
 * User: maximustan
 * Date: 2/11/16
 * Time: 5:27 PM
 */
class News extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('news_model');
        $this->load->helper('url_helper');
    }

    public function index()
    {
        $data['news'] = $this->news_model->get_news();
        $data['title'] = 'News archive (list of news) (index function)';

        $this->load->view('templates/header', $data);
        $this->load->view('news/index', $data);
        $this->load->view('templates/footer');
    }

    public function view($slug = NULL)
    {
        $data['news_item'] = $this->news_model->get_news($slug);

        if (empty($data['news_item']))
        {
            //show_404();
        }

        $data['title'] = $data['news_item']['title'];

        $this->load->view('templates/header', $data);
        $this->load->view('news/view', $data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('form_validation');

        $data['title'] = 'Create a news item (create function)';

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('text', 'Text', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $data);
            $this->load->view('news/create');
            $this->load->view('templates/footer');

        }
        else
        {
            $this->news_model->set_news();
            redirect('/news');
        }
    }

    public function delete()
    {
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('form_validation');

        $data['title'] = 'Delete a news item (delete function)';

        $this->form_validation->set_rules('title', 'Title', 'required');
        //if action is not submit,
        if ($this->form_validation->run() === FALSE)
        {
            //display view with fields
            $this->load->view('templates/header', $data);
            $this->load->view('news/delete');
            $this->load->view('templates/footer');

        }
        else
        {
            //execute model instead
            $this->news_model->del_news();
            redirect('/news');
        }
    }
}