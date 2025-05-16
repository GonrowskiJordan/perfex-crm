<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Swagger extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('app_modules');
    }
        
    public function index() {
        $data['title'] = _l('api_guide');
        $this->load->view('playground', $data);
    }

    public function json()
    {
        echo file_get_contents(dirname(__DIR__) . '/swagger.json');
    }
}