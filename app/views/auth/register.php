<!DOCTYPE html>
<html lang="en">

<head>

   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="">
   <meta name="author" content="">

   <title><?= $data['title']; ?></title>

   <!-- Custom fonts for this template-->
   <link href="<?= BASEURL; ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
   <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

   <!-- Custom styles for this template-->
   <link href="<?= BASEURL; ?>/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

   <div class="container">
      <!-- Outer Row -->
      <div class="row justify-content-center">

         <div class="col-lg-6 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
               <div class="card-body p-0">
                  <!-- Nested Row within Card Body -->
                  <div class="p-5">
                     <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Form Register</h1>
                     </div>
                     <div class="col-lg-12">
                        <?php Flasher::flash(); ?>
                     </div>
                     <form action="<?= BASEURL; ?>/auth/registerUser" method="POST" class="user">
                        <div class="form-group">
                           <input type="text" class="form-control form-control-user" id="name" name="name" placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                           <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Enter Email">
                        </div>
                        <div class="form-group">
                           <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                        </div>

                        <button type="submit" class="btn btn-primary btn-user btn-block">
                           Sign Up
                        </button>
                     </form>
                     <hr>
                     <div class="text-center">
                        <a class="small" href="<?= BASEURL; ?>/auth/login">Already have an account!</a>
                     </div>
                  </div>
               </div>
            </div>

         </div>

      </div>

   </div>

   <!-- Bootstrap core JavaScript-->
   <script src="<?= BASEURL; ?>/vendor/jquery/jquery.min.js"></script>
   <script src="<?= BASEURL; ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

   <!-- Core plugin JavaScript-->
   <script src="<?= BASEURL; ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

   <!-- Custom scripts for all pages-->
   <script src="<?= BASEURL; ?>/js/sb-admin-2.min.js"></script>

</body>

</html>