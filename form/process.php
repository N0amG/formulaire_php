<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['numPartners'])) {
    $numPartners = (int)$_POST['numPartners'];

    echo "<h2>Données soumises :</h2>";

    // Affichage des données pour chaque partenaire
    for ($i = 1; $i <= $numPartners; $i++) {
        $partnerName = htmlspecialchars($_POST["partner$i"]);
        $contribution = htmlspecialchars($_POST["contribution$i"]);

        echo "Partenaire $i: $partnerName<br>";
        echo "Contribution: $contribution<br><br>";
    }
} else {
    echo "<p>Erreur : données du formulaire manquantes.</p>";
}
?>
