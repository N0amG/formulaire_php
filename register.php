<?php
require_once('functions.php');

$error_message = '';
$success_message = '';

if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['nom']) && isset($_POST['prenom'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];

    // Vérifier si l'utilisateur existe déjà
    $user = getUserByEmail($email);

    if ($user) {
        $error_message = 'Un compte avec cet email existe déjà.';
    } else {
        // Créer un nouveau compte utilisateur
        $query = "INSERT INTO compte (email, nom, prenom, mdp) VALUES (:email, :nom, :prenom, :mdp)";
        $params = [
            ':email' => $email,
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':mdp' => $password
        ];
        if (sqlquery(connectDB(), $query, $params)) {
            echo $query;

            $success_message = 'Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.';
            header('Location: login.php');
            exit();
        } else {
            $error_message = 'Une erreur est survenue lors de la création de votre compte. Veuillez réessayer.';
        }
    }
}

require_once('html_utils/header.php');
?>

<form method="post" action="register.php" id="register-form">
    <div id="register-container">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>
        <br>
        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>
        <br>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Créer un compte</button>
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <br>
        <a href="login.php" id="login-link">Se connecter</a>
    </div>
</form>

<?php require_once('html_utils/footer.php'); ?>