<?php
// list_tasks.php
require 'db.php';

// Récupérer les tâches de l'utilisateur (via un paramètre GET)
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Requête pour récupérer les tâches de l'utilisateur
    $sql = "SELECT * FROM tasks WHERE user_id = :user_id ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Renvoyer les résultats sous forme de JSON
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($tasks);
} else {
    echo json_encode(['success' => false, 'message' => 'ID utilisateur manquant']);
}