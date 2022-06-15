Symfony Json Schema PoC
========================
POC sull'utilizzo di una pre-validazione in json-schema e successiva validazione (e idratazione) del relativo DTO.

![Symfony 6.1.1](https://img.shields.io/badge/Symfony-6.1.1-purple.svg?style=flat-square&logo=symfony)
![Php 8.1.4](https://img.shields.io/badge/Php-8.1.4-blue.svg?style=flat-square&logo=php)

## Stack
* Symfony: 6.1.1
* Caddy: 2

#### Requisiti
- Docker

## Preparazione ambiente sviluppo
L'applicazione funziona all'interno di più containers docker. Preparare l'ambiente in questo modo:

### Utilizzo

#### Primo avvio
```shell
make build
```

#### Avviare i containers
```shell
make up
```

#### Fermare i containers
```shell
make stop
```

### Tools

#### Eseguire PhpStan
```shell
make stan
```

#### Eseguire il fix del coding style
```
make csfix
```

#### Eseguire la batteria di tests (funzionali ed unitari)
```shell
make test
```

#### Verificare il coverage
```shell
make coverage
```

#### Ottenere una lista dei comandi disponibili
```
make help
```

### Librerie / Tools
* [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)
* [PhpStan](https://phpstan.org/)
* [PHPunit](https://phpunit.de/)
* [Json Schema](https://github.com/justinrainbow/json-schema)
