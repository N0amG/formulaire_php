function applyDarkTheme() {
    document.body.classList.add('dark-theme');
    document.body.classList.remove('light-theme');

    // Fonction récursive pour appliquer le thème à tous les éléments et sous-éléments
    function applyThemeRecursively(element) {
        element.classList.add('dark-theme');
        element.classList.remove('light-theme');

        // Parcourir les enfants de l'élément actuel
        const children = element.children;
        for (let i = 0; i < children.length; i++) {
            applyThemeRecursively(children[i]);
        }
    }

    // Appliquer le thème à l'élément <body> et à tous ses enfants
    applyThemeRecursively(document.body);

    // Changer le texte du bouton
    document.getElementById('theme-switcher').textContent = 'Mode clair';
}

function applyLightTheme() {
    document.body.classList.add('light-theme');
    document.body.classList.remove('dark-theme');

    // Fonction récursive pour appliquer le thème à tous les éléments et sous-éléments
    function applyThemeRecursively(element) {
        element.classList.add('light-theme');
        element.classList.remove('dark-theme');

        // Parcourir les enfants de l'élément actuel
        const children = element.children;
        for (let i = 0; i < children.length; i++) {
            applyThemeRecursively(children[i]);
        }
    }

    // Appliquer le thème à l'élément <body> et à tous ses enfants
    applyThemeRecursively(document.body);

    // Changer le texte du bouton
    document.getElementById('theme-switcher').textContent = 'Mode sombre';
}

function switchTheme() {
    const body = document.body;
    if (body.classList.contains('light-theme')) {
        applyDarkTheme();
    } else {
        applyLightTheme();
    }
}

if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
    // Application du thème sombre si le navigateur l'est.
    applyDarkTheme();
} else {
    applyLightTheme();
}

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
    const newColorScheme = event.matches ? "dark" : "light";
    if (newColorScheme === "dark") {
        applyDarkTheme();
    } else {
        applyLightTheme();
    }
});

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
    const partnerSection = document.getElementsByClassName('partner-section')[0];
    const newPartnerSection = partnerSection.cloneNode(true);
    const partnerNum = document.getElementsByClassName('partner-section').length + 1;

    // Mettre à jour le texte du label
    newPartnerSection.querySelector('.partnerNum').textContent = "Partenaire " + partnerNum + ":";

    // Réinitialiser les champs de texte
    newPartnerSection.querySelector('input[type="text"]').value = "";
    newPartnerSection.querySelector('textarea').value = "";
    // Mettre à jour l'ID et le nom des champs
    newPartnerSection.id = 'partner' + partnerNum;
    newPartnerSection.querySelector('input[type="text"]').id = 'partner' + partnerNum;
    newPartnerSection.querySelector('input[type="text"]').name = 'partner' + partnerNum;
    newPartnerSection.querySelector('textarea').id = 'contribution' + partnerNum;
    newPartnerSection.querySelector('textarea').name = 'contribution' + partnerNum;

    // Ajouter l'écouteur d'événements pour le bouton de suppression
    newPartnerSection.querySelector('.delete-partner-button').addEventListener('click', function() {
        delete_section(this);
    });

    // Ajouter la nouvelle section au DOM
    partnerSection.parentNode.appendChild(newPartnerSection);

    // Mettre à jour le nombre de partenaires
    update_partner_section_name();
}

// Ajout de l'écouteur d'événements pour le bouton de changement de thème
document.getElementById('theme-switcher').addEventListener('click', switchTheme);

// Ajout de l'écouteur d'événements pour chaque bouton de suppression de partenaire
const deleteButtons = document.getElementsByClassName('delete-partner-button');
for (let i = 0; i < deleteButtons.length; i++) {
    deleteButtons[i].addEventListener('click', function() {
        delete_section(this);
    });
}

document.getElementById('add-partner-button').addEventListener('click', add_partner_section);
