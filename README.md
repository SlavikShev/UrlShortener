# Project Deployment Instructions

To deploy this project locally using Docker, follow these steps:

1. Navigate to the `docker` directory:
    ```bash
    cd docker
    ```

2. Initialize the project using the following make command:
    ```bash
    make init
    ```

3. Set up a cron job to run the console command `php bin/console app:delete-expired-links`:
    ```bash
    * * * * * php /path/to/your/project/bin/console app:delete-expired-links
    ```

   Make sure to replace `/path/to/your/project` with the actual path to your project.

   Note: All make commands should be executed from within the `docker` folder.

### Working with Docker

- To open a bash shell inside the Docker container, run:
    ```bash
    make ssh
    ```

- Run tests using PHPUnit inside the shell:
    ```bash
    php bin/phpunit
    ```

- Start the Docker container:
    ```bash
    make start
    ```

# About the Project

The project generates short links ranging from 'aaaaaaa' to '999999', using a combination of lowercase letters and numbers. For example, 1 corresponds to 'aaaaaaa', 2 to 'aaaaaab', and so forth.

There are 36^6 (2,176,782,336) variants of links available. If more variants are needed, additional symbols like A-Z or special symbols can be added. Alternatively, if the current set is insufficient, consider increasing the length of the link from 7 to include more symbols.

The URL shortener uses an `unsignedBigInteger` for the ID, providing 2^64 (18,446,744,073,709,551,616) variants of links. This ensures ample availability for the foreseeable future.

Links are stored for durations ranging from 1 day to 1 month, providing flexibility in link expiration management.
