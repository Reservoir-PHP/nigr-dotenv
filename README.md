## Get started:

### Install library:

```bash
    composer require nigr/dotenv:@dev
```

### Call function than parsed ENV-file:

```php
  use Nigr/Dotenv;

  $dotenv = new Dotenv();
  $dotenv->parse();
```

### If file locates not in root folder, pass as argument path to file in function envPasre():

```php
  $dotenv = new Dotenv();
  $dotenv->parse("/env/.env.dev");
```

### For call variable, can use $_ENV, $_SERVER and getenv():

```php
  $_ENV['DB_USER'];
  $_SERVER['DB_HOST'];
  getenv('DB_PORT');
``` 
