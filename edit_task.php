<?php
// Connexion à la base de données MySQL
$pdo = new PDO('mysql:host=localhost;dbname=gtaches', 'root', '');

// Récupérer l'ID de la tâche à modifier
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les informations de la tâche à modifier
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si la tâche existe
    if ($task) {
        // Mettre à jour la tâche
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'];
            $description = $_POST['description'];

            // Mettre à jour la tâche dans la base de données
            $stmt = $pdo->prepare("UPDATE tasks SET title = :title, description = :description WHERE id = :id");
            $stmt->execute(['title' => $title, 'description' => $description, 'id' => $id]);

            echo "Tâche modifiée avec succès!";
            // Rediriger vers la page principale après modification
            header("Location: index.php");
        }
    } else {
        echo "Tâche non trouvée.";
    }
} else {
    echo "Aucun ID de tâche spécifié.";
}
?>

<!-- Formulaire de modification -->
<form method="POST">
    <input type="text" name="title" value="<?= $task['title'] ?>" required>
    <textarea name="description" required><?= $task['description'] ?></textarea>
    <button type="submit">Modifier</button>
</form>
