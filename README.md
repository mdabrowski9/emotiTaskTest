# Emoti Group Code Challenge

Repository contain code which should be answers to code challenge from Emoti Group.

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. Run `docker-compose build --pull --no-cache` to build fresh images
3. Run `docker-compose up` (the logs will be displayed in the current shell)
4. Run `docker-compose exec php sh`.
5. Inside container run:
   1. `php bin/console `
   2. `php bin/console `
   3. `php bin/console `
6. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
7. To stop the Docker containers run `docker-compose down --remove-orphans`.

**Enjoy!**

## Docs

1. [Troubleshooting](docs/troubleshooting.md)

