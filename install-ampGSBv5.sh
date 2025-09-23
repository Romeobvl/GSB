#!/bin/bash
###############################################################################
##                                                                           ##
## Auteur : José GIL                                                         ##
##                                                                           ## 
## Synopsis : Script d’installation et de configuration automatique d'un     ##
##            serveur LAMP (Apache, MariaDB et PHP).                         ##
##            Et restauration du contexte GSB.                               ##
##                                                                           ##
## Date : 17/09/2025 (v5)                                                    ##
##                                                                           ##
## Scénario :                                                                ##
##                                                                           ##
## Changements v5 (17/09/2025) :                                             ##
##   - Passage en PHP 8.4                                                    ##
##   - Passage sur MariaDB au lieu de MySQL                                  ##
##                                                                           ##
## Changements v4 (18/12/2024) :                                             ##
##   - Reconstruction du contexte GSB avec les sauvegardes réalisées         ##
##                                                                           ##
## Changements v3 (20/08/2014) :                                             ##
##   - Passage en PHP 8.3                                                    ##
##                                                                           ##
## Changements v2 (19/12/2007) :                                             ##
##   - Passage en MySQL 8.2                                                  ##
##   - Passage en PHP 8.2                                                    ##
##                                                                           ##
##      1. Mise à jour des paquets et du système si besoin                   ##
##      2. Installation de MySQL                                             ##
##      3. Installation de Apache, PHP, Git, OpenSSH-Server et Fail2Ban      ##
##                                                                           ##
###############################################################################

# Test pour savoir si exécute le script en tant que root, sinon sudo !
if [ "$(whoami)" != "root" ]; then
    SUDO=sudo
fi

# Sortir du script en cas d'erreur
set -e

# Variables 
FICHIER_DE_LOG="`echo $HOME`/post-install.log"
MOT_DE_PASSE_ADMIN_MARIADB="P@ssw0rdMariaDB"

# Création du fichier de log
if [ ! -f $FICHIER_DE_LOG ]
then
    ${SUDO} touch $FICHIER_DE_LOG
fi

# Fonction pour l'affichage écran et la journalisation dans un fichier de log
suiviInstallation() 
{
    echo "# $1"
	${SUDO} echo "# $1" &>>$FICHIER_DE_LOG
    ${SUDO} bash -c 'echo "#####" `date +"%d-%m-%Y %T"` "$1"' &>>$FICHIER_DE_LOG
}

# Fonction qui gère l'affichage d'un message de réussite
toutEstOK()
{
    echo -e "  '--> \e[32mOK\e[0m"
}

# Fonction qui gère l'affichage d'un message d'erreur et l'arrêt du script en cas de problème
erreurOnSort()
{
    echo -e "\e[41m" `${SUDO} tail -1 $FICHIER_DE_LOG` "\e[0m"
    echo -e "  '--> \e[31mUne erreur s'est produite\e[0m, consultez le fichier \e[93m$FICHIER_DE_LOG\e[0m pour plus d'informations"
    exit 1
}

# Mise à jour de la liste des paquets et mise à jour de l'installation si besoin (2 opérations)
suiviInstallation "Mise à jour de la liste des paquets et mise à jour de l'installation si besoin (2 opérations)" 
${SUDO} apt-get -y update &>>$FICHIER_DE_LOG && toutEstOK || erreurOnSort 
${SUDO} apt-get -y upgrade &>>$FICHIER_DE_LOG && toutEstOK || erreurOnSort

# Installation des services Apache, MariaDB, PHP, Git, OpenSSH-Server et Fail2Ban
suiviInstallation "Installation des services Apache, MariaDB, PHP, Git, OpenSSH-Server et Fail2Ban"
${SUDO} apt-get -y install mariadb-server apache2 php libapache2-mod-php php-mysql git openssh-server fail2ban &>>$FICHIER_DE_LOG && toutEstOK || erreurOnSort

# Création d'un compte admin pour l'administration de MariaDB
suiviInstallation "Création d'un compte admin pour l'administration de MariaDB"
${SUDO} mariadb -u root -e "CREATE USER admin@'%'; GRANT ALL PRIVILEGES ON *.* to admin@'%' IDENTIFIED BY '$MOT_DE_PASSE_ADMIN_MARIADB' WITH GRANT OPTION; FLUSH PRIVILEGES;" &>>$FICHIER_DE_LOG && toutEstOK || erreurOnSort

#########################################################################################################
# Restauration du contexte GSB
suiviInstallation "Restauration du contexte GSB"
cd /var/www/html
# Récupération du dépôt GitHub (attention si le dépôt est privé à bien ajouter le token d'authentification)
${SUDO} git clone https://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@github.com/jgil83000/GSB.git &>>$FICHIER_DE_LOG && toutEstOK || erreurOnSort

# Import de la BDD
${SUDO} mariadb -u admin -p${MOT_DE_PASSE_ADMIN_MARIADB} < /var/www/html/GSB/resources/bdd/gsb_restore.sql &>>$FICHIER_DE_LOG && toutEstOK || erreurOnSort

# Configuration de Apache pour spécifier le dossier d'hébergement par défaut sur serveur web
suiviInstallation "Configuration de Apache pour spécifier le dossier d'hébergement par défaut sur serveur web (2 opérations)"
${SUDO} sed -i "s/^\tDocumentRoot \/var\/www\/html/\tDocumentRoot \/var\/www\/html\/GSB\/public/" /etc/apache2/sites-available/000-default.conf &>>$FICHIER_DE_LOG && toutEstOK || erreurOnSort
${SUDO} service apache2 reload &>>$FICHIER_DE_LOG && toutEstOK || erreurOnSort

# Fin
suiviInstallation "Le serveur est prêt !" && exit 0
