<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $data['title']; ?></title>
   <style>
      table {
         font-family: arial, sans-serif;
         border-collapse: collapse;
         width: 100%;
      }

      td,
      th {
         border: 1px solid #dddddd;
         text-align: left;
         padding: 8px;
      }

      tr:nth-child(even) {
         background-color: #dddddd;
      }
   </style>
</head>

<body>
   <center>
      <h4>LIST TOTAL BARANG MASUK PER HARI DALAM 1 BULAN</h4>
   </center>
   <table border="1">
      <thead>
         <tr>
            <th>Name</th>
            <th>Tanggal</th>
            <th>Total Qty</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($data['barang_in'] as $row) : ?>
            <tr>
               <?php foreach ($row as $col) : ?>
                  <td><?= $col; ?></td>
               <?php endforeach; ?>
            </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</body>

</html>