<?php
require_once('functions.php');
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Partenariat Commercial</title>
    <link rel="stylesheet" href="style.css" />
    <script src="scripts/script.js" defer></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>

<body>
    <header>
        <div id="nav-button-container">
            <div id="theme-switcher-container">
                <button type="button" class=" button button-medium" id="theme-switcher">Mode Sombre</button>
            </div>
            <div id="menu-container">
                <a href="index.php" class="menu-button button button-small page-header">Menu</a>
            </div>
            <?php
            // Si on se trouve sur la page display_contract.php, rajouter le bouton d'édition
            if (basename($_SERVER['PHP_SELF']) == 'display_contract.php') {
                $formId = isset($_GET['id']) ? $_GET['id'] : 0;

                echo '
            <div id="edit-button-container">
                <a href="edit_contract.php?id='.$formId.'" class="edit-button button button-small page-header">Édition</a>
            </div>';
            echo '
            <div id="delete-button-container">
                <a href="delete_contract.php?id='.$formId.'" class="delete-button button button-small page-header" onclick="return confirmDelete()">Supprimer</a>
            </div>';
                // Afficher un bouton pour imprimer le contrat qui redirige sur la page gen_pdf.php avec en paramètre GET l'id du contrat
                echo '
            <div id="print-button-container">
                <a href="gen_pdf.php?id='.$formId.'" class="print-button button button-small page-header">Imprimer</a>
            </div>';
            } ?>
        </div>
    </header>
    <main>