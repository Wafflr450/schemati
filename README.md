# Schemati

## Setup

### Using Sail

-   Install docker [documentation](https://docs.docker.com/install/linux/docker-ce/ubuntu/#set-up-the-repository)
-   Make sure docker is running in rootless mode [documentation](https://docs.docker.com/install/linux/linux-postinstall/)
-   Install docker-compose [documentation](https://docs.docker.com/compose/install/)
-   :warning: **MAKE SURE .env IS SET UP CORRECTLY** or sail will create a broken database
    -   Copy .env.example to .env for default config
        ```bash
        cp .env.example .env
        ```
-   Bootstrap sail
    -   Install composer and dependencies
        ```bash
        docker run --rm \
            -u "$(id -u):$(id -g)" \
            -v $(pwd):/var/www/html \
            -w /var/www/html \
            laravelsail/php81-composer:latest \
            composer install --ignore-platform-reqs
        ```
-   Create an alias for sail, that alias can be added to your `~/.bashrc` or `~/.zshrc` to have it on terminal startup
    -   ```bash
        alias sail='./vendor/bin/sail'
        ```
    -   ```bash
        echo "alias sail='./vendor/bin/sail'" >> ~/.bashrc
        source ~/.bashrc
        ```
-   #### Run sail
    Sail is a docker-compose wrapper for Laravel, it will start the docker containers and run the Laravel application.
    It also manages php, npm, and artisan commands
    ```bash
    sail up -d
    ```
-   #### Run key generation
    The key will be added to the .env file, and it will be used for encrypting user sessions and other sensitive data
    ```bash
    sail artisan key:generate
    ```
-   #### Run migration

    Migrations are used to create the database schema

    ```bash
    sail artisan migrate
    ```

-   #### Run seeding

    Seeders are used to populate the database with dummy data or default values

    ```bash
    sail artisan db:seed
    ```

-   #### Run npm install

    npm install, will install all the js dependencies for the project

    ```bash
    sail npm install
    ```

-   #### Run npm run dev
    npm run dev, will compile the js and css files, and watch for changes in the files to recompile them automatically and auto-refresh the browser
    ```bash
    sail npm run dev
    ```
