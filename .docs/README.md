# DAG

Dag is PHP library to generate your fake API.

It is based on [faker](https://github.com/fzaninotto/Faker) & [alice](https://github.com/nelmio/alice/).

## Get Started

1. Create new project structure.

   ```
   project/
   ├── api/
   │   ├── dag.neon
   │   └── index.php
   └── now.json
   ```

2. Install `ninjify/dag` package.
3. Create `api/index.php` file.

    ```php
    <?php

    require __DIR__ . '/../vendor/autoload.php';

    Dag\Grub::boot();
   ```

4. Create `api/dag.neon` file.

    ```yaml
    dag:
        endpoints:
            /api/:
                generator: alice
                data:
                    schema:
                        stdClass:
                            api:
                                name: Acme API
                                version: "1.0"
                                time: "<(time())>"

            /api/v1/users:
                generator: alice
                data:
                    schema:
                        stdClass:
                            "user{1..10}":
                                name: "<firstName()>"
                                surname: "<lastName()>"
                                email: "<email()>"
                                role: "<jobTitle()>"
                                createdAt: "<date_create()>"
                                lastLoggedAt: "<date_create()>"
   ```

5. Create `now.json` file. [[ZEIT Now](https://zeit.co/home)]

    ```json
    {
      "functions": {
        "api/index.php": {
          "runtime": "now-php@0.0.7"
        }
      },
      "routes": [
        { "src": "/api/(.*)",  "dest": "/api/index.php" }
      ]
    }
    ```

6. Call `now` in project folder.

7. Navigate `project.tld/api/` or `project.tld/api/v1/users`.
