// Sauvegarder la préférence de thème dans le stockage local
function saveThemePreference(theme) {
    localStorage.setItem('theme', theme);
}

function applyDarkTheme() {
    document.body.classList.add('dark-theme');
    document.body.classList.remove('light-theme');

    document.getElementById('theme-switcher').textContent = 'Mode clair';

    saveThemePreference('dark'); // Sauvegarder la préférence
}

function applyLightTheme() {
    document.body.classList.add('light-theme');
    document.body.classList.remove('dark-theme');

    document.getElementById('theme-switcher').textContent = 'Mode sombre';

    saveThemePreference('light'); // Sauvegarder la préférence
}

function switchTheme() {
    const body = document.body;
    if (body.classList.contains('light-theme')) {
        applyDarkTheme();
    } else {
        applyLightTheme();
    }
}

// Vérifier et appliquer le thème sauvegardé ou celui du navigateur
const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark') {
    applyDarkTheme();
} else if (savedTheme === 'light') {
    applyLightTheme();
} else {
    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        applyDarkTheme();
    } else {
        applyLightTheme();
    }
}

// Mettre à jour le thème lors d'un changement dans les préférences du navigateur
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
    if (!localStorage.getItem('theme')) { // Ne pas écraser la préférence utilisateur
        if (event.matches) {
            applyDarkTheme();
        } else {
            applyLightTheme();
        }
    }
});

// Ajouter un écouteur d'événements pour le bouton de changement de thème
document.getElementById('theme-switcher').addEventListener('click', switchTheme);

console.log('Thème enregistré :', savedTheme);
console.log('Thème du navigateur :', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
