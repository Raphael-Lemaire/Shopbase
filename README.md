# e-shop

## Ce projet nécessite les dépendances suivantes que vous utilisiez une docker ou une installation à la main la documentation d'installation des différentes dépendances ce trouve plus bas :
- PHP 8.1
- MySQL version 8.0.32
- Composer version 2.5.5


## Installation des dépendances
### L'explication de l'installation des dépendances ce trouver en fin du Readme 
Pour installer les dépendances, veuillez suivre les étapes ci-dessous :
1. Assurez-vous que PHP 8.1, MySQL version 8.0.32 et Composer version 2.5.5 sont installés sur votre système.
2. Clonez le projet depuis le dépôt Git.
3. Prenez le site ce trouvant dans le dossier shopbase le dossier database contient la base de donnée à intégré dans votre SGBDR donc MariaDB ou MYsql
4. Ouvrez une console et naviguez jusqu'au répertoire du projet.
5. Exécutez la commande `composer install` pour installer les dépendances du projet.


## Configuration de la connexion à la base de données
Aller dans le fichier global.php et modifier les information de connexion en conséquence :
        'username' =>'[Identifiant]',
        'password' => '[MotDePasse]',
        'dsn' => 'mysql:dbname=[Nomdelabase];host=[hoteDeConnexion/ip];port=3306;charset=utf8',

## Utilisation
Une fois que toutes les dépendances sont installées et que la base de données est configurée, vous pouvez exécuter l'application en utilisant votre serveur PHP préféré.

### Démarrage de l'application en local
Pour démarrer l'application, veuillez suivre les étapes ci-dessous :
1. Ouvrez une console et naviguez jusqu'au répertoire du projet et aller dans le dossier public qui est le point d'entré du site.
2. Exécutez la commande `php -S localhost:8000` pour lancer le serveur PHP intégré.
3. Accédez à l'application en ouvrant un navigateur Web et en visitant `http://localhost:8000`.
4. Pour accèder à l'interface admin du site utiliser les identifiants généré ci-dessous pour une première connexion ensuite supprimer le compte et créer un nouveau :
- Email : admin@admin.com
- Mot de passe : admin

## Installer les dépendances

### PHP

Installation des dépendances

sudo apt-get update

sudo apt-get install ca-certificates apt-transport-https software-properties-common wget curl lsb-release

Ajouter le dépôt pour PHP 8.1

curl -sSL https://packages.sury.org/php/README.txt | sudo bash -x

sudo apt-get update

Dès à présent, nous pouvons passer à l'installation de PHP 8.1 sur Debian 11 !

Installation de PHP 8.1


sudo apt-get install php8.1
Cette commande va permettre d'installer PHP 8.1 sur le serveur Linux. Pour ajouter l'intégration de PHP à Apache il faudra ajouter ce paquet supplémentaire :

sudo apt-get install libapache2-mod-php8.1
Redémarrez Apache pour prendre en charge ce nouveau module :

sudo systemctl restart apache2
À partir de là, PHP 8.1 est installé ! Vous pouvez vous en assurer avec la commande suivante :

php -v
Le résultat retourné met bien en évidence la présence de PHP 8.1 dans sa dernière version mineure :

Installation des extensions de PHP 8.1

sudo apt-get install php8.1-common php8.1-curl php8.1-bcmath php8.1-intl php8.1-mbstring php8.1-xmlrpc php8.1-mcrypt php8.1-mysql php8.1-gd php8.1-xml php8.1-cli php8.1-zip

### COMPOSER

sudo apt update

sudo apt install curl php-cli php-mbstring git unzip

cd ~

curl -sS https://getcomposer.org/installer -o composer-setup.php

HASH=`curl -sS https://composer.github.io/installer.sig`

echo $HASH

php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

Tester votre installation avec la commande suivante :

composer

### MARIADB / MYSQL

sudo apt update

sudo apt install mariadb-server

sudo mysql_secure_installation

Votre compte root est déjà protéger donc vous pouvez faire non n

Switch to unix_socket authentication [Y/n] n

Change the root password? [Y/n]

sudo mariadb
## Auteur
Ce projet a été créé par Raphael Lemaire.
