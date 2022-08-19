# Wymagania: 

- docker
- docker-compose
- posiadanie skonfigurowanego konta z aplikacją DropBox https://www.dropbox.com/developers/reference/getting-started

# Instalacja

1. W katalogu z aplikacją wykonanie: `docker-compose up -d`
2. Wykonanie polecenia `docker exec -it php bash`
3. Wykonanie polecenia `composer install --no-dev`

# Konfiguracja

W pliku .env zdefiniowanie tokena uwierzytelniającego DROPBOX_TOKEN na podstawie danych we własnym koncie DropBox

# Przeskalowanie obrazka i wysłania na serwer DropBox

1. Przesłanie zdjęcia do katalogu var/files np.example.jpg
2. Wykonanie polecenia `docker exec -it php bash`
3. Wykonanie polecenia `bin/console app:resize-image --fileName=nazwa_pliku` np. `bin/console app:resize-image --fileName=example.jpg`

