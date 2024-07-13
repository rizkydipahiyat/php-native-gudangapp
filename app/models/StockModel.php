<?php

class StockModel
{
   private $db;

   public function __construct()
   {
      $this->db = new Database;
   }

   public function getAllStock()
   {
      $query = "SELECT * FROM stocks";
      $this->db->query($query);
      return $this->db->resultSet();
   }

   public function getStockById($id)
   {
      $query = "SELECT * FROM stocks WHERE id=:id";
      $this->db->query($query);
      $this->db->bind('id', $id);
      return $this->db->single();
   }

   public function addStock($data)
   {
      $query = "INSERT INTO stocks (name, description, image, stock) 
             VALUES (:name, :description, :image, :stock)";
      $this->db->query($query);
      $this->db->bind('name', $data['name']);
      $this->db->bind('description', $data['description']);
      $this->db->bind('stock', $data['stock']);
      $this->db->bind('image', $data['image']);

      $this->db->execute();

      return $this->db->rowCount();
   }

   public function editStock($data)
   {
      $query = "UPDATE stocks SET name = :name, description = :description, stock = :stock";
      if (isset($data['image'])) {
         $query .= ", image = :image";
      }
      $query .= " WHERE id = :id";
      $this->db->query($query);
      $this->db->bind('name', $data['name']);
      $this->db->bind('description', $data['description']);
      $this->db->bind('stock', $data['stock']);
      if (isset($data['image'])) {
         $this->db->bind('image', $data['image']);
      }
      $this->db->bind('id', $data['id']);

      $this->db->execute();

      return $this->db->rowCount();
   }

   public function deleteStock($id)
   {
      $query = "DELETE FROM stocks WHERE id=:id";
      $this->db->query($query);
      $this->db->bind('id', $id);
      $this->db->execute();
      $this->db->rowCount();
   }

   public function updateStockById($id, $newStock)
   {
      $query = "UPDATE stocks SET stock = :stock WHERE id = :id";
      $this->db->query($query);
      $this->db->bind('stock', $newStock);
      $this->db->bind('id', $id);
      $this->db->execute();
      return $this->db->rowCount();
   }
}
