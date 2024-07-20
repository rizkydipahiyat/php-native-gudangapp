<div class="container-fluid">

   <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#formModalBarangIn">Tambah Barang Masuk</button>
   <div class="mt-2">
      <?php Flasher::flash(); ?>
   </div>
   <div class="card">
      <div class="card-header">
         List Barang Masuk
      </div>
      <div class="card-body">
         <div class="row mb-3">
            <button onclick="editPeriode()" class="btn btn-sm btn-success" data-toggle="modal" data-target="#formModalBarangInDate">
               <i class="fa fa-plus-circle"></i> Ubah Periode
            </button>
            <a href="<?= BASEURL; ?>/barang/exportPDF/<?= urlencode($data['tanggal_awal']); ?>/<?= urlencode($data['tanggal_akhir']); ?>" target="_blank" class="btn btn-sm btn-info  ml-2">
               <i class="fa fa-file-pdf"></i> Export PDF
            </a>
         </div>
         <table id="itemBarangIn" class="table">
            <thead>
               <tr>
                  <th scope="col">No</th>
                  <th scope="col">Barang</th>
                  <th scope="col">Penerima</th>
                  <th scope="col">Tanggal</th>
                  <th scope="col">Qty</th>
                  <th scope="col">Action</th>
               </tr>
            </thead>
         </table>
      </div>
   </div>
</div>


<!-- Logout Modal-->
<div class="modal fade" id="formModalBarangIn" tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="titleModal">Tambah Barang Masuk</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="<?= BASEURL; ?>/barang/addBarangIn" method="post">
               <input type="hidden" id="id" name="id">
               <div class="form-group">
                  <label for="penerima">Penerima</label>
                  <input type="text" id="penerima" name="penerima" class="form-control" placeholder="Masukan nama penerima" autofocus>
               </div>
               <div class="form-group">
                  <label for="id_stock">Choose Item</label>
                  <select name="id_stock" id="id_stock" class="form-control">
                     <?php foreach ($data['stocks'] as $index => $stock) : ?>
                        <option value="<?= $stock['id'] ?>"><?= $stock['name'] ?></option>
                     <?php endforeach; ?>
                  </select>
               </div>
               <div class="form-group">
                  <label for="qty">Qty</label>
                  <input type="number" id="qty" name="qty" class="form-control">
               </div>
               <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="formModalBarangInDate" tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="titleModal">Edit Periode</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="<?= BASEURL; ?>/barang/in" method="get">
               <div class="form-group">
                  <label for="tanggal_awal">Tanggal Awal</label>
                  <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control" autofocus required value="<?= $data['tanggal_awal'] ?>">
               </div>
               <div class="form-group">
                  <label for="tanggal_akhir">Tanggal Akhir</label>
                  <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control" value="<?= $data['tanggal_akhir'] ?>">
               </div>
               <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
         </div>
      </div>
   </div>
</div>