function update_partner_section_name() {
    const partnerSections = document.querySelectorAll('.partner-section');
    partnerSections.forEach((partnerSection, index) => {
        partnerSection.querySelector('.partnerNum').textContent = "Partenaire " + (index + 1) + ":";
    });

    const hiddenInput = document.getElementById('numPartnersInput');
    hiddenInput.value = partnerSections.length;
}

function delete_section(element) {
    const parent = element.closest('.partner-section');
    if (document.querySelectorAll('.partner-section').length > 1) {
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

document.getElementById('add-partner-button').addEventListener('click', add_partner_section);
attach_delete_events();
