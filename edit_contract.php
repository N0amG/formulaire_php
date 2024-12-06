<?php
include('html_utils/header.php');
include('functions.php');

// Connexion à la base de données
$pdo = connectDB();

// Vérification de l'ID du formulaire dans l'URL
if (isset($_GET['id'])) {
    $formId = (int)$_GET['id'];

    // Récupération des informations du formulaire et des partenaires associés
    try {
        $formData = getFormDataById($pdo, $formId);
    } catch (Exception $e) {
        echo "<p>Erreur : " . $e->getMessage() . "</p>";
        exit;
    }
} else {
    echo "<p>Erreur : ID du formulaire manquant.</p>";
    exit;
}

// Vérification de la méthode POST pour la mise à jour du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $formId = (int)$_POST['id'];

    // Récupération des données du formulaire
    $data = getPOSTData();
    $formData = $data['data'];
    $partners = $data['partners'];

    try {
        // Mise à jour du contrat en utilisant la fonction
        updateContract($pdo, $formId, $formData, $partners);

        // Redirection vers display_contract.php avec l'ID du formulaire
        header("Location: display_contract.php?id=$formId");
        exit;
    } catch (Exception $e) {
        echo "<p>Erreur : " . $e->getMessage() . "</p>";
    }
}
?>

<h1>Modifier le Contrat de Partenariat Commercial</h1>

<form method="POST" action="">
    <input type="hidden" name="id" value="<?php echo $formId; ?>">

    <h2>Partenaires et Contributions</h2>
    <?php if (isset($formData['partners']) && is_array($formData['partners'])): ?>
        <?php foreach ($formData['partners'] as $index => $partner): ?>
            <p><strong>Partenaire <?php echo $index + 1; ?>:</strong> <?php echo htmlspecialchars($partner['nom']); ?></p>
            <p><strong>Contribution:</strong> <?php echo htmlspecialchars($partner['contribution']); ?></p>
            <input type="hidden" name="partner<?php echo $index + 1; ?>" value="<?php echo htmlspecialchars($partner['nom']); ?>">
            <input type="hidden" name="contribution<?php echo $index + 1; ?>" value="<?php echo htmlspecialchars($partner['contribution']); ?>">
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun partenaire trouvé.</p>
    <?php endif; ?>

    <input type="hidden" name="numPartners" value="<?php echo isset($formData['partners']) ? count($formData['partners']) : 0; ?>">

    <h2>1. Nom du Partenariat et Activité</h2>
    <p><strong>Nature des activités</strong>: </p>
    <textarea id="activityType" name="activityType" rows="5" required style="resize: none;"><?php echo htmlspecialchars($formData['data']['activity_type']); ?></textarea>
    <p><strong>Nom du Partenariat</strong>: </p>
    <textarea id="partnershipName" name="partnershipName" rows="5" required style="resize: none;"><?php echo htmlspecialchars($formData['data']['partnership_name']); ?></textarea>
    <p><strong>Adresse officielle</strong>: </p>
    <textarea id="officialAdress" name="officialAdress" rows="5" required style="resize: none;"><?php echo htmlspecialchars($formData['data']['official_address']); ?></textarea>
    <h2>2. Termes</h2>
    <p>Le partenariat commence le <input type="date" id="date_debut" name="date" value="<?php echo htmlspecialchars($formData['data']['start_date']); ?>"> et finira le <input type="date" id="date_fin" name="date_fin" value="<?php echo htmlspecialchars($formData['data']['end_date']); ?>">.</p>
    <br>
    <h2>3. Répartition des bénéfices et des pertes</h2>
    <textarea id="distributionOfProfitsAndLosses" name="distributionOfProfitsAndLosses" rows="5" required style="resize: none;"><?php echo htmlspecialchars($formData['data']['profit_loss_distribution']); ?></textarea>

    <h2>4. Modalités bancaires</h2>
    <p>Les chèques doivent être signés par <input type="number" id="partnerCount" name="partnerCount" min="1" max="<?php echo isset($formData['partners']) ? count($formData['partners']) : 1; ?>" value="<?php echo htmlspecialchars($formData['data']['signing_partner_count']); ?>"> des partenaires.</p>

    <h2>5. Juridiction</h2>
    <p>Le présent contrat de partenariat commercial est régi par les lois de l'État de 
    <select id="countryOfContract" name="country" required>
        <option value="">Sélectionnez un pays</option>
        <option value="FR" <?php echo ($formData['data']['country_code'] == 'FR') ? 'selected' : ''; ?>>France</option>
        <option value="US" <?php echo ($formData['data']['country_code'] == 'US') ? 'selected' : ''; ?>>États-Unis</option>
        <option value="CA" <?php echo ($formData['data']['country_code'] == 'CA') ? 'selected' : ''; ?>>Canada</option>
        <option value="GB" <?php echo ($formData['data']['country_code'] == 'GB') ? 'selected' : ''; ?>>Royaume-Uni</option>
        <option value="DE" <?php echo ($formData['data']['country_code'] == 'DE') ? 'selected' : ''; ?>>Allemagne</option>
        <option value="JP" <?php echo ($formData['data']['country_code'] == 'JP') ? 'selected' : ''; ?>>Japon</option>
        <option value="CN" <?php echo ($formData['data']['country_code'] == 'CN') ? 'selected' : ''; ?>>Chine</option>
        <option value="IN" <?php echo ($formData['data']['country_code'] == 'IN') ? 'selected' : ''; ?>>Inde</option>
        <option value="BR" <?php echo ($formData['data']['country_code'] == 'BR') ? 'selected' : ''; ?>>Brésil</option>
        <option value="AU" <?php echo ($formData['data']['country_code'] == 'AU') ? 'selected' : ''; ?>>Australie</option>
    </select>.
    </p>

    <button type="submit" id="submitFinalForm">Mettre à jour le Contrat</button>
</form>

<?php include('html_utils/footer.php'); ?>