<?php
require_once('html_utils/header.php');
require_once('functions.php');

// Connexion à la base de données
$pdo = connectDB();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Partenariat Commercial</title>
    <link rel="stylesheet" href="style.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="scripts/create_form.js" defer></script>
</head>
<body>
    <h1>Formulaire de Partenariat Commercial</h1>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['activityType'])) {
            // Récupération des données du formulaire
            $data = getPOSTData();
            $formData = $data['data'];
            $partners = $data['partners'];

            // Sauvegarde des données dans la base de données
            $formId = insertDataIntoForm($pdo, $formData, $partners);
            if ($formId) {
                // Redirection vers display_contract.php avec l'ID du formulaire
                header("Location: display_contract.php?id=$formId");
                exit;
            } else {
                echo "Erreur lors de l'insertion des données.";
            }
        }
    } else {
        echo '<form action="" method="post">';
        echo '<fieldset class="form-section">';
        echo '<legend>Sélection du Nombre de Partenaires</legend>';
        echo '<label for="numPartners">Combien de partenaires ?</label>';
        echo '<input type="number" id="numPartners" class ="spinner-input" name="numPartners" min="1" max="100" required />';
        echo '<input type="submit" value="Valider" />';
        echo '</fieldset>';
        echo '</form>';
    }
    ?>

    <form method="POST" action="">

        <?php 
        if (isset($_POST['numPartners']) && !isset($_POST['activityType'])) {
            $numPartners = (int) $_POST['numPartners'];  // Nombre de partenaires

            // Afficher le formulaire pour les partenaires
            echo '<h2>Informations sur les Partenaires</h2>';
            echo '<div id="partners-container">';
            // Génération des champs pour chaque partenaire
            for ($i = 1; $i <= $numPartners; $i++) {
                echo '<div class="partner-section">';

                // Champ caché pour l'ID du partenaire (vide lors de la création)
                echo '<input type="hidden" name="partner_id[]" value="">';

                echo "<label class='partnerNum'>Partenaire $i:</label>";
                echo "<br>";
                echo '<div class="delete-partner-container">';
                echo '<button type="button" class="delete-partner-button">X</button>';
                echo '</div>';
                echo "<label>Nom du Partenaire</label>";
                echo "<input type='text' class='partner-name' name='partner[]' required>";
                echo "<label>Contribution du Partenaire</label>";
                echo "<textarea class ='contributions' name='contribution[]' rows='3' required style='resize: none;'></textarea>";
                echo '<br>';
                echo '<br>';
                echo '</div>';
            }
            echo '</div>'; // fin de partners-container

            echo '<input type="hidden" id="numPartnersInput" name="numPartners" value="' . $numPartners . '">';
            echo '<div id="bottom-page-container">';
            echo '<button type="button" id="add-partner-button">Ajouter un Partenaire</button>';
            echo '</div>';

        }
        ?>
        
        <h2>1. Nom du Partenariat et Activité</h2>
        <p><strong>Nom du Partenariat</strong>: </p>
        <textarea id="partnershipName" name="partnershipName" rows="5" required style="resize: none;"></textarea>
        <p><strong>Nature des activités</strong>: </p>
        <textarea id="activityType" name="activityType" rows="5" required style="resize: none;"></textarea>
        <p><strong>Adresse officielle</strong>: </p>
        <textarea id="officialAdress" name="officialAdress" rows="5" required style="resize: none;"></textarea>

        <h2>2. Termes</h2>
        <p>Le partenariat commence le <input type="text" id="date_debut" name="date_debut"> et finira le <input type="text" id="date_fin" name="date_fin"></p>

        
        <br>
        <h2>3. Répartition des bénéfices et des pertes</h2>
        <textarea id="distributionOfProfitsAndLosses" name="distributionOfProfitsAndLosses" rows="5" required
            style="resize: none;"></textarea>

        <h2>4. Modalités bancaires</h2>
        <p>Les chèques doivent être signés par <input type="number" id="partnerCount" class ="spinner-input" name="partnerCount" min="1" max="<?php echo $numPartners ?>" value="<?php echo $numPartners ?>"> des partenaires.</p>
        <h2>5. Juridiction</h2>
        <p>Le présent contrat de partenariat commercial est régi par les lois de l'État de
            <select id="countryOfContract" name="country" required>
                <option value="">Sélectionnez un pays</option>
                <option value="FR">France</option>
                <option value="US">États-Unis</option>
                <option value="CA">Canada</option>
                <option value="GB">Royaume-Uni</option>
                <option value="DE">Allemagne</option>
                <option value="JP">Japon</option>
                <option value="CN">Chine</option>
                <option value="IN">Inde</option>
                <option value="BR">Brésil</option>
                <option value="AU">Australie</option>
            </select>.
        </p>

        <button type="submit" id="submitFinalForm">Valider le Contrat</button>
    </form>

    <?php require_once('html_utils/footer.php'); ?>
</body>
</html>