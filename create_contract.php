<?php
include('html_utils/header.php');
include('functions.php');

// Connexion à la base de données
$pdo = connectDB();
?>

<h1>Formulaire de Partenariat Commercial</h1>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['numPartners']) && !isset($_POST['activityType'])) {
        $numPartners = (int) $_POST['numPartners'];  // Nombre de partenaires

        // Afficher le formulaire pour les partenaires
        echo '<form id="form" action="" method="post">';
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
            echo "<input type='text' name='partner[]' required>";
            echo "<label>Contribution du Partenaire</label>";
            echo "<textarea name='contribution[]' rows='3' required style='resize: none;'></textarea>";
            echo '<br>';
            echo '<br>';
            echo '</div>';
        }
        echo '</div>'; // fin de partners-container

        echo '<input type="hidden" id="numPartnersInput" name="numPartners" value="' . $numPartners . '">';
        echo '<div id="bottom-page-container">';
        echo '<input type="submit" value="Soumettre">';
        echo '<button type="button" id="add-partner-button">Ajouter un Partenaire</button>';
        echo '</div>';
        echo '</form>';
    } elseif (isset($_POST['activityType'])) {
        // Récupération des données du formulaire
        $data = getPOSTData();
        $formData = $data['data'];
        $partners = $data['partners'];

        // Sauvegarde des données dans la base de données
        $formId = insertDataIntoForm($pdo, $formData, $partners);
        // Redirection vers display_contract.php avec l'ID du formulaire
        header("Location: display_contract.php?id=$formId");
        exit;
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

<script src="scripts/create_form.js" defer></script>