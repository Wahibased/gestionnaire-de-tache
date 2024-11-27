<?php
//Fonction pour récupérer les notifications depuis l'API Node.js
function getNotifications() {
    $url = 'http://localhost:3000/notification'; // L'URL de votre API Node.js

    // Initialisation de cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    
    // Exécuter la requête cURL et obtenir la réponse
    $response = curl_exec($ch);
    
    // Vérifier si cURL a retourné une erreur
    if (curl_errno($ch)) {
        echo 'Erreur cURL : ' . curl_error($ch);
    }
    
    curl_close($ch);

    // Décoder la réponse JSON pour récupérer les notifications
    return json_decode($response, true); // Retourne un tableau associatif
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionnaire de Tâches</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Gestionnaire de Tâches</h1>
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
                <!-- Les tâches seront injectées ici par PHP -->
                <?php
                // Inclusion du fichier pour récupérer et afficher les tâches
                include('fetch_tasks.php');
                ?>
            </tbody>
        </table>
        <!-- Affichage des notifications -->
        <h3>Notifications</h3>
        <div class="notifications">
            <?php
            // Affichage des notifications depuis MongoDB
           // Vérifier que les notifications existent et sont un tableau
    if (!empty($notifications) && is_array($notifications)) {
        foreach ($notifications as $notification) {
            // Assurez-vous que le message et le timestamp existent
            $message = $notification['message'] ?? 'Message non disponible';
            $timestamp = isset($notification['timestamp'])
                ? date('Y-m-d H:i:s', strtotime($notification['timestamp']))
                : 'Date non disponible';

            echo "<p>{$message} - {$timestamp}</p>";
        }
    } else {
        echo "<p>Aucune notification trouvée.</p>";
    }
            ?>
        </div>
    </div>
    <script src="app.js"></script>
</body>
</html>



