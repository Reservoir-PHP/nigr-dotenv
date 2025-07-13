## Get started:
   1. Install library:
      ```bash
          composer install nigr/dotenv:@dev
      ```
   2. Call function than parsed ENV-file:
      ```php
        use Nigr/Dotenv;
    
        envParse();
      ```
   3. If file locates not in root folder, pass as argument path to file in function envPasre():
      ```php
        envParse("/env/.env.dev");
      ```
   4. For call variable, can use $_ENV, $_SERVER and getenv():
      ```php
        $_ENV['DB_USER'];
        $_SERVER['DB_HOST'];
        getenv('DB_PORT');
      ``` 
