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
      <button id="theme-switcher">Mode Sombre</button>
    </div>
    <!-- Génération dynamique des champs après la sélection -->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['numPartners'])) {
        $numPartners = (int)$_POST['numPartners'];  // Nombre de partenaires sélectionné

        echo '<form action="generate_contract.php" method="post">';
        echo '<legend>Informations sur les Partenaires</legend>';
        echo '<br>';
        echo '<fieldset class="information-section">';

        // Génération des champs pour chaque partenaire
        for ($i = 1; $i <= $numPartners; $i++) {
            echo '<div id="partner'.$i.'" class="partner-section">';
            echo '
            <div class="delete-partner-container">
                <input type="button" id="delete-partner'.$i.'-button" value="Delete-'.$i.'" class="delete-section-button" "onclick="delete-section">
            </div>';
            echo "<label for='partner$i'>Nom du Partenaire $i:</label>";
            echo "<input type='text' id='partner$i' name='partner$i' required>";

            echo "<label for='contribution$i'>Contribution du Partenaire $i:</label>";
            echo "<textarea id='contribution$i' name='contribution$i' rows='3' required style='resize: none;'></textarea>";
            echo '</div>';
            echo '<br>';
        }

        echo '</fieldset>';
        echo '<br>';
        echo '<input type="hidden" name="numPartners" value="' . $numPartners . '">';
        echo '<input type="submit" value="Soumettre">';
        echo '</form>';
    } else {
        echo "<p>Erreur : nombre de partenaires non défini.</p>";
    }
    ?>

</body>
</html>
