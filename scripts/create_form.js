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
    applyAutocomplete();
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

function confirmDelete() {
    return confirm("Êtes-vous sûr de vouloir supprimer ce formulaire ?");
}

$(function () {
    $("#date_debut, #date_fin").datepicker({
        showAnim: "slideDown",
        dateFormat: "yy-mm-dd" // Format YYYY-MM-DD
    });
});

function applyAutocomplete() {
    // Fonction pour récupérer les noms des partenaires depuis la base de données
    function fetchPartnerNames(request, response) {
        console.log("Fetching partner names for term:", request.term); // Message de débogage
        $.ajax({
            url: 'fetch_partner_names.php', // Assurez-vous que ce chemin est correct
            type: 'GET',
            dataType: 'json',
            data: {
                term: request.term
            },
            success: function (data) {
                console.log("Partner names received:", data); // Message de débogage
                response(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.error("Error fetching partner names:", textStatus, errorThrown); // Message de débogage
            }
        });
    }
    function fetchContributions(request, response) {
        console.log("Fetching contributions for term:", request.term); // Message de débogage
        $.ajax({
            url: 'fetch_contributions.php', // Assurez-vous que ce chemin est correct
            type: 'GET',
            dataType: 'json',
            data: {
                term: request.term
            },
            success: function (data) {
                console.log("contributions received:", data); // Message de débogage
                response(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.error("Error fetching partner names:", textStatus, errorThrown); // Message de débogage
            }
        });
    }

    // Appliquer l'autocomplétion aux champs de saisie des noms des partenaires
    $(".partner-name").autocomplete({
        source: fetchPartnerNames
    });
    $(".contributions").autocomplete({
        source: fetchContributions
    });

}

$(function() {
    var spinners = $(".spinner-input").spinner();
    var selectmenu = $("select").selectmenu();
    $("button").button();

    // Ajuster la largeur des éléments du spinner pour correspondre à celle des inputs d'origine
    spinners.each(function() {
        var originalWidth = $(this).width() + 30;
        $(this).closest(".ui-spinner").css('width', originalWidth);
    });

    // Vérifier la classe du body et appliquer les couleurs appropriées
    if ($("body").hasClass("dark-theme")) {
        spinners.each(function() {
            // Appliquer les couleurs de la classe dark-theme aux éléments du spinner
            $(this).closest(".ui-spinner").css({
                'background-color': '#333', // Couleur de fond de dark-theme
                'color': '#fff', // Couleur du texte de dark-theme
                'border-color': '#555' // Couleur de la bordure de dark-theme
            });

            // Appliquer les couleurs de la classe dark-theme aux inputs du spinner
            $(this).css({
                'background-color': '#333', // Couleur de fond de dark-theme
                'color': '#fff', // Couleur du texte de dark-theme
                'border-color': '#555' // Couleur de la bordure de dark-theme
            });
        });

        // Appliquer les couleurs de la classe dark-theme aux éléments selectmenu
        selectmenu.selectmenu("widget").css({
            'background-color': '#333', // Couleur de fond de dark-theme
            'color': '#fff', // Couleur du texte de dark-theme
            'border-color': '#555' // Couleur de la bordure de dark-theme
        });

        // Appliquer les couleurs de la classe dark-theme aux boutons
        $("button").button("widget").css({
            'background-color': '#333', // Couleur de fond de dark-theme
            'color': '#fff', // Couleur du texte de dark-theme
            'border-color': '#555' // Couleur de la bordure de dark-theme
        });
    }
});

$( function() {
    $( "#countryOfContract" )
      .selectmenu()
      .selectmenu( "menuWidget" )
        .addClass( "overflow" );
  } );

document.addEventListener('DOMContentLoaded', function () {
    // Ajouter les événements au chargement de la page
    document.getElementById('add-partner-button').addEventListener('click', add_partner_section);
    attach_delete_events();
    applyAutocomplete();
});