<?php
require_once('functions.php');

// Gestion des requêtes AJAX pour l'autocomplétion des noms des partenaires
if (isset($_GET['term'])) {
    $term = $_GET['term'];
    $partnerNames = fetchPartnerNames($term);
    echo $partnerNames;
    exit;
}
?>