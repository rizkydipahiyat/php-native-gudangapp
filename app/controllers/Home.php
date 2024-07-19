<?php

class Home extends Controller
{
   public function index()
   {
      $data = [
         'url' => 'out'
      ];
      $this->view('layouts/header', $data);
      $this->view('home/index');
      $this->view('layouts/footer');
   }


   public function chartBarangOut()
   {

      $barang_out = $this->model('BarangModel')->getAllBarangOut();
      // Menggabungkan data dengan tanggal yang sama
      $grouped_data = [];
      foreach ($barang_out as $item) {
         $tanggal = substr($item['tanggal'], 0, 10); // Hanya mengambil bagian tanggal tanpa waktu
         if (!isset($grouped_data[$tanggal])) {
            $grouped_data[$tanggal] = [
               'tanggal' => $tanggal,
               'qty' => 0
            ];
         }
         $grouped_data[$tanggal]['qty'] += $item['qty'];
      }

      // Mengonversi data kembali ke array numerik
      $combined_data = array_values($grouped_data);

      header('Content-Type: application/json');
      echo json_encode($combined_data);
   }
}
