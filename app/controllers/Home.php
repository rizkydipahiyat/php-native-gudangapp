<?php

class Home extends Controller
{
   public function index()
   {

      $data['url'] = 'dashboard';
      $this->view('layouts/header', $data);
      $this->view('home/index');
      $this->view('layouts/footer');
   }
}
