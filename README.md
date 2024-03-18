Uruchomienie projektu:
- cd docker
- docker compose up -d
- docker exec -it h2h-php bash
- php bin/console doctrine:migrations:migrate

Dokumentacja:
- http://localhost:8080/documentation

Uruchomienie testów:
- docker exec -it h2h-php bash
- php bin/console --env=test doctrine:database:create
- php bin/console --env=test doctrine:schema:create
- php bin/phpunit

Przykład:
root@73fc309569ee:/var/www/symfony# php bin/phpunit
PHPUnit 9.6.17 by Sebastian Bergmann and contributors.

Testing 
............                                                      12 / 12 (100%)

Time: 00:00.461, Memory: 28.00 MB

OK (12 tests, 42 assertions)
root@73fc309569ee:/var/www/symfony# 