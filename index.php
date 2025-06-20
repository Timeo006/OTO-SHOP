<?php
// Fichier PHP de base pour OTO Shop
// Vous pouvez ajouter ici vos traitements côté serveur (ex : gestion de formulaires, connexion à une base de données, etc.)

session_start();
// Fonctions cookie PHP
function setCookiePHP($name, $value, $minutes) {
    $expire = time() + ($minutes * 60);
    setcookie($name, $value, $expire, "/");
}
function getCookiePHP($name) {
    return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
}

// Gestion inscription/connexion (stockage en session pour la démo)
$message = '';
if (isset($_POST['action'])) {
    if ($_POST['action'] === 'register') {
        $user = trim($_POST['username'] ?? '');
        $pass = trim($_POST['password'] ?? '');
        if ($user && $pass) {
            $_SESSION['users'][$user] = password_hash($pass, PASSWORD_DEFAULT);
            $message = "Compte créé. Connectez-vous.";
        } else {
            $message = "Veuillez remplir tous les champs.";
        }
    } elseif ($_POST['action'] === 'login') {
        $user = trim($_POST['username'] ?? '');
        $pass = trim($_POST['password'] ?? '');
        if (isset($_SESSION['users'][$user]) && password_verify($pass, $_SESSION['users'][$user])) {
            $_SESSION['logged_user'] = $user;
            setCookiePHP('user', $user, 120);
        } else {
            $message = "Identifiants incorrects.";
        }
    } elseif ($_POST['action'] === 'logout') {
        unset($_SESSION['logged_user']);
        setCookiePHP('user', '', -1);
    }
}

// Affichage HTML
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>OTO Shop - Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="ax">
<?php if (isset($_SESSION['logged_user'])): ?>
    <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['logged_user']); ?> !</h2>
    <form method="post">
        <input type="hidden" name="action" value="logout">
        <button type="submit">Se déconnecter</button>
    </form>
<?php else: ?>
    <h2>Connexion</h2>
    <?php if ($message) echo '<div style="color:#c00;">'.$message.'</div>'; ?>
    <form method="post" style="margin-bottom:18px;">
        <input type="hidden" name="action" value="login">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required><br><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br><br>
        <button type="submit">Se connecter</button>
    </form>
    <h3>Créer un compte</h3>
    <form method="post">
        <input type="hidden" name="action" value="register">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required><br><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br><br>
        <button type="submit">Créer le compte</button>
    </form>
<?php endif; ?>
</div>
</body>
</html>
