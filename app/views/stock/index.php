<div class="container-fluid">

   <button class="btn btn-sm btn-primary showEditModal" data-toggle="modal" data-target="#formModal">Tambah Stock</button>
   <div class="mt-2">
      <?php Flasher::flash(); ?>
   </div>
   <div class="card">
      <div class="card-header">
         <div class="row flex-row align-items-center justify-content-between">
            <h5>
               List Stocks
            </h5>
            <a href="<?= BASEURL; ?>/stock/export" class="btn btn-sm btn-warning">Export Data</a>
         </div>
      </div>
      <div class="card-body data-tables">
         <table id="itemStocks" class="table">
            <thead>
               <tr>
                  <th scope="col">No</th>
                  <th scope="col">Name</th>
                  <th scope="col">Description</th>
                  <th scope="col">Stock</th>
                  <th scope="col">Image</th>
                  <th scope="col">Aksi</th>
               </tr>
            </thead>
         </table>
      </div>
   </div>
</div>


<!-- Logout Modal-->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="titleModal" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="titleModal">Tambah Stock</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="<?= BASEURL; ?>/stock/store" method="post" enctype="multipart/form-data">
               <input type="hidden" name="id" id="id">
               <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" id="name" name="name" class="form-control" placeholder="Masukan nama barang" autofocus>
               </div>
               <div class="form-group">
                  <label for="description">Description</label>
                  <input type="text" id="description" name="description" class="form-control" placeholder="Masukan deskripsi barang">
               </div>
               <div class="form-group">
                  <label for="stock">Stock</label>
                  <input type="number" id="stock" name="stock" class="form-control">
               </div>
               <div class="form-group">
                  <label for="image">Upload Image</label>
                  <input type="file" id="image" name="image" class="form-control">
               </div>
               <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
         </div>
      </div>
   </div>
</div>