## Run
### Setup
```
composer install \
    && composer run-script post-root-package-install \
    && composer run-script post-create-project-cmd \
    && php artisan key:generate --ansi \
    && npm install \
    && npm run dev
```

### Config
Set the following environment variables in the `.env` file :
```
DB_ROOT_PASSWORD
DB_PORT
DB_DATABASE
DB_USERNAME
DB_PASSWORD
```

### Run
```
docker-compose up -d
```

### Migrate
`php artisan migrate`

### OAuth2
- Run `php artisan passport:client --public --name=App --redirect_uri=http://localhost:3000/login/callback -n`.
- Update `FIRST_PARTY_OAUTH_CLIENTS_IDS` environment variable in the `.env` file with the returned Client ID.
