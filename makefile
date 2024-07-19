# Makefile

# Variable pour le chemin vers le binaire de Symfony CLI
SYMFONY = symfony

# Variable pour la commande Docker Compose
DOCKER_COMPOSE = docker-compose

# Définir les cibles
.PHONY: up down

# Cible pour démarrer Docker et le serveur Symfony
up:
	$(SYMFONY) server:stop
	$(DOCKER_COMPOSE) down
	$(DOCKER_COMPOSE) up -d
	$(SYMFONY) server:start -d

# Cible pour arrêter Docker et le serveur Symfony
down:
	$(SYMFONY) server:stop
	$(DOCKER_COMPOSE) down
