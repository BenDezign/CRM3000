# CRM 3000

## Installation

### Compilation de l'environnement de DEV

A faire la premiere fois ou si on veut tout re-installer

```
docker-compose up -d --build
``` 

### Pour lancer le serveur

```
docker-compose up -d
```

### Pour stopper le serveur

```
docker-compose stop
```

### Pour installer les dependances PHP (via Composer)

```
docker-compose exec server composer install
```

### Pour mettre a jour les dependances PHP (via Composer)

```
docker-compose exec server composer update
``` 

### Pour mettre a jour la base de donnée

```
docker-compose exec server php bin/console doctrine:migration:migrate
```

### Pour créer le premier utilisateur admin

```
docker-compose exec server php bin/console user:create $email@email.com $mdp
``` 

### Email

```
Email client : Recu par l'email renseigné sur la fiche client (envoie automatique apres validation de la fiche client)
Email Facture : Recu par le user connecté (envoie manuelle depuis facture_edit)
Email utilisateur : Recu par l'email renseigné sur la fiche utilisateur (envoie automatique apres validation de la fiche utilisateur)
``` 

