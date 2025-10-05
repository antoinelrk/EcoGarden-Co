# Eco Garden & Co

### Configurer la base de données

1. Créez un fichier `.env.dev` à la racine du projet à partir de `.env.`
2. Modifiez les variables d'environnement pour correspondre à votre configuration de base de données.

Ajouter cette ligne pour connecter directement l'app à la DB de docker 
```
DATABASE_URL="postgresql://postgres:P@ss1234@database:5432/eco_garden_co?serverVersion=17&charset=utf8"
```

### Préparer le projet

```bash
# Cette commande va créer les conteneurs, les images et les volumes nécessaires
make prepare

docker compose up -d --build
```

### Configurer les JWT

Générer la paire de clés :

```shell
php bin/console lexik:jwt:generate-keypair
```

Ajouter votre passphrase.

### OpenWeatherMap API

Créer un compte sur [OpenWeatherMap](https://openweathermap.org/api) pour obtenir une clé API.
*L'activation de la clé peut mettre 24h, penser à le faire assez tôt.*

Ajouter votre clé API dans le fichier `.env.dev` :

```dotenv
WEATHER_API_KEY="votre_cle_api"
```

### Lancer l'application

```bash
# Pour la première fois, il faut lancer les migrations

make setup
```
