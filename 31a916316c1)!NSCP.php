<?php
session_start();

// Vérifiez le HTTP_REFERER pour une sécurité supplémentaire
if (!isset($_SERVER['HTTP_REFERER'])) {

    // Générez un token CSRF si aucun n'existe
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    // Vérifiez que la requête POST contient le bon token CSRF
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';
        $hashed_password = password_hash('okreizyouareinforaweek', PASSWORD_DEFAULT);
        if (hash_equals($_SESSION['csrf_token'], $csrf_token)) {
            if ($username === 'iwannaenterplsletmeinimreiz' && password_verify($password, $hashed_password)) {
                $_SESSION['iwannaenterplsletmeinimreiz'] = 'connected';
                header('Location: /pages/admin/dashboard.php');
                exit;
            } else {
                $error = 'Identifiants incorrects.';
            }
        } else {
            $error = 'Token CSRF invalide.';
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';

    if (hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        if ($username === 'iwannaenterplsletmeinimreiz' && $password === 'okreizyouareinforaweek') {  // À remplacer par password_verify pour plus de sécurité
            $_SESSION['iwannaenterplsletmeinimreiz'] = true;
            header('Location:  /pages/admin/dashboard.php');
            exit;
        } else {
            $error = 'Identifiants incorrects.';
        }
    } else {
        $error = 'Token CSRF invalide.';
    }
} else {
    header('Location: /index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="/assets/css/login.css">
</head>

<body>

    <?php if (isset($error)) : ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <main>
        <!-- Utilisez le nom du fichier PHP pour l'attribut action -->
        <form action="" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" id="username" name="username">

            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password">

            <button type="submit">Se connecter</button>
        </form>
    </main>

</body>

</html>