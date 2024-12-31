# Planification de Repas

Bienvenue dans le projet de planification de repas. Ce projet vous permet de gérer vos recettes, de planifier vos repas et de générer des listes de courses facilement.

## Fonctionnalités

- **Gestion des Recettes** : Créez, modifiez et supprimez des recettes.
- **Planification des Repas** : Planifiez vos repas pour la semaine.
- **Liste de Courses** : Générez automatiquement une liste de courses basée sur vos recettes.
- **Interface Utilisateur** : Une interface conviviale utilisant Bootstrap pour un design réactif.

## Technologies Utilisées

- **Backend** : PHP avec Symfony
- **Frontend** : HTML, CSS, JavaScript
- **Base de Données** : MySQL
- **Bibliothèques** : Bootstrap, jQuery, Select2

## Installation

1. Clonez le dépôt :

   ```bash
   git clone https://github.com/votre-utilisateur/planification-repas.git
   ```

2. Accédez au répertoire du projet :

   ```bash
   cd planification-repas
   ```

3. Installez les dépendances :

   ```bash
   composer install
   ```

4. Configurez votre base de données dans le fichier `.env`.

5. Exécutez les migrations :

   ```bash
   php bin/console doctrine:migrations:migrate
   ```

6. Démarrez le serveur :
   ```bash
   symfony server:start
   ```

## Utilisation

- Accédez à l'application via `http://localhost:8000`.
- Créez un compte ou connectez-vous pour commencer à ajouter vos recettes et planifier vos repas.

## Contribuer

Les contributions sont les bienvenues ! Veuillez soumettre une demande de tirage (pull request) pour toute amélioration ou correction.

## License

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.
