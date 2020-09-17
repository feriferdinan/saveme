<?php

class Home extends Controller
{
    public function index()
    {
        $data['title'] = 'Save-Me';
        $data['css'] = ['home/css/index'];
        $data['js'] = ['home/js/index'];
        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer', $data);
    }
}
