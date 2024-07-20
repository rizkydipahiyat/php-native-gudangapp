<?php

use Dompdf\Dompdf;

class Stock extends Controller
{
   public function index()
   {
      $data['url'] = 'stock';
      $this->view('layouts/header', $data);
      $this->view('stock/index');
      $this->view('layouts/footer');
   }

   // public function getPaginatedStocks()
   // {
   //    $stockModel = $this->model('StockModel');

   //    // Get parameters from query string
   //    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // If id is not provided, set it to a very large number
   //    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2; // Default limit is 10
   //    var_dump($page);
   //    // Validate input
   //    // $page = filter_var($page, FILTER_VALIDATE_INT);
   //    // $limit = filter_var($limit, FILTER_VALIDATE_INT);

   //    // Get records for the current page
   //    $stocks = $stockModel->getRecords($page, $limit);

   //    // Prepare response
   //    // $data = [
   //    //    "data" => $stocks
   //    // ];

   //    // // Return JSON response
   //    // header('Content-Type: application/json');
   //    // echo json_encode($data);
   // }

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

   public function exportPDF()
   {
      $stocks = $this->model('StockModel')->getAllStock();
      $data = [
         'title' => 'Stock List',
         'stocks' => $stocks
      ];
      // Path ke file view
      $viewPath = __DIR__ . '/../views/stock/export_pdf.php';

      // Muat view dan dapatkan HTML sebagai string
      $html = $this->loadView($viewPath, $data);
      $pdf = new Dompdf();
      $pdf->loadHtml($html);
      $pdf->setPaper('A4', 'potrait');
      $pdf->render();
      $pdf->stream('stock.pdf' . date('Y-m-d-his') . '.pdf', array("Attachment" => false));
   }

   public function exportExcel()
   {
      $stocks = $this->model('StockModel')->getAllStock();
      $data = [
         'title' => 'Stock List',
         'stocks' => $stocks
      ];
      $this->view('stock/export_excel', $data);
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="stock_data.xls"');
   }
}
