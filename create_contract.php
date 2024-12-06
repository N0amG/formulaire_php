<?php
include('html_utils/header.php');
include('functions.php');
?>

<h1>Formulaire de Partenariat Commercial</h1>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['numPartners'])) {
    $numPartners = (int) $_POST['numPartners'];  // Nombre de partenaires

    if (isset($_POST['activityType'])) {
        // Récupération des données du formulaire
        $data = getPOSTData();
        $formData = $data['data'];
        $partners = $data['partners'];

        // Sauvegarde des données dans la base de données
        $pdo = connectDB();
        $formId = insertDataIntoForm($pdo, $formData, $partners);
        // Redirection vers display_contract.php avec l'ID du formulaire
        header("Location: display_contract.php?id=$formId");
        exit;
    } elseif (isset($_POST['partner1'])) {
        // Si les partenaires sont définis, afficher le formulaire de contrat
        echo "<form method='POST' action=''>";
        echo "<input type='hidden' name='numPartners' value='$numPartners'>";

        // Champs cachés pour transmettre les noms et contributions des partenaires
        for ($i = 1; $i <= $numPartners; $i++) {
            $partnerName = htmlspecialchars($_POST["partner$i"]);
            $contribution = htmlspecialchars($_POST["contribution$i"]);
            echo "<input type='hidden' name='partner$i' value='$partnerName'>";
            echo "<input type='hidden' name='contribution$i' value='$contribution'>";
        }

        // Affichage des noms et contributions des partenaires
        echo "<h2>Partenaires et Contributions</h2>";
        for ($i = 1; $i <= $numPartners; $i++) {
            $partnerName = htmlspecialchars($_POST["partner$i"]);
            $contribution = htmlspecialchars($_POST["contribution$i"]);
            echo "<p><strong>Partenaire $i:</strong> $partnerName</p>";
            echo "<p><strong>Contribution:</strong> $contribution</p>";
        }

        // Le reste des informations du contrat
        echo "<h2>1. Nom du Partenariat et Activité</h2>";
        echo "<p><strong>Nature des activités</strong>: </p>
        <textarea id='activityType' name='activityType' rows='5' required style='resize: none;'></textarea>";
        echo "<p><strong>Nom du Partenariat</strong>: </p>
        <textarea id='partnershipName' name='partnershipName' rows='5' required style='resize: none;'></textarea>";
        echo "<p><strong>Adresse officielle</strong>: </p>
        <textarea id='officialAdress' name='officialAdress' rows='5' required style='resize: none;'></textarea>";
        echo "<h2>2. Termes</h2>";
        echo "<p>Le partenariat commence le <input type='date' id='date_debut' name='date'> et finira le <input type='date' id='date_fin' name='date_fin'>.</p>";
        echo "<h2>3. Répartition des bénéfices et des pertes</h2>";
        echo "<textarea id='distributionOfProfitsAndLosses' name='distributionOfProfitsAndLosses' rows='5' required style='resize: none;'></textarea>";

        echo "<h2>4. Modalités bancaires</h2>";
        echo "<p>Les chèques doivent être signés par <input type='number' id='partnerCount' name='partnerCount' min='1' max='$numPartners' value='1'> des partenaires.</p>";

        echo "<h2>5. Juridiction</h2>";
        echo "<p>Le présent contrat de partenariat commercial est régi par les lois de l'État de " .
            "<select id='countryOfContract' name='country' required>" .
            "<option value=''>Sélectionnez un pays</option>" .
            "<option value='FR'>France</option>" .
            "<option value='US'>États-Unis</option>" .
            "<option value='CA'>Canada</option>" .
            "<option value='GB'>Royaume-Uni</option>" .
            "<option value='DE'>Allemagne</option>" .
            "<option value='JP'>Japon</option>" .
            "<option value='CN'>Chine</option>" .
            "<option value='IN'>Inde</option>" .
            "<option value='BR'>Brésil</option>" .
            "<option value='AU'>Australie</option>" .
            "</select>" .
            ".</p>";

        // Bouton de soumission
        echo "<button type='submit' id='submitFinalForm'>Valider le Contrat</button>";
        echo "</form>";
    } else {
        // Si les partenaires ne sont pas définis, afficher le formulaire pour les partenaires
        echo '<form id="form" action="" method="post">';
        echo '<legend>Informations sur les Partenaires</legend>';
        echo '<br>';
        echo '<fieldset class="information-section">';
        echo '<br>';
        // Génération des champs pour chaque partenaire
        for ($i = 1; $i <= $numPartners; $i++) {
            echo '<div class="partner-section">';

            echo "<label class='partnerNum' for='partner$i'>Partenaire $i:</label>";
            echo "<br>";
            echo '<div class="delete-partner-container" id="delete-partner' . $i . '-div">';
            echo '<button type="button" class="delete-partner-button">X</button>';
            echo '</div>';
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
    }
} else {
    echo '<form action="" method="post">';
    echo '<fieldset class="form-section">';
    echo '<legend>Sélection du Nombre de Partenaires</legend>';
    echo '<label for="numPartners">Combien de partenaires ?</label>';
    echo '<input type="number" id="numPartners" name="numPartners" min="1" max="100" required />';
    echo '<input type="submit" value="Valider" />';
    echo '</fieldset>';
    echo '</form>';
}

include('html_utils/footer.php');
?>

<script src="script.js" defer></script>