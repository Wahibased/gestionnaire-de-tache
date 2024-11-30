<?php

session_start([
    'cookie_lifetime' => 86400, // Durée de vie du cookie (1 jour)
    'cookie_secure' => isset($_SERVER['HTTPS']), // Seulement via HTTPS
    'cookie_httponly' => true, // Inaccessible via JavaScript
    'use_strict_mode' => true, // Empêche les attaques de fixation de session
    'use_trans_sid' => false, // Désactive le passage d'ID de session via URL
]);

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$username = $_SESSION['username'];

require 'db.php'; // Connexion à MySQL
//require 'mongo.js'; // Connexion à MongoDB pour les notifications

// Notifications depuis Node.js
$nodeNotifications = getNotificationsFromNode($username);

// Fonction pour récupérer les notifications via l'API Node.js
function getNotificationsFromNode($username) {
    $url = "http://localhost:3002/notifications?username=" . urlencode($username);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return []; // Retourne une liste vide en cas d'erreur
    }

    curl_close($ch);

    return json_decode($response, true);
}

// Récupérer les notifications pour l'utilisateur connecté
try {
    $nodeNotifications = getNotificationsFromNode($username);
} catch (Exception $e) {
    echo "Erreur lors de la récupération des notifications : " . $e->getMessage();
    $notifications = []; // Défaut si une erreur survient
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestionnaire de Tâches</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


    <div class="container">
        <h1>Bienvenue, <?php echo htmlspecialchars($username); ?> !</h1>
        <a href="logout.php">Déconnexion</a>

        <form id="task-form" action="add_task.php" method="POST">
            <input type="text" id="task-title" name="title" placeholder="Titre de la tâche" required>
            <textarea id="task-description" name="description" placeholder="Description de la tâche" required></textarea>
            <button type="submit">Ajouter</button>
        </form>

        <h2>Tâches en cours</h2>
        <table id="task-list">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE username = ?");
        $stmt->execute([$username]);
        
                while ($task = $stmt->fetch()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($task['title']) . "</td>"; // Afficher le titre de la tâche
                    echo "<td>" . htmlspecialchars($task['description']) . "</td>"; // Afficher la description
                    // Lien pour supprimer une tâche
                    echo "<td><a href='delete_task.php?id=" . urlencode($task['id']) . "'>Supprimer</a></td>";
                    // Lien pour modifier une tâche
                    echo "<td><a href='edit_task.php?id=" . urlencode($task['id']) . "'>Modifier</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <h3>Notifications</h3>
        <div class="notifications">
            <?php
if (!empty($notifications)) {
            foreach ($notifications as $notification) {
                echo "<p>" . htmlspecialchars($notification['message']) . " - " . date('Y-m-d H:i:s', $notification['timestamp']->toDateTime()->getTimestamp()) . "</p>";
            }
            foreach ($nodeNotifications as $nodeNotification) {
                echo "<p>" . htmlspecialchars($nodeNotification['message']) . " - " . htmlspecialchars($nodeNotification['timestamp']) . "</p>";
            }}
            ?>
        </div>
    </div>
    <script src="app.js"></script>
</body>
</html>
