<html>

<head>
   <title>Stock Barang</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
   <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<body>
   <div class="container">
      <h2 class="text-center py-5">Stock Barang</h2>
      <div class="data-tables datatable-dark">

         <!-- Masukkan table nya disini, dimulai dari tag TABLE -->
         <table id="dataStock" class="table">
            <thead>
               <tr>
                  <th scope="col">No</th>
                  <th scope="col">Name</th>
                  <th scope="col">Description</th>
                  <th scope="col">Stock</th>
               </tr>
            </thead>
         </table>
      </div>
   </div>

   <script>
      let baseUrl = "http://localhost/gudangapp";
      $(document).ready(function() {
         $('#dataStock').DataTable({
            processing: true,
            autoWidth: false,
            ajax: `${baseUrl}/stock/getStocks`,
            dom: 'Bfrtip',
            buttons: [
               'excel', 'pdf', 'print'
            ],
            columns: [{
                  data: "DT_RowIndex",
                  searchable: false,
                  sortable: false,
               },
               {
                  data: "name"
               },
               {
                  data: "description"
               },
               {
                  data: "stock"
               },
            ],
         });
      });
   </script>

   <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
   <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
   <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
   <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>



</body>

</html>