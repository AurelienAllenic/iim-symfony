# Projet Symfony - Plateforme E-commerce avec Système de Points

## 📋 Description du Projet

Ce projet est une plateforme e-commerce simplifiée utilisant un système de points pour les achats, avec gestion des utilisateurs et notifications administrateur.

**⚠️ Note importante** : Il s'agit de mon premier projet Symfony. L'accent a été mis sur la fonctionnalité plutôt que sur le design, conformément aux exigences pédagogiques.

## 🏗️ Architecture

## 🔧 Fonctionnalités Implémentées

### Pour les Utilisateurs

-   ✅ **Connexion uniquement** (pas d'inscription disponible)
-   ✅ Consultation de la liste des produits
-   ✅ Visualisation du détail d'un produit
-   ✅ Achat de produits avec système de points
-   ✅ Modification du nom et prénom
-   ✅ Blocage des achats pour les comptes désactivés
-   ✅ Notification visuelle en cas de désactivation

### Pour les Administrateurs

-   ✅ Désactivation des comptes utilisateurs
-   ✅ CRUD complet des produits
-   ✅ Réception de notifications pour :
-   ✅ Modifications de produits
-   ✅ Achats effectués par les utilisateurs
-   ✅ Attribution de 1000 points à tous les utilisateurs actifs (via Messenger)
-   ✅ Routes API dédiées pour récupérer ses propres produits

## 🛠️ Technologies Utilisées

-   **Framework** : Symfony 7.3
-   **Base de données** : MySQL (via Docker)
-   **Containerisation** : Docker & Docker Compose
-   **API** : API Platform
-   **Messaging** : Symfony Messenger

# 🚀 Installation

## Prérequis

-   PHP 8.1+
-   Composer 2.5.8
-   Symfony CLI 5.9.1
-   Docker 27.3.1 & Docker Compose 2.29.2

## Étapes d'installation

### 1. Cloner le repository

```bash
git clone https://github.com/AurelienAllenic/iim-symfony.git
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Lancement des services Docker

```bash
docker-compose up -d
```

### 4. Configuration de l'environnement

Les .env sont déja présents dans le repo

### 5. Créer la base de données

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 6. Charger les fixtures

```bash
php bin/console doctrine:fixtures:load
```

### 7. Démarrer le serveur

```bash
symfony serve -d
```

# 👤 Comptes de Démonstration

**⚠️ Note** : L'inscription n'est pas disponible. Utilisez les comptes prédéfinis ci-dessous, cf voir les fixtures.

## Administrateurs

### Professeur (Admin principal)

-   **Email** : `prof@gmail.com`
-   **Mot de passe** : `prof`
-   **Rôle** : `ROLE_ADMIN`
-   **Nom** : GILLES Mathias

### Admin secondaire

-   **Email** : `admin2@gmail.com`
-   **Mot de passe** : `admin2`
-   **Rôle** : `ROLE_ADMIN`
-   **Nom** : ADMIN2 admin2

## Utilisateurs

### ALLENIC Aurélien

-   **Email** : `aurel@gmail.com`
-   **Mot de passe** : `aurel`
-   **Rôle** : `ROLE_USER`
-   **Nom** : ALLENIC Aurélien

### USER user

-   **Email** : `user@gmail.com`
-   **Mot de passe** : `user`
-   **Rôle** : `ROLE_USER`
-   **Nom** : USER user

## 🔐 Sécurité

-   Authentification par formulaire de connexion
-   Hashage des mots de passe
-   Protection CSRF sur les formulaires
-   Contrôle d'accès basé sur les rôles

## 📋 Fonctionnalités Techniques

### Système de Messaging

-   Attribution de points en masse via Symfony Messenger
-   Traitement asynchrone des notifications

## 🐛 Limitations Connues

-   Interface utilisateur basique (focus sur la fonctionnalité)
-   **Pas d'inscription** : seuls les comptes prédéfinis sont disponibles
-   Design minimal (première expérience Symfony)
-   Pas de système de traduction

---

_Projet réalisé dans le cadre du cours Symfony - Première expérience avec ce framework_
