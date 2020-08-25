# Symfony Telegram Console

Symfony bundle which provide symfony console command interface for Telegram using [Bot Api](https://core.telegram.org/bots/api).

## Installation

1) Add bundle definition to `bundles.php`.
2) Add bundle routings to application routing list (e.g. in `app/config/routing.yml`):
```
telegram_console:
    resource: '@TelegramConsoleBundle/Resources/config/routing.yaml'
```
3) Create you application bot using [@botFather](https://t.me/botFather).
Use the `/newbot` command to create a new bot. The BotFather will ask you for a name and username, 
then generate an authorization token (`<you_bot_token>`) for your new bot.
To get chat id you should add bot to chat and then open next link in browser `https://api.telegram.org/bot<you_bot_token>/getUpdates`.

More: [Telegram Guide](https://core.telegram.org/bots#6-botfather).

4) Configure Bundle like:
```
telegram_console:
    token: '<you_bot_token>'                     # token recieved from @botFather
    webhook_url: '/api/telegram/<you_bot_token>' # optional, by default '/<you_bot_token>'
    privacy:
        chat_id: '908908'                        # chat id which users have access to bot (add bot to chat and then open in browser https://api.telegram.org/bot<you_bot_token>/getUpdates)
        users:                                   # other users (usernames) which have access to bot
            - 'johndoe'                       
```
5) Configure PSR-18 and PSR-17 HTTP Client interoperability.
You app must have HTTP Client service which implements PSR-18 interface 
(Buzz, Symfony Client, Guzzle6 with adapter etc.):
```
# services.yaml using guzzle 6  http-interop/http-factory-guzzle

Psr\Http\Client\ClientInterface:
    class: 'Http\Adapter\Guzzle6\Client'
    arguments: ['@eight_points_guzzle.client.default']
```
For PSR-17 interoperability you can use:
```
# installs an efficient implementation of response and stream factories
# with autowiring aliases provided by Symfony Flex
composer require nyholm/psr7
```
6) Send configs (webhook url, command list) to Bot using console: `bin/console telegram-console:configure`.
7) Check it out.

## Troubleshooting

If you get error during bot configuration command runnig like `invalid webhook URL specified` 
then you need configure router for console env: 
```
# parameters.yml

router.request_context.host: 'example.org'
router.request_context.schema: 'https'
```