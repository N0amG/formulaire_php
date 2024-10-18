<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données des partenaires
    $partnerNames = $_POST['partnerName'];
    $partnerContributions = $_POST['partnerContribution'];

    // Générer le contenu du contrat
    $contractContent = "<h1>Contrat de Partenariat Commercial</h1>";
    $contractContent .= "<p>Ce contrat est fait ce jour <strong>__________</strong>, <strong>______________________</strong> en <strong>_____________________</strong> copies originales, entre</p>";
    $contractContent .= "<ol>";

    foreach ($partnerNames as $name) {
        $contractContent .= "<li><strong>$name</strong></li>";
    }

    $contractContent .= "</ol><p>(les “Partenaires”).</p>";
    $contractContent .= "<div class='section-title'>3. CONTRIBUTION AU PARTENARIAT</div>";
    $contractContent .= "<div>3.1 La contribution de chaque partenaire au capital listée ci-dessous se compose des éléments suivants :</div>";
    $contractContent .= "<ol>";

    foreach ($partnerContributions as $contribution) {
        $contractContent .= "<li><strong>$contribution</strong></li>";
    }

    $contractContent .= "</ol>";
    $contractContent .= "<div>2.1 Le partenariat commence le <strong>______________________________</strong> et continue jusqu'à la fin de cet accord.</div>";
    $contractContent .= "<div>4.1 Les Partenaires se partageront les profits et les pertes du partenariat commercial de la manière suivante :</div>";
    $contractContent .= "<textarea>___________________________________________________________________________</textarea>";
    $contractContent .= "<div>5.1 Aucune personne ne peut être ajoutée en tant que partenaire sans le consentement écrit de tous les partenaires.</div>";
    $contractContent .= "<p>Solennellement affirmé à <strong>____________________</strong></p>";
    $contractContent .= "<p>Daté de ce jour <strong>________________________</strong></p>";
    $contractContent .= "<p>Signé, validé et livré en présence de:</p>";
    $contractContent .= "<ol>";
    $contractContent .= "<li><strong>___________________________________________</strong> (Nom du partenaire)</li>";
    $contractContent .= "<li><strong>___________________________________________</strong> (Nom du partenaire)</li>";
    $contractContent .= "<li><strong>___________________________________________</strong> (Nom du partenaire)</li>";
    $contractContent .= "</ol>";
    $contractContent .= "<p>Par moi:</p>";
    $contractContent .= "<p>___________________________________________ (Nom de l'avocat)</p>";

    // Affichage du contrat
    echo "<html lang='fr'><head><meta charset='UTF-8'><link rel='stylesheet' href='styles.css'></head><body>$contractContent</body></html>";
} else {
    // Redirection vers le formulaire si la méthode n'est pas POST
    header('Location: index.php');
    exit;
}
?>
