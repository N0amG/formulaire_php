<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Partenariat Commercial</title>
    <link rel="stylesheet" href="style.css"/>
    <script src="scripts/script.js" defer></script>
</head>
<body>
    <header>
        <div id="theme-switcher-container">
            <button type="button" id="theme-switcher">Mode Sombre</button>
        </div>
        <div id="menu-container">
            <a href="index.php" class="menu-button">Menu</a>
        </div>
    <?php
    // Si on se trouve sur la page display_contract.php, rajouter le bouton d'édition
    if (basename($_SERVER['PHP_SELF']) == 'display_contract.php') {
        echo '
        <div id="edit-button-container">
            <a href="edit_contract.php?id=' . $formId . '" class="edit-button">Édition</a>
        </div>';
        
        // Afficher un bouton pour imprimer le contrat qui redirige sur la page gen_pdf.php avec en paramètre GET l'id du contrat
        echo '
        <div id="print-button-container">
            <a href="gen_pdf.php?id=' . $formId . '" class="print-button">Imprimer</a>
        </div>';
    }
?>
    </header>
    <main>