# Formulaire de Partenariat Commercial

Ce projet est une application web permettant de créer, modifier, afficher et supprimer des contrats de partenariat commercial. Il inclut des fonctionnalités pour gérer les partenaires, les contributions et les termes du contrat.

# Mise en Place

Créer une bdd nommée "formulaire_db", puis cliquer sur cette bdd, cliquer sur importer, et importer le fichier formulaire_db.sql

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

### 7. Création de Compte
- **Page**: `register.php`
- Permet de créer un nouveau compte utilisateur en renseignant les informations nécessaires telles que le nom, le prénom, l'email et le mot de passe.

### 8. Connexion
- **Page**: `login.php`
- Permet de se connecter à un compte utilisateur existant en renseignant l'email et le mot de passe.

### 9. Thème Sombre/Clair
- **Script**: `script.js`
- Permet de basculer entre un thème sombre et un thème clair pour l'interface utilisateur.

### 10. Autocomplétion
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
- `register.php`: Page de création de compte utilisateur.
- `login.php`: Page de connexion utilisateur.
- `scripts/`: Dossier contenant les fichiers JavaScript.
- `html_utils/`: Dossier contenant les fichiers d'en-tête et de pied de page.
- `functions.php`: Fichier contenant les fonctions utilitaires pour la gestion des contrats et des partenaires.


