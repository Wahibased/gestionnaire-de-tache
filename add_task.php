<?php
require 'vendor/autoload.php'; // Charge le package MongoDB

// Inclure la connexion à la base de données MySQL
session_start();
include 'db.php';
include 'mongo.js';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Préparer et exécuter la requête d'insertion dans MySQL
    $stmt = $pdo->prepare("INSERT INTO tasks (username, title, description) VALUES (:username, :title, :description)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    

    // Ajouter une notification via l'API Node.js
    $notificationMessage = "Une nouvelle tâche a été ajoutée : $title";
    $url = 'http://localhost:3000/add-notification';
    $data = json_encode(['message' => $notificationMessage]);

    // Utiliser cURL pour appeler l'API Node.js
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    // Redirection vers la page d'accueil pour afficher les tâches
    header('Location: tasks.php');
    exit();
}
?>

<!-- Formulaire pour ajouter une tâche -->
<form method="POST">
    <input type="text" name="title" placeholder="Titre de la tâche" required>
    <textarea name="description" placeholder="Description de la tâche" required></textarea>
    <button type="submit">Ajouter</button>
</form>


