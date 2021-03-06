# Feedback form

## Running
### Docker (Docker compose)
1. Copy .env.example to .env in root folder and in `frontend/`

2. Run all compose services
```bash
docker-compose up -d
```

3. Generate key and run migrations
```bash
docker-compose exec app bash
```

```bash
php artisan key:generate && \
php artisan migrate
```

> NOTE: you can run any artisan command from application container

4. Run frontend app
```bash
cd frontend && npm run dev
```

5. Open app url: http://localhost:3000 (or other link from terminal output) for form page
and http://localhost:3000/feedback for admin dashboard page (feedback list)

## Troubleshooting
1. Could not open file in append mode...

Solution:
Connect to container's terminal
```bash
docker-compose exec app bash
```

Then in container's terminal run:
```bash
chmod -R 775 storage && \
chmod -R 775 bootstrap/cache && \
chown -R root:www-data .
```

## Misc
### Connect to database container outside of docker network
Connect to database using localhost:54320 and database credentials from .env
