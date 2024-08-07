<?php

use Dompdf\Dompdf;

class Barang extends Controller
{
   public function in()
   {
      $stocks = $this->model('StockModel')->getAllStock();
      $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
      $tanggalAkhir = date('Y-m-d');

      if (isset($_GET['tanggal_awal']) && !empty($_GET['tanggal_awal']) && isset($_GET['tanggal_akhir']) && !empty($_GET['tanggal_akhir'])) {
         $tanggalAwal = $_GET['tanggal_awal'];
         $tanggalAkhir = $_GET['tanggal_akhir'];
      }

      $data = [
         'url' => 'in',
         'title' => 'Barang Masuk',
         'stocks' => $stocks,
         'tanggal_awal' => $tanggalAwal,
         'tanggal_akhir' => $tanggalAkhir,
      ];
      $this->view('layouts/header', $data);
      $this->view('barang_in/index', $data);
      $this->view('layouts/footer');
   }

   public function getDataBarangIn($awal, $akhir)
   {
      $no = 1;
      $data = [];

      $tanggalAwal = strtotime($awal);
      $tanggalAkhir = strtotime($akhir);

      while ($tanggalAwal <= $tanggalAkhir) {
         $tanggal = date('Y-m-d', $tanggalAwal);

         $totalQty = 0;
         $results = $this->model('BarangModel')->getBarangInDate($tanggal);

         foreach ($results as $result) {
            $totalQty += $result['qty'];
         }

         // Buat baris data untuk tanggal tersebut
         $row = [];
         $row['DT_RowIndex'] = $no++;
         $row['tanggal'] = $tanggal;
         $row['total_qty'] = $totalQty;

         // Tambahkan baris ke array data
         $data[] = $row;

         // Tambahkan satu hari ke tanggalAwal
         $tanggalAwal = strtotime("+1 day", $tanggalAwal);
      }

      return $data;
   }

   public function exportPDF($awal, $akhir)
   {
      $barang_in = $this->getDataBarangIn($awal, $akhir);
      $data = [
         'title' => 'Stock List',
         'barang_in' => $barang_in,
         'awal' => $awal,
         'akhir' => $akhir
      ];
      // Path ke file view
      $viewPath = __DIR__ . '/../views/barang_in/export_pdf.php';

      // Muat view dan dapatkan HTML sebagai string
      $html = $this->loadView($viewPath, $data);
      $pdf = new Dompdf();
      $pdf->loadHtml($html);
      $pdf->setPaper('A4', 'potrait');
      $pdf->render();
      $pdf->stream('barang_in.pdf' . date('Y-m-d-his') . '.pdf', array("Attachment" => false));
   }

   public function getBarangIn()
   {
      $barang_in = $this->model('BarangModel')->getAllBarangIn();
      $data = [];
      $no = 1;
      foreach ($barang_in as $in) {
         $row = [
            'DT_RowIndex' => $no++,
            'name' => $in['name'],
            'penerima' => $in['penerima'],
            'tanggal' => $in['tanggal'],
            'qty' => $in['qty'],
            'action' => '
                     <button onclick="editFormBarangIn(\'' . BASEURL . '/barang/updateBarangIn/' . $in['id'] . '\')" class="btn btn-sm btn-info btn-flat">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button onclick="deleteDataBarangIn(\'' . BASEURL . '/barang/deleteBarangIn/' . $in['id'] . '\')" class="btn btn-sm btn-danger btn-flat">
                        <i class="fa fa-trash"></i>
                    </button>
            '
         ];
         $data[] = $row;
      }
      header('Content-Type: application/json');
      echo json_encode(['data' => $data]);
   }

   public function filterDateBarangIn()
   {
   }

   public function editBarangIn($id)
   {
      $barang_in = $this->model('BarangModel')->getBarangInById($id);
      header('Content-Type: application/json');
      echo json_encode($barang_in);
   }

   public function updateBarangIn($id)
   {
      echo json_encode($id);
      $data = [
         'id' => $id,
         'id_stock' => $_POST['id_stock'],
         'penerima' => $_POST['penerima'],
         'qty' =>  $_POST['qty'],
      ];
      // Check Stock
      $getStock = $this->model('StockModel')->getStockById($data['id_stock']);
      $currentStock = $getStock['stock'];

      // Check current qty di table barang_in
      $getQty = $this->model('BarangModel')->getBarangInById($data['id']);
      $currentQty = $getQty['qty'];

      $difference = $data['qty'] - $currentQty;

      if ($difference > 0) {
         $newStock = $currentStock + $difference;
      } else {
         $newStock = $currentStock + $difference;
      }

      $updateStock = $this->model('StockModel')->updateStockById($data['id_stock'], $newStock);

      if ($updateStock && $this->model('BarangModel')->editBarangIn($data) > 0) {
         Flasher::setFlash('Success', 'Update barang in successfully', 'success');
         header('Location: ' . BASEURL . '/barang/in');
         exit;
      } else {
         Flasher::setFlash('Failed', 'Update barang in failed', 'danger');
         header('Location: ' . BASEURL . '/barang/in');
         exit;
      }
   }

   public function addBarangIn()
   {
      $data = [
         'id_stock' => $_POST['id_stock'],
         'penerima' => $_POST['penerima'],
         'qty' => $_POST['qty'],
      ];
      // Check Stock
      $getStock = $this->model('StockModel')->getStockById($data['id_stock']);
      $currentStock = $getStock['stock'];

      $newStock = $data['qty'] + $currentStock;

      $updateStock = $this->model('StockModel')->updateStockById($data['id_stock'], $newStock);
      // Store to DB
      if ($updateStock && $this->model('BarangModel')->storeBarangIn($data) > 0) {
         Flasher::setFlash('Success', 'Add barang in successfully', 'success');
         header('Location: ' . BASEURL . '/barang/in');
         exit;
      } else {
         Flasher::setFlash('Failed', 'Add barang masuk failed', 'danger');
         header('Location: ' . BASEURL . '/barang/in');
         exit;
      }
   }

   public function deleteBarangIn($id)
   {
      if (isset($_POST['_token']) && hash_equals($_SESSION['csrf_token'], $_POST['_token'])) {
         $barangIn = $this->model('BarangModel')->getBarangInById($id);
         $qtyToDelete = $barangIn['qty'];
         $id_stock = $barangIn['id_stock'];
         // Ambil stok saat ini dari tabel stocks
         $getStock = $this->model('StockModel')->getStockById($id_stock);
         $currentStock = $getStock['stock'];
         // Kurangi stok dengan qty yang dihapus
         $newStock = $currentStock - $qtyToDelete;
         // Update stok di tabel stocks
         $updateStock = $this->model('StockModel')->updateStockById($id_stock, $newStock);

         if ($updateStock && $this->model('BarangModel')->deleteBarangIn($id) > 0) {
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

   // Barang Keluar
   public function out()
   {
      $stocks = $this->model('StockModel')->getAllStock();

      $data = [
         'url' => 'out',
         'title' => 'Barang Keluar',
         'stocks' => $stocks
      ];
      $this->view('layouts/header', $data);
      $this->view('barang_out/index', $data);
      $this->view('layouts/footer');
   }

   public function getBarangOut()
   {
      $barang_out = $this->model('BarangModel')->getAllBarangOut();
      $data = [];
      $no = 1;
      foreach ($barang_out as $out) {
         $row = [
            'DT_RowIndex' => $no++,
            'name' => $out['name'],
            'penerima' => $out['penerima'],
            'tanggal' => $out['tanggal'],
            'qty' => $out['qty'],
            'action' => '
                     <button onclick="editFormBarangOut(\'' . BASEURL . '/barang/updateBarangOut/' . $out['id'] . '\')" class="btn btn-sm btn-info btn-flat">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button onclick="deleteDataBarangOut(\'' . BASEURL . '/barang/deleteBarangOut/' . $out['id'] . '\')" class="btn btn-sm btn-danger btn-flat">
                        <i class="fa fa-trash"></i>
                    </button>
            '
         ];
         $data[] = $row;
      }
      header('Content-Type: application/json');
      echo json_encode(['data' => $data]);
   }

   public function addBarangOut()
   {
      $data = [
         'id_stock' => $_POST['id_stock'],
         'penerima' => $_POST['penerima'],
         'qty' => $_POST['qty'],
      ];
      // Check Stock
      $getStock = $this->model('StockModel')->getStockById($data['id_stock']);
      $currentStock = $getStock['stock'];

      if ($data['qty'] > $currentStock) {
         Flasher::setFlash('Failed', 'Stock tersisa yang tersisa saat ini ' . $currentStock, 'danger');
         header('Location: ' . BASEURL . '/barang/out');
         exit;
      }

      $newStock = $currentStock - $data['qty'];

      $updateStock = $this->model('StockModel')->updateStockById($data['id_stock'], $newStock);
      // Store to DB
      if ($updateStock && $this->model('BarangModel')->storeBarangOut($data) > 0) {
         Flasher::setFlash('Success', 'Add barang out successfully', 'success');
         header('Location: ' . BASEURL . '/barang/out');
         exit;
      } else {
         Flasher::setFlash('Failed', 'Add barang keluar failed', 'danger');
         header('Location: ' . BASEURL . '/barang/out');
         exit;
      }
   }
   public function editBarangOut($id)
   {
      $barang_out = $this->model('BarangModel')->getBarangOutById($id);
      header('Content-Type: application/json');
      echo json_encode($barang_out);
   }

   public function updateBarangOut($id)
   {
      echo json_encode($id);
      $data = [
         'id' => $id,
         'id_stock' => $_POST['id_stock'],
         'penerima' => $_POST['penerima'],
         'qty' =>  $_POST['qty'],
      ];
      // Check Stock
      $getStock = $this->model('StockModel')->getStockById($data['id_stock']);
      $currentStock = $getStock['stock'];

      if ($data['qty'] > $currentStock) {
         Flasher::setFlash('Failed', 'Stock tersisa yang tersisa saat ini ' . $currentStock, 'danger');
         header('Location: ' . BASEURL . '/barang/out');
         exit;
      }


      // Check current qty di table barang_in
      $getQty = $this->model('BarangModel')->getBarangOutById($data['id']);
      $currentQty = $getQty['qty'];

      $difference = $currentQty - $data['qty'];

      if ($difference > 0) {
         $newStock = $currentStock + $difference;
      } else {
         $newStock = $currentStock + $difference;
      }

      $updateStock = $this->model('StockModel')->updateStockById($data['id_stock'], $newStock);

      if ($updateStock && $this->model('BarangModel')->editBarangOut($data) > 0) {
         Flasher::setFlash('Success', 'Update barang in successfully', 'success');
         header('Location: ' . BASEURL . '/barang/out');
         exit;
      } else {
         Flasher::setFlash('Failed', 'Update barang in failed', 'danger');
         header('Location: ' . BASEURL . '/barang/out');
         exit;
      }
   }

   public function deleteBarangOut($id)
   {
      if (isset($_POST['_token']) && hash_equals($_SESSION['csrf_token'], $_POST['_token'])) {
         $barangOut = $this->model('BarangModel')->getBarangOutById($id);
         $qtyToDelete = $barangOut['qty'];
         $id_stock = $barangOut['id_stock'];
         // Ambil stok saat ini dari tabel stocks
         $getStock = $this->model('StockModel')->getStockById($id_stock);
         $currentStock = $getStock['stock'];
         // Kurangi stok dengan qty yang dihapus
         $newStock = $currentStock - $qtyToDelete;
         // Update stok di tabel stocks
         $updateStock = $this->model('StockModel')->updateStockById($id_stock, $newStock);

         if ($updateStock && $this->model('BarangModel')->deleteBarangOut($id) > 0) {
            Flasher::setFlash('Success', 'Delete barang keluar successfully', 'success');
            header('Location: ' . BASEURL . '/barang/out');
            exit;
         } else {
            Flasher::setFlash('Failed', 'Delete barang keluar failed', 'danger');
            header('Location: ' . BASEURL . '/barang/out');
            exit;
         }
      }
   }
}
