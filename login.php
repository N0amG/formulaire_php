<?php
require_once('functions.php');

session_start();

$error_message = '';
$success_message = '';

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = getUserByEmail($email);

    if (passwordVerify($email, $password)) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email']
        ];
        $success_message = 'Vous êtes connecté';
        header('Location: index.php');
        exit();
    } else {
        $error_message = 'Email ou mot de passe incorrect';
    }
}

require_once('html_utils/header.php');
?>

<form method="post" action="login.php" id="login-form">
    <div id="login-container">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Se connecter</button>
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <br>
        <a href="register.php" id="create-account-link">Créer un compte</a>
    </div>
</form>

<?php require_once('html_utils/footer.php'); ?>