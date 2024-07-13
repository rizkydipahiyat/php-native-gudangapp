<?php

class User
{
   private $db;

   public function __construct()
   {
      $this->db = new Database;
   }

   public function checkUser($data)
   {
      $query = "SELECT * FROM users WHERE email=:email";
      $this->db->query($query);
      $this->db->bind('email', $data['email']);
      return $this->db->single();
   }

   public function createUser($data)
   {
      $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
      $this->db->query($query);
      $this->db->bind('name', $data['name']);
      $this->db->bind('email', $data['email']);
      $this->db->bind('password', $data['password']);

      $this->db->execute();
      return $this->db->rowCount();
   }

   public function loginUser($data)
   {
      $query = "SELECT * FROM users WHERE email=:email AND password=:password";
      $this->db->query($query);
      $this->db->bind('email', $data['email']);
      $this->db->bind('password', $data['password']);

      $this->db->execute();

      return $this->db->rowCount();
   }
}
