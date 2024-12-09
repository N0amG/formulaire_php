function update_partner_section_name() {
    const partnerSections = document.querySelectorAll('.partner-section');
    partnerSections.forEach((partnerSection, index) => {
        partnerSection.querySelector('.partnerNum').textContent = "Partenaire " + (index + 1) + ":";
    });
    // Mettre à jour le champ de texte après la mise à jour des numéros de partenaire
    partnerCount = partnerSections.length;
    document.getElementById('partnerCount').max = partnerCount;
    document.getElementById('partnerCount').value = partnerCount;

    // Mettre à jour la valeur du champ caché numPartnersInput
    const hiddenInput = document.getElementById('numPartnersInput');
    hiddenInput.value = partnerSections.length;
}

function add_partner_section() {
    const partnerSections = document.querySelectorAll('.partner-section');
    const newPartnerSection = partnerSections[partnerSections.length - 1].cloneNode(true);

    // Réinitialiser les champs du nouveau partenaire
    newPartnerSection.querySelector('input[name="partner_id[]"]').value = "";
    newPartnerSection.querySelector('input[name="partner[]"]').value = "";
    newPartnerSection.querySelector('textarea[name="contribution[]"]').value = "";

    // Ajouter la nouvelle section partenaire
    const partnersContainer = document.getElementById('partners-container');
    partnersContainer.appendChild(newPartnerSection);

    // Mettre à jour les numéros de partenaire et le champ caché
    update_partner_section_name();
    attach_delete_events();
}
function delete_section(element) {
    const parent = element.closest('.partner-section');
    const partnersContainer = document.getElementById('partners-container');

    if (partnersContainer.querySelectorAll('.partner-section').length > 1) {
        parent.remove();
        // Mettre à jour les numéros de partenaire et le champ caché
        update_partner_section_name();
    } else {
        alert("Il doit au moins y avoir 1 partenaire.");
    }
}

function attach_delete_events() {
    const deleteButtons = document.querySelectorAll('.delete-partner-button');
    deleteButtons.forEach(button => {
        button.removeEventListener('click', handleDeletePartner);
        button.addEventListener('click', handleDeletePartner);
    });
}

function handleDeletePartner(event) {
    const button = event.currentTarget;
    delete_section(button);
}

// Ajouter les événements au chargement de la page
document.getElementById('add-partner-button').addEventListener('click', add_partner_section);
attach_delete_events();