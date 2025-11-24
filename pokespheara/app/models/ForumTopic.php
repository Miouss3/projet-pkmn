<?php
namespace App\Models;
class ForumTopic
{
    private $db;
    private $table = 'forum_topics';

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (title, content, category_id, created_at) VALUES (:title, :content, :category_id, :created_at)");
        return $stmt->execute([
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':category_id' => $data['category_id'],
            ':created_at' => $data['created_at'],
        ]);
    }
}
