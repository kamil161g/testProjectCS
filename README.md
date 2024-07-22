Sklonuj repozytorium na swoją lokalną maszynę:

git clone https://github.com/kamil161g/testProjectCS Przejdź do katalogu aplikacji:cd [nazwa-katalogu-aplikacji] Uruchom kontenery Docker za pomocą Docker Compose:

docker-compose up --build Flagę --build używamy, aby upewnić się, że obrazy Docker zostaną zbudowane przed uruchomieniem. Jest to szczególnie przydatne przy pierwszym uruchomieniu lub gdy dokonano zmian w konfiguracji Docker.

Komende odpalamy w kontenrze cs-php.

Użyj docker ps znajdz swoj kontener i wejdz w niego np: docker exec -it cs-php /bin/bash

Po uruchomieniu kontenera pierwszym krokiem powinno być zainstalowanie zależności projektu za pomocą Composera. Aby to zrobić, musisz wejść do kontenera, w którym zainstalowany jest PHP, jak opisano w poprzedniej wiadomości.

Po wejściu do kontenera wykonaj poniższe polecenie, aby zainstalować zależności:

composer install

Nastepnie uruchom: php bin/console doctrine:migrations:migrate

Aby uruchomić testy Twojej aplikacji, użyj: ./vendor/bin/phpunit

Z baza łaczymy sie po danych:

Dodany jest doctrine:fixtures, doda automatcznie dane Product do testu. 
Uruchamiamy go: php bin/console doctrine:fixtures:load

127.0.0.1 root password 3308

strona dostepna pod: http://localhost:8888/

dokumetnacja dla requestów: [https://documenter.getpostman.com/view/8640351/2sA3JKeNSK](https://documenter.getpostman.com/view/8640351/2sA3kVjM6H)
