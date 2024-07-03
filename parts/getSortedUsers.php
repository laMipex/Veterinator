<?php
session_start();
require "../db_config.php";
require "functions.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$search = $data['search'] ?? '';
$sort = $data['sort'] ?? ['field' => 'user_fname', 'order' => 'asc'];

$queryString = "SELECT * FROM users WHERE active = 1 AND is_banned = 0";
$params = [];

if (!empty($search)) {
    if (is_numeric($search)) {
        $queryString .= " AND negative_points = :negative_points";
        $params[':negative_points'] = $search;
    } else {
        if (strpos($search, ' ') !== false) {
            $terms = explode(' ', $search);
            if (count($terms) > 1) {
                $queryString .= " AND (user_fname LIKE :fname AND user_lname LIKE :lname)";
                $params[':fname'] = '%' . $terms[0] . '%';
                $params[':lname'] = '%' . $terms[1] . '%';
            } else {
                $queryString .= " AND (user_fname LIKE :search OR user_lname LIKE :search)";
                $params[':search'] = '%' . $search . '%';
            }
        } else {
            $queryString .= " AND (user_fname LIKE :fname OR user_lname LIKE :lname)";
            $params[':fname'] = '%' . $search . '%';
            $params[':lname'] = '%' . $search . '%';
        }
    }
}

$queryString .= " ORDER BY {$sort['field']} {$sort['order']}";

$query = $pdo->prepare($queryString);
$query->execute($params);
$users = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($users);
?>
