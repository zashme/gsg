# Case study

## Installation

1. Docker and docker-composer should be installed

2. Use Docker Compose for launching all the infrastructural dependencies locally

   ```shell
   docker-compose up -d
   ```

3. Run migrations

   ```shell
   bin/console doctrine:migrations:migrate
   ```

4. You can use `api.http` file for running API requests
