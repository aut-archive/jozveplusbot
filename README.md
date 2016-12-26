# JozvePlus Bot
[![Docker Repository on Quay](https://quay.io/repository/aut-ceit/jozveplusbot/status "Docker Repository on Quay")](https://quay.io/repository/aut-ceit/jozveplusbot)
Telegram bot that gets your booklets, archives them and allows everyone else get and use them :)

### Maintainers: 
- [@mohamad-amin](https://github.com/mohamad-amin)
- [@pi0](https://github.com/pi0)

## Contribution guide
- Make sure you have everything:
    - MySQL
    - PHP with Curl Extension
    - PHP Composer
- Create your testing bot using @BotFather
- Create a local database
- Fork & Clone this repository
- Run `composer install`
- Copy `.env.example` to `.env`
- Config `.env` file
- Run `php src/migrate.php` to initialize DB
- Run `php src/cli.php` this will run an infinity loop to get messages every second.
- Make your fixes
- Reload cli process and test your changes
- Use `git add` `git commit` to add ONLY changed files
- Make PR
