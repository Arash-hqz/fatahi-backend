# Fatah

A Laravel-based web application fully dockerized for easy development and deployment.

## Description

This project is a standard Laravel application containerized using Docker and Docker Compose. It includes PHP, Nginx, and MySQL services to run the application locally.

## Prerequisites

- Docker (version 20.10 or later)
- Docker Compose (version 1.29 or later)

## Installation and Setup

Follow these steps to get the project running on your local machine:

1. **Clone the repository**:
   ```bash
   git clone <your-repository-url>
   cd fatah
   ```

2. **Set up environment variables**:
   ```bash
   cp .env.example .env
   ```
   Edit the `.env` file to configure your database and other settings. The default database configuration is set to use the MySQL container.

3. **Build and start the containers**:
   ```bash
   docker-compose up -d
   ```
   This command will build the Docker images and start the services in detached mode.

4. **Install PHP dependencies**:
   ```bash
   docker-compose exec app composer install
   ```

5. **Generate application key**:
   ```bash
   docker-compose exec app php artisan key:generate
   ```

6. **Run database migrations**:
   ```bash
   docker-compose exec app php artisan migrate
   ```

7. **(Optional) Seed the database**:
   ```bash
   docker-compose exec app php artisan db:seed
   ```

## Usage

Once the setup is complete, you can access the application by opening your browser and navigating to:

- **Application**: http://localhost:8080

## Development Commands

- **Stop the containers**:
  ```bash
  docker-compose down
  ```

- **View logs**:
  ```bash
  docker-compose logs -f
  ```

- **Access the application container**:
  ```bash
  docker-compose exec app bash
  ```

- **Access the database**:
  ```bash
  docker-compose exec db mysql -u laravel -p laravel
  ```

## Project Structure

- `app/`: Application code
- `config/`: Configuration files
- `database/`: Migrations, seeders, and factories
- `public/`: Public assets
- `resources/`: Views, CSS, JS
- `routes/`: Route definitions
- `storage/`: File storage
- `tests/`: Test files
- `.docker/`: Docker-related files (Dockerfiles, configs)

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests
5. Submit a pull request

## License

This project is licensed under the MIT License.
