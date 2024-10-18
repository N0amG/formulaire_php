
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

// Ajout de l'écouteur d'événements pour le bouton de changement de thème
document.getElementById('theme-switcher').addEventListener('click', switchTheme);

// Application du thème sombre par défaut
applyDarkTheme();


