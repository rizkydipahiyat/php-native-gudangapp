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