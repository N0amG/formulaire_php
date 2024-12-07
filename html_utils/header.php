<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Partenariat Commercial</title>
    <link rel="stylesheet" href="style.css"/>
    <script src="scripts/script.js" defer></script>
</head>
<body>
    <div id="theme-switcher-container">
        <button type="button" id="theme-switcher">Mode Sombre</button>
    </div>
    <div id="menu-container">
        <a href="index.php" class="menu-button">Menu</a>
    </div>
    <?php 
        //si on se trouve sur la page display_contract.php rajouter le bouton d'edition
        if (basename($_SERVER['PHP_SELF']) == 'display_contract.php') {
            echo '
            <div id="edit-button-container">
                <a href="edit_contract.php?id=' . $formId . '" class="edit-button">Édition</a>
            </div>';
        }
    ?>