# 🎮 Master Nombre - Refonte fonction -> objet
Application web du jeu **Master Nombre** (mécanique inspirée du Mastermind/Motus), développée en **PHP** avec une séparation stricte des responsabilités (Logique métier / Présentation).

Projet conçu pour la validation des compétences du dossier professionnel (**RNCP**).

---

## 🏗️ Architecture du Projet

Le projet applique les principes fondamentaux de la programmation moderne :
* **Composants Métiers Purs (`src/Metier/`)** : Logique de calcul des indices (`Master.php`) et arbitrage de la partie (`Game.php`). Ces classes sont totalement indépendantes de l'affichage HTML et du protocole HTTP.
* **Moteur de Présentation / Vue (`templates/`)** : Utilisation du moteur de template **Blade** de Laravel de manière autonome pour isoler l'affichage.
* **Contrôleur (`public/index.php`)** : Point d'entrée unique de l'application gérant les sessions PHP et aiguillant les requêtes.
* **Gestion des Dépendances** : Configuration et autoloading standardisés via **Composer (PSR-4)**.
* **Conteneurisation** : Environnement isolé et reproductible sous **Docker**.

---

## 🚀 Lancement Rapide (Mode Démonstration)

Suivez ces étapes pour exécuter et tester l'application localement.

### Prérequis
* [Docker Desktop](https://docker.com) installé et démarré sur votre machine (Windows / macOS / Linux).

### 1. Démarrer les conteneurs Docker
À la racine du projet, lancez la commande suivante pour monter l'environnement web :
```bash
docker compose up -d --build
```

### 2. Installer les dépendances (Composer)
Une fois le conteneur démarré, installez le moteur de template Blade en exécutant Composer directement à l'intérieur de l'environnement isolé :
```bash
docker compose exec web composer install
```

### 3. Accéder au jeu
Ouvrez votre navigateur internet et rendez-vous à l'adresse suivante :
👉 **[http://localhost:8080](http://localhost:8080)** 
*(ou le port configuré dans votre fichier `compose.yaml`)*

---
