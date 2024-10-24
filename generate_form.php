<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Dynamique</title>
    <link rel="stylesheet" href="style.css"/>
    <script src="script.js" defer></script>
</head>
<body>

    <h1>Formulaire de Partenariat Commercial Dynamique</h1>
    <div id="theme-switcher-container">
      <button type="button" id="theme-switcher">Mode Sombre</button>
    </div>
    <!-- Génération dynamique des champs après la sélection -->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['numPartners'])) {
        $numPartners = (int)$_POST['numPartners'];  // Nombre de partenaires sélectionné

        echo '<form id="form" action="generate_contract.php" method="post">';
        echo '<legend>Informations sur les Partenaires</legend>';
        echo '<br>';
        echo '<fieldset class="information-section">';
        echo '<br>';
        // Génération des champs pour chaque partenaire
        for ($i = 1; $i <= $numPartners; $i++) {
            echo '<div class="partner-section">';
            
            echo "<label class='partnerNum' for='partner$i'>Partenaire $i:</label>";
            echo "<br>";
            echo '
            <div class="delete-partner-container" id="delete-partner'.$i.'-div">
                <button type="button" class="delete-partner-button">X</button>
            </div>';
            echo "<label for='partner$i'>Nom du Partenaire</label>";
            echo "<input type='text' id='partner$i' name='partner$i' required>";

            echo "<label for='contribution$i'>Contribution du Partenaire</label>";
            echo "<textarea id='contribution$i' name='contribution$i' rows='3' required style='resize: none;'></textarea>";
            echo '<br>';
            echo '<br>';
            echo '</div>';

        }

        echo '</fieldset>';
        echo '<br>';
        echo '<input type="hidden" id="numPartnersInput" name="numPartners" value="' . $numPartners . '">';
        echo '<div id = "bottom-page-container">';
        echo '<input type="submit" value="Soumettre">';
        echo '<button type="button" id="add-partner-button">Ajouter un Partenaire</button>';
        echo '</div>';
        echo '</form>';
    } else {
        echo "<p>Erreur : nombre de partenaires non défini.</p>";
    }
    ?>

</body>
</html>
