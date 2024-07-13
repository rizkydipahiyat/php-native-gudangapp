<?php

class Stock extends Controller
{
   public function index()
   {
      $data['url'] = 'stock';
      $this->view('layouts/header', $data);
      $this->view('stock/index');
      $this->view('layouts/footer');
   }

   public function getStocks()
   {
      $stocks = $this->model('StockModel')->getAllStock();
      $data = [];
      $no = 1;
      foreach ($stocks as $stock) {
         $row = [
            'DT_RowIndex' => $no++,
            'name' => $stock['name'],
            'description' => $stock['description'],
            'stock' => $stock['stock'],
            'image' => '<img src="' . BASEURL . '/storage/' . $stock['image'] . '" alt="logo" width="25">',
            'action' => '
                     <button onclick="editForm(\'' . BASEURL . '/stock/update/' . $stock['id'] . '\')" class="btn btn-sm btn-info btn-flat">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button onclick="deleteData(\'' . BASEURL . '/stock/delete/' . $stock['id'] . '\')" class="btn btn-sm btn-danger btn-flat">
                        <i class="fa fa-trash"></i>
                    </button>
            '
         ];
         $data[] = $row;
      }
      header('Content-Type: application/json');
      echo json_encode(['data' => $data]);
   }

   public function edit($id)
   {
      $stock = $this->model('StockModel')->getStockById($id);
      header('Content-Type: application/json');
      echo json_encode($stock);
   }

   public function store()
   {
      if (isset($_FILES['image'])) {
         // Tentukan lokasi penyimpanan file
         $target_dir = "../public/storage/";
         // Generate nama file unik
         $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
         $unique_name = uniqid() . '.' . $file_extension;
         $target_file = $target_dir . $unique_name;

         // Pindahkan file yang diunggah ke lokasi yang ditentukan
         if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $data = [
               'name' => $_POST['name'],
               'description' => $_POST['description'],
               'image' => $unique_name
            ];
            // Store to DB
            if ($this->model('StockModel')->addStock($data) > 0) {
               Flasher::setFlash('Success', 'Added stock successfully', 'success');
               header('Location: ' . BASEURL . '/stock');
               exit;
            } else {
               Flasher::setFlash('Failed', 'Add stock failed', 'danger');
               header('Location: ' . BASEURL . '/stock');
               exit;
            }
         } else {
            Flasher::setFlash('Failed', 'Sorry, there was an error uploading your file.', 'danger');
            header('Location: ' . BASEURL . '/stock');
            exit;
         }
      } else {
         Flasher::setFlash('Failed', 'No file was uploaded.', 'danger');
         header('Location: ' . BASEURL . '/stock');
         exit;
      }
   }

   public function update($id)
   {
      $stock = $this->model('StockModel')->getStockById($id);

      $data = [
         'id' => $id,
         'name' => $_POST['name'],
         'description' =>  $_POST['description'],
      ];
      if (!empty($_FILES['image']['name'])) {
         // Hapus gambar lama jika ada
         $this->deleteOldImage($stock['image']);
         $data['image'] = $this->uploadImage();
      }
      if ($this->model('StockModel')->editStock($data) > 0) {
         Flasher::setFlash('Success', 'Update stock successfully', 'success');
         header('Location: ' . BASEURL . '/stock');
         exit;
      } else {
         Flasher::setFlash('Failed', 'Update stock failed', 'danger');
         header('Location: ' . BASEURL . '/stock');
         exit;
      }
   }

   private function deleteOldImage($filename)
   {
      $target_dir = "../public/storage/";
      $file_path = $target_dir . $filename;

      // Periksa apakah file ada sebelum dihapus
      if (file_exists($file_path)) {
         unlink($file_path); // Hapus file gambar lama
      }
   }

   private function uploadImage()
   {
      $target_dir = "../public/storage/";
      // Generate nama file unik
      $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
      $unique_name = uniqid() . '.' . $file_extension;
      $target_file = $target_dir . $unique_name;
      move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
      return $unique_name;
   }

   // delete data
   public function delete($id)
   {
      if (isset($_POST['_token']) && hash_equals($_SESSION['csrf_token'], $_POST['_token'])) {
         if ($this->model('StockModel')->deleteStock($id) > 0) {
            Flasher::setFlash('Success', 'Delete stock successfully', 'success');
            header('Location: ' . BASEURL . '/stock');
            exit;
         } else {
            Flasher::setFlash('Failed', 'Delete stock failed', 'danger');
            header('Location: ' . BASEURL . '/stock');
            exit;
         }
      }
   }

   public function export()
   {
      $this->view('stock/export');
   }
}
