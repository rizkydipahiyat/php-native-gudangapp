<?php

class Controller
{
   public function view($view, $data = [])
   {
      require_once '../app/views/' . $view . '.php';
   }

   public function model($model)
   {
      require_once '../app/models/' . $model . '.php';
      return new $model;
   }

   public function loadView($viewPath, $data = [])
   {
      // Ekstrak data array menjadi variabel
      extract($data);

      // Mulai output buffering
      ob_start();

      // Sertakan file view
      include($viewPath);

      // Ambil isi buffer dan hapus buffer
      $output = ob_get_clean();

      return $output;
   }
}
