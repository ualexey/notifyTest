# Symfony Docker

A [Docker](https://www.docker.com/)-based installer and runtime for the [Symfony](https://symfony.com) web framework,
with [FrankenPHP](https://frankenphp.dev) and [Caddy](https://caddyserver.com/) inside!

![CI](https://github.com/dunglas/symfony-docker/workflows/CI/badge.svg)


# Notification Service API

This is a RESTful API for sending and retrieving notifications.

## Before Start
1. Clone repository into your IDE.
2. Install all necessary packages
`php composer install`
3. Run migration command to create DB tables
`php bin/console doctrine:migrations:migrate`
4. Run tests
`php vendor/bin/phpunit`

## Endpoints

## Send Notification

- **URL:** `/notification/send`
- **Method:** POST
- **Request Body:**
  ```json
  {
      "userId": "12345678",
      "type": "sms",
      "message": "This is a test message",
      "recipient": "example@test.com"
  }
  ```
  
### Success Response. 
Code: 200
 ```json
    {
        "message": "Notification sent successfully"
    }
```

### Error Response. 
Code: 500
 ```json
    {
      "error": "Failed to send notification: Error message"
    }
```

## Get Notifications
Retrieve notifications for a specific user.
- **URL:** `/notification/get/{user_id}`
- **Method:** GET

URL Params:
- **user_id:** `The ID of the user to retrieve notifications for.`

### Success Response:  
Code: 200
 ```json
 {
    "notifications": [
        {
            "id": 1,
            "user_id": "12345678",
            "updated_on": "2024-06-08T12:00:00Z",
            "status": "success",
            "recipient": "example@test.com",
            "channel": "sms",
            "provider": "Twilio"
        },
        {
            "id": 2,
            "user_id": "12345678",
            "updated_on": "2024-06-07T10:30:00Z",
            "status": "pending",
            "recipient": "example@test.com",
            "channel": "email",
            "provider": "SendGrid"
        }
    ]
 }
 ```

### Error Response:
Code: 400
 ```json
{
"message": "Invalid user_id"
}
```

## Console Commands
Send Delayed Notifications.
This command attempts to resend delayed notifications.

- **Command:** `php bin/console send:delayed {user_id}`  

### Success Output. 
Messages have been successfully sent.
### Error Output. 
Unexpected error during sending: Error message.

## Config Channels and Providers

### Turn on/off channel
You can change channel status in 
`config/packages/channel_status.yaml`
file.

### Add/Remove channel
1. You can add/remove channel by creating/deleting channel class providers.
**Like this:** `App\Domain\Notification\SmsNotificationProviders`.
2. Inject all necessary dependencies in
`config/services.yaml` file.

3. Add condition in
`App\Application\Service\NotificationService`
class.

### Add/Remove provider
1. Create new provider adapter.
   **Like this:** `App\Infrastructure\Notification\Adapter`.
2. Inject new adapter class into specific channel class provider in
   `config/services.yaml` file.


## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull always -d --wait` to set up and start a fresh Symfony project
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Features

* Production, development and CI ready
* Just 1 service by default
* Blazing-fast performance thanks to [the worker mode of FrankenPHP](https://github.com/dunglas/frankenphp/blob/main/docs/worker.md) (automatically enabled in prod mode)
* [Installation of extra Docker Compose services](docs/extra-services.md) with Symfony Flex
* Automatic HTTPS (in dev and prod)
* HTTP/3 and [Early Hints](https://symfony.com/blog/new-in-symfony-6-3-early-hints) support
* Real-time messaging thanks to a built-in [Mercure hub](https://symfony.com/doc/current/mercure.html)
* [Vulcain](https://vulcain.rocks) support
* Native [XDebug](docs/xdebug.md) integration
* Super-readable configuration

**Enjoy!**

## Docs
1. [Options available](docs/options.md)
2. [Using Symfony Docker with an existing project](docs/existing-project.md)
3. [Support for extra services](docs/extra-services.md)
4. [Deploying in production](docs/production.md)
5. [Debugging with Xdebug](docs/xdebug.md)
6. [TLS Certificates](docs/tls.md)
7. [Using MySQL instead of PostgreSQL](docs/mysql.md)
8. [Using Alpine Linux instead of Debian](docs/alpine.md)
9. [Using a Makefile](docs/makefile.md)
10. [Updating the template](docs/updating.md)
11. [Troubleshooting](docs/troubleshooting.md)

## License

Symfony Docker is available under the MIT License.

## Credits

Created by [Kévin Dunglas](https://dunglas.dev), co-maintained by [Maxime Helias](https://twitter.com/maxhelias) and sponsored by [Les-Tilleuls.coop](https://les-tilleuls.coop).
