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
   <h4>STOCK LIST</h4>
   <table border="1">
      <tr>
         <th>Name</th>
         <th>Description</th>
         <th>Stock</th>
      </tr>
      <?php foreach ($data['stocks'] as $stock) : ?>
         <tr>
            <td><?= $stock['name']; ?></td>
            <td><?= $stock['description']; ?></td>
            <td><?= $stock['stock']; ?></td>
         </tr>
      <?php endforeach; ?>
   </table>
</body>

</html>