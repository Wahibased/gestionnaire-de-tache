<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestionnaire de Tâches - Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Gestionnaire De Tâches</h1>
    <div class="container">
        <h1>Créez vos Tâches</h1>
        <form method="POST" action="auth.php">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit" name="action" value="login">Se connecter</button>
            <button type="submit" name="action" value="register">Créer un compte</button>
        </form>
    </div>
    <script src="app.js"></script>
</body>
</html>





