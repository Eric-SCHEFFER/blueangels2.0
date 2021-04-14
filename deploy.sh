#!/bin/bash

# ============== SCRIPT DE DÉPLOIEMENT D'UN PROJET LOCAL VERS UN SERVEUR ==============

# Détails:
# Effectue une synchro avec simulation préalable (avec rsync) + un cache clear de symfony

# Utilisation:
# Completer la partie "Variables à renseigner" en début de script
# Placer ce script dans le dossier racine local de l'application que l'on veut déployer.
# Se placer dans le dossier du script, et le lancer avec la commande: ./deploy.sh
# Tout ce qui se trouve dans ce dossier sera envoyé sur le serveur, en excluant ceux inclus dans le fichier .gitignore qui doit être présent à la racine.
# Eric SCHEFFER 2020-12-11-18-33


# ====== Variables à renseigner ======
utilisateur="u104388791"
serveur="access867248383.webspace-data.io"
racineProjetDistant="~/bad_test"
php="php7.4-cli"

# Couleur texte et fond
NONE='\033[00m'
CYAN='\033[01;36m'
BLACK='\033[0;30m'
NONE_BG='\e[49m'
MAGENTA_BG='\e[45m'
GREEN_BG='\033[42m'

echo -e "${CYAN}Simulation de la synchro:"

# dry run (simule ce qui sera fait dans la commande rsync)
rsync -avn ./ $utilisateur@$serveur:$racineProjetDistant --exclude-from=.gitignore --exclude=".*"

echo -e "${BLACK}${GREEN_BG}Démarrer le déploiement pour $racineProjetDistant: Entrée - Ctrl C pour annuler${NONE_BG}${NONE}"
read

# lance réellement la commande rsync
rsync -av ./ $utilisateur@$serveur:$racineProjetDistant --exclude-from=.gitignore --exclude=".*"
# Nettoie le cache de symfony
ssh $utilisateur@$serveur " cd $racineProjetDistant && $php bin/console cache:clear"