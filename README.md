**Requisiti:**

- Linux(dual-boot o macchina virtuale)
- Docker-engine
- Docker-compose
- Make

**Passaggi per installare Symfony e creare un nuovo progetto:**
1. Clonare il repository https://github.com/RBastianini/labingsoft
2. Spostarsi nella cartella del repository
3. Usare il comando "sudo make start"
4. Controllare se funziona andando alla pagina "localhost:8080"
5. Usare il comando "sudo make shell" per entrare nel container docker
6. Scaricare Symfony utilizzando uno dei comandi che is trova sul loro sito https://symfony.com/download
7. Sempre dentro il container utilizzare il comando "symfony new --no-git --version=^6.4
8. Usare il comando git config --global user.email "example@example.com"
