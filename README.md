# Repository del corso di Laboratorio di Ingegneria del Software

**Requisiti:**

- Linux (dual-boot o macchina virtuale) o in alternativa docker desktop per mac o windows (alcuni passaggi potrebbero
richiedere aggiustamenti in questo caso.)
- Docker-engine
- Docker-compose
- Make

**Passaggi per installare Symfony e creare un nuovo progetto:**
1. Clonare questo repository
2. Spostarsi nella cartella del repository
3. Usare il comando `make start`. Se l'esecuzione fallisce per permessi mancanti, seguire [questa](https://docs.docker.com/engine/install/linux-postinstall/#manage-docker-as-a-non-root-user) guida e riprovare.
4. Controllare se funziona andando alla pagina "localhost:8080"
5. Usare il comando `make shell` per entrare nel container docker
6. Scaricare Symfony utilizzando uno dei comandi che si trova sul loro sito https://symfony.com/download
7. Usare il comando git config --global user.email "example@example.com"
8. Sempre dentro il container utilizzare il comando "symfony new --no-git --version=^6.4

**Collegare il DB a PHPStorm**
1. Da phpstorm, andare su Database -> New -> Datasource, selezionare Postgres
2. Compilare i campi seguenti con i valori specificati
	- user = "dbuser"
	- password = "segreta"
	- database = "app" <-- importante!
3. eseguire "Test connection" ed applicare le modifiche
