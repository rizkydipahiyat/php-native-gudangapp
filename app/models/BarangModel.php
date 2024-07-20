<?php

class BarangModel
{
   private $db;

   public function __construct()
   {
      $this->db = new Database;
   }

   public function getBarangInDate($tanggal)
   {
      $query = "SELECT qty FROM barang_in WHERE DATE(tanggal) = :tanggal";
      $this->db->query($query);
      $this->db->bind('tanggal', $tanggal);
      return $this->db->resultSet();
   }

   public function storeBarangIn($data)
   {
      $query = "INSERT INTO barang_in (id_stock, penerima, qty) VALUES (:id_stock, :penerima, :qty)";
      $this->db->query($query);
      $this->db->bind('id_stock', $data['id_stock']);
      $this->db->bind('penerima', $data['penerima']);
      $this->db->bind('qty', $data['qty']);

      $this->db->execute();

      return $this->db->rowCount();
   }

   public function getAllBarangIn()
   {
      $query = "SELECT masuk.id, masuk.id_stock, masuk.tanggal, masuk.penerima, masuk.qty, stock.name, stock.description, stock.image 
             FROM barang_in masuk
             INNER JOIN stocks stock ON stock.id = masuk.id_stock";
      $this->db->query($query);
      return $this->db->resultSet();
   }

   public function getBarangInById($id)
   {
      $query = "SELECT * FROM barang_in WHERE id=:id";
      $this->db->query($query);
      $this->db->bind('id', $id);
      return $this->db->single();
   }

   public function editBarangIn($data)
   {
      $query = "UPDATE barang_in SET id_stock = :id_stock, penerima = :penerima, qty = :qty WHERE id = :id";
      $this->db->query($query);
      $this->db->bind('id_stock', $data['id_stock']);
      $this->db->bind('penerima', $data['penerima']);
      $this->db->bind('qty', $data['qty']);
      $this->db->bind('id', $data['id']);

      $this->db->execute();

      return $this->db->rowCount();
   }

   public function deleteBarangIn($id)
   {
      $query = "DELETE FROM barang_in WHERE id=:id";
      $this->db->query($query);
      $this->db->bind('id', $id);
      $this->db->execute();
      $this->db->rowCount();
   }

   // Barang Keluar
   public function getAllBarangOut()
   {
      $query = "SELECT keluar.id, keluar.id_stock, keluar.tanggal, keluar.penerima, keluar.qty, stock.name, stock.description, stock.image 
             FROM barang_out keluar
             INNER JOIN stocks stock ON stock.id = keluar.id_stock";
      $this->db->query($query);
      return $this->db->resultSet();
   }

   public function storeBarangOut($data)
   {
      $query = "INSERT INTO barang_out (id_stock, penerima, qty) VALUES (:id_stock, :penerima, :qty)";
      $this->db->query($query);
      $this->db->bind('id_stock', $data['id_stock']);
      $this->db->bind('penerima', $data['penerima']);
      $this->db->bind('qty', $data['qty']);

      $this->db->execute();

      return $this->db->rowCount();
   }


   public function getBarangOutById($id)
   {
      $query = "SELECT * FROM barang_out WHERE id=:id";
      $this->db->query($query);
      $this->db->bind('id', $id);
      return $this->db->single();
   }

   public function editBarangOut($data)
   {
      $query = "UPDATE barang_out SET id_stock = :id_stock, penerima = :penerima, qty = :qty WHERE id = :id";
      $this->db->query($query);
      $this->db->bind('id_stock', $data['id_stock']);
      $this->db->bind('penerima', $data['penerima']);
      $this->db->bind('qty', $data['qty']);
      $this->db->bind('id', $data['id']);

      $this->db->execute();

      return $this->db->rowCount();
   }


   public function deleteBarangOut($id)
   {
      $query = "DELETE FROM barang_out WHERE id=:id";
      $this->db->query($query);
      $this->db->bind('id', $id);
      $this->db->execute();
      $this->db->rowCount();
   }
}
