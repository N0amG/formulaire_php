function update_partner_section_name() {
    const partnerLabel = document.getElementsByClassName('partnerNum');
    for (let i = 0; i < partnerLabel.length; i++) {
        partnerLabel[i].textContent = "Partenaire " + (i+1) + ":";
    };
    
    const hiddenInput = document.getElementById('numPartnersInput');
    hiddenInput.value = partnerLabel.length;
}

function delete_section(element) {
    const parent = element.closest('.partner-section');
    if (document.getElementsByClassName('delete-partner-button').length > 1 ) {
        parent.remove();
        update_partner_section_name();
    } else {
        alert("Il doit au moins y avoir 1 partenaire.");
    }
}

function add_partner_section() {
    const partnerSections = document.querySelectorAll('.partner-section');
    const newPartnerSection = partnerSections[partnerSections.length - 1].cloneNode(true);

    // Réinitialiser les champs
    newPartnerSection.querySelector('input[name="partner_id[]"]').value = "";
    newPartnerSection.querySelector('input[name="partner[]"]').value = "";
    newPartnerSection.querySelector('textarea[name="contribution[]"]').value = "";

    // Ajouter la nouvelle section
    const partnersContainer = document.getElementById('partners-container');
    partnersContainer.appendChild(newPartnerSection);

    update_partner_section_name();
    attach_delete_events();
}

// Ajout de l'écouteur d'événements pour chaque bouton de suppression de partenaire
const deleteButtons = document.getElementsByClassName('delete-partner-button');
for (let i = 0; i < deleteButtons.length; i++) {
    deleteButtons[i].addEventListener('click', function() {
        delete_section(this);
    });
}

document.getElementById('add-partner-button').addEventListener('click', add_partner_section);

