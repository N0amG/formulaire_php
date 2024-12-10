# Formulaire de Partenariat Commercial

Ce projet est une application web permettant de créer, modifier, afficher et supprimer des contrats de partenariat commercial. Il inclut des fonctionnalités pour gérer les partenaires, les contributions et les termes du contrat.

## Fonctionnalités

### 1. Création de Contrat
- **Page**: `new_contract.php`
- Permet de créer un nouveau contrat de partenariat en renseignant les informations nécessaires telles que le nom du partenariat, la nature des activités, l'adresse officielle, les termes, la répartition des bénéfices et des pertes, les modalités bancaires et la juridiction.

### 2. Modification de Contrat
- **Page**: `edit_contract.php`
- Permet de modifier un contrat existant en récupérant les informations depuis la base de données et en les affichant dans un formulaire éditable.

### 3. Affichage de Contrat
- **Page**: `display_contract.php`
- Affiche les détails d'un contrat de partenariat existant, y compris les informations sur les partenaires et les termes du contrat.

### 4. Suppression de Contrat
- **Page**: `delete_contract.php`
- Permet de supprimer un contrat existant après confirmation de l'utilisateur.

### 5. Génération de PDF
- **Page**: `gen_pdf.php`
- Génère un fichier PDF du contrat de partenariat pour impression ou archivage.

### 6. Gestion des Partenaires
- **Scripts**: `create_form.js`
- Permet d'ajouter, modifier et supprimer des partenaires dans le formulaire de création ou de modification de contrat.

### 7. Thème Sombre/Clair
- **Script**: `script.js`
- Permet de basculer entre un thème sombre et un thème clair pour l'interface utilisateur.

### 8. Autocomplétion
- **Pages**: `fetch_partner_names.php`, `fetch_contributions.php`
- Fournit des fonctionnalités d'autocomplétion pour les noms des partenaires et leurs contributions en utilisant des requêtes AJAX.

## Structure du Projet

- `index.php`: Page d'accueil listant tous les contrats existants.
- `new_contract.php`: Page de création de nouveau contrat.
- `edit_contract.php`: Page de modification de contrat existant.
- `display_contract.php`: Page d'affichage des détails d'un contrat.
- `gen_pdf.php`: Page de génération de PDF pour un contrat.
- `fetch_partner_names.php`: Script pour l'autocomplétion des noms des partenaires.
- `fetch_contributions.php`: Script pour l'autocomplétion des contributions des partenaires.
- `scripts/`: Dossier contenant les fichiers JavaScript.
- `html_utils/`: Dossier contenant les fichiers d'en-tête et de pied de page.
- `functions.php`: Fichier contenant les fonctions utilitaires pour la gestion des contrats et des partenaires.

## Installation

1. Cloner le dépôt.
2. Configurer la base de données dans le fichier `functions.php`.
3. Importer le schéma de la base de données.
4. Lancer le serveur web et accéder à l'application via le navigateur.

## Utilisation

1. Accéder à la page d'accueil pour voir la liste des contrats.
2. Utiliser les boutons pour créer, modifier, afficher ou supprimer des contrats.
3. Utiliser le bouton de changement de thème pour basculer entre le thème sombre et clair.
4. Utiliser les fonctionnalités d'autocomplétion pour faciliter la saisie des noms des partenaires et des contributions.

## Auteurs

- Développeur: Noam Guez

## Licence

Ce projet est sous licence MIT.
