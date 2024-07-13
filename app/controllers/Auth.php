<?php

class Auth extends Controller
{
   public function login()
   {

      if (isset($_SESSION['user_id'])) {
         header('Location: ' . BASEURL . '/');
         exit;
      }
      $data['title'] = 'Login';
      $this->view('auth/auth', $data);
   }

   public function register()
   {
      $data['title'] = 'Sign Up';
      if (isset($_SESSION['user_id'])) {
         header('Location: ' . BASEURL . '/');
         exit;
      }
      $this->view('auth/register', $data);
   }

   public function signinUser()
   {

      if (!session_id()) {
         session_start();
      }
      $data = [
         'email' => $_POST['email'],
         'password' => $_POST['password'],
      ];

      // check user
      $user = $this->model('User')->checkUser($data);

      if (!$user) {
         Flasher::setFlash('Failed', 'Invalid email or password.', 'danger');
         header('Location: ' . BASEURL . '/auth/login');
         exit;
      }

      // validasi password
      $isPasswordMatch = password_verify($data['password'], $user['password']);
      if (!$isPasswordMatch) {
         Flasher::setFlash('Failed', 'Password do not match.', 'danger');
         header('Location: ' . BASEURL . '/auth/login');
         exit;
      }

      if ($this->model('User')->loginUser($user) > 0) {
         $_SESSION['user_id'] = $user['id'];
         $_SESSION['user_name'] = $user['name'];
         $_SESSION['user_email'] = $user['email'];
         header('Location: ' . BASEURL . '/');
         exit;
      } else {
         Flasher::setFlash('Failed', 'Invalid email or password.', 'danger');
         header('Location: ' . BASEURL . '/auth/login');
         exit;
      }
   }

   public function registerUser()
   {
      $data = [
         'name' => $_POST['name'],
         'email' => $_POST['email'],
         'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
      ];
      if ($this->model('User')->checkUser($data)) {
         Flasher::setFlash('Failed', 'User or email already exists.', 'danger');
         header('Location: ' . BASEURL . '/auth/register');
         exit;
      } else {
         if ($this->model('User')->createUser($data) > 0) {
            Flasher::setFlash('Success', 'Registered user successfully.', 'danger');
            header('Location: ' . BASEURL . '/auth/login');
            exit;
         } else {
            Flasher::setFlash('Failed', 'Error register user', 'danger');
            header('Location: ' . BASEURL . '/auth/register');
            exit;
         }
      }
   }
   public function logout()
   {
      session_start();
      // Hapus semua variabel session
      session_unset();
      session_destroy();
      header('Location: ' . BASEURL . '/auth/login');
   }
}
