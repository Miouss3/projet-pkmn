<?php
namespace App\Models;
class Category
{
    private $db;
    private $table = 'categories';

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY name ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}