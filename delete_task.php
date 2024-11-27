<?php
// Connexion à la base de données MySQL
$pdo = new PDO('mysql:host=localhost;dbname=gtaches', 'root', '');

// Récupérer l'ID de la tâche à supprimer
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer la requête SQL pour supprimer la tâche
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute(['id' => $id]);

    echo "Tâche supprimée avec succès!";
    // Rediriger vers la page principale après suppression
    header("Location: index.php");
}
?>
