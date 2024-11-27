<?php
function validateFormData($data) {
    // Validation des données (exemple basique)
    return !empty($data['activity_type']) && !empty($data['partnership_name']);
}
?>

