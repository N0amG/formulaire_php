<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['numPartners'])) {
    $numPartners = (int)$_POST['numPartners'];  // Nombre de partenaires

    echo "<!DOCTYPE html>";
    echo "<html lang='fr'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Contrat de Partenariat</title>";
    echo "<link rel='stylesheet' href='style.css'/>";
    echo "</head>";
    echo "<body>";
    
    echo "<h1>Contrat de Partenariat Commercial</h1>";
    echo "<p>Ce contrat est fait ce jour " . date("d/m/Y") . ", en " . $numPartners . " copies originales, entre :</p>";

    // Boucle pour récupérer et afficher les noms et contributions des partenaires
    for ($i = 1; $i <= $numPartners; $i++) {
        $partnerName = isset($_POST["partner$i"]) ? htmlspecialchars($_POST["partner$i"]) : "Nom non fourni";
        $contribution = isset($_POST["contribution$i"]) ? htmlspecialchars($_POST["contribution$i"]) : "Contribution non fournie";

        echo "<p>Partenaire $i: $partnerName</p>";
        echo "<p>Contribution: $contribution</p>";
    }

    // Le reste des informations du contrat peut être statique ou généré ici
    echo "<h2>1. Nom du Partenariat et Activité</h2>";
    echo "<p><strong>Nature des activités</strong>: (à définir)</p>";
    echo "<p><strong>Nom du Partenariat</strong>: (à définir)</p>";
    echo "<p><strong>Adresse officielle</strong>: (à définir)</p>";

    echo "<h2>2. Termes</h2>";
    echo "<p>Le partenariat commence le (à définir) et continue jusqu'à la fin de cet accord.</p>";

    echo "<h2>3. Répartition des bénéfices et des pertes</h2>";
    echo "<p>(à définir)</p>";

    echo "<h2>4. Modalités bancaires</h2>";
    echo "<p>Les chèques doivent être signés par (à définir) des partenaires.</p>";

    echo "<h2>5. Juridiction</h2>";
    echo "<p>Le présent contrat de partenariat commercial est régi par les lois de l'État de (à définir).</p>";

    echo "</body>";
    echo "</html>";
} else {
    echo "<p>Erreur : données du formulaire manquantes.</p>";
}
?>
