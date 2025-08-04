# Eco Garden & Co

### Configurer la base de données

1. Créez un fichier `.env.dev` à la racine du projet à partir de `.env.`
2. Modifiez les variables d'environnement pour correspondre à votre configuration de base de données.

```
DATABASE_URL="postgresql://postgres:P@ss1234@database:5432/eco_garden_co?serverVersion=17&charset=utf8"
```

### Lancer l'application

```bash
# Cette commande va créer les conteneurs, les images et les volumes nécessaires
make prepare

docker compose up -d

# Pour la première fois, il faut lancer les migrations

make setup
```

> **Penser à ajouter la config pour les JWT**
