# Projet 7 Créez un web service exposant une API

### Formation DA php / Symfony

## Description du projet 

Le projet BilMo (exposer une api REST) à été réalisé avec :
- php 7.3
- Le framework Symfony (version 5.3.6)
- ApiPlatform
- Authentification via JWT

## Documentation de l'api

La documentation de l'api est accessible via l'url : http(s)://MonUrl/api  
Cette documentation comprend l'ensemble des endpoints de l'api accessiblent par un user authentifié ou anonyme

![GitHub img](Public/img/documentation.png)

## Installation du projet

#### 1. Récupération du projet sur GitHub
- Créer un dossier pour le projet
- Initialiser Git à la racine du projet en executant la commande `git init` dans le terminal
- Copier le lien du repository sur GitHub https://github.com/Etienne21000/BileMo.git
- Executer les commandes : 
>`git clone https://github.com/Etienne21000/BileMo.git` <br>
>`composer install`

#### 2. Base de donnée

##### a. Configuration des fichiers
- Editer le fichier .env pour créer la connexion à votre base de données
- Lancer votre server local (vous pouvez passer par Symfony cli)

##### b. Création de la base de données
- Executer les commandes :
>`symfony console doctrine:database:create` <br>
>`symfony console make:migration` <br>
>`symfony console doctrine:migrations:migrate` <br>

#### 3. Chargement des fixtures
- Executer la commande doctrine `symfony console doctrine:fixtures:load` 

## Utilisation de l'api BileMo

#### 1. Endpoints accessible aux Users anonymes
Les endpoints mobiles en methode GET sont accessibles à tous les utilisateurs, qu'ils soient authentifiés ou non

#### 2. EndPoints user autentifié
##### a. Génération d'un JWT
Différents user_roles sont disponibles : 
- role_user
- role_admin
- role_superadmin  

Pour authentifier un user il faut :
- rendre sur le endpoint /api/login
- renseigner ses informations de connexion 
```json
{
  "username": "monadressemail.com",
  "password": "monmotdepasse"
}
```
- Le Json Web Token sera alors renvoyé et l'utilisateur pourra se connecter via l'authentification Bearer 
- Le Token à une durée de validité de 5 jours

##### b. endPoints
Le role_admin correspond à un client de BileMo, il peut :
- consulter la liste des produits BileMo 
- consulter les détails d’un produit BileMo 
- consulter la liste des utilisateurs inscrits liés à un client sur le site web 
- consulter le détail d’un utilisateur inscrit lié à un client 
- ajouter un nouvel utilisateur lié à un client 
- supprimer un utilisateur ajouté par un client

Le role_superadmin correspond a l'administrateur BileMo, il a accès à l'ensemble des endpoints de l'api

Le role_user ou anonyme permet à un utilisateur non authentifié de consulter la liste et le détail d'un mobile
