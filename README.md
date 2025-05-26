# Les Tests avec Symfony
## Test unitaire
```php bin/phpunit```

## installation Entity Product

* name - string 255 not null
* price - float not null
* description - text not null
* date - 'datetime' not null

## installation des fixtures

```composer require --dev orm-fixtures```

```composer require fakerphp/faker```

## installation de bootstrap avec asset mapper

```php bin/console importmap:require bootstrap```

dans assets/app.js les fichier de bootstrap sont dans le vendor
```
/* bootstrap via assetMapper */
import 'bootstrap/dist/css/bootstrap.min.css';
/* pour le js */
import 'bootstrap';
```

avant de déployer
```php bin/console asset-map:compile```

## test de la bdd
Dans __.env.test__

```DATABASE_URL="mysql://root:root@127.0.0.1:8889/testsymfo25_test"```

Puis dans le terminal

```php bin/console doctrine:database:create --env=test```

```php bin/console doctrine:schema:create --env=test```