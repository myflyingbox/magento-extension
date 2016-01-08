Module Magento MyFlyingBox
==========================

Module Magento fournissant une interface pour les services web MyFlyingBox (cf. https://www.myflyingbox.com), fournis par MYFLYINGBOX (société française).

IMPORTANT: il s'agit d'une version beta, à ne pas utiliser en production sauf si vous êtes en contact direct avec l'équipe de développement.

Assurez-vous de tester de manière exhaustive le module sur votre environnement de test avant de passer en production.

Il reste des choses à affiner, notamment en terme d'interface et de gestion d'erreurs. Nous sommes dessus. N'hésitez pas à nous contacter pour voir où nous en sommes ou suggérer des améliorations.

## Présentation

Ce module fournit deux ensembles de fonctionnalités indépendants :
- la commande d'expéditions à travers l'API MyFlyingBox par une interface back-office dédiée (commande par commande, ou à travers des actions de masse)
- le calcul automatisé des coûts de transport pour le panier d'un client, au moment de la commande

## Installation

Pour utiliser ce module, vous avez besoin de :
- un Magento récent, installé et fonctionnel (1.7 à 1.9)
- le module php-curl activé sur le serveur (pour les appels vers l'API MyFlyingBox)
- un compte MyFlyingBox actif et les clés d'API correspondantes

Le module a été développé et testé sur Magento 1.7 et 1.9 et peut donc ne pas être compatible avec d'anciennes versions. Assurez-vous de bien tester le module avant de le déployer en production. Des problèmes de compatibilité avec d'autres modules sont toujours possibles.

### Installation à partir des sources

Clonez le code du module à partir de notre dépôt Github (note : le répertoire du module DOIT être myflyingbox) :

```bash
git clone --recursive https://github.com/myflyingbox/magento-extension.git myflyingbox
```

Une des bibliothèques externes du module (php-lce) a des dépendances spécifiques, que vous devez initialiser explicitement avec 'composer' :

```bash
cd myflyingbox/lib/php-lce
curl -s http://getcomposer.org/installer | php
php composer.phar install
```

Enfin, copiez le contenu du module dans le répertoire racine de Magento :
```bash
cp -R myflyingbox/* /path/to/magento/
```


### Installation à partir d'un paquet

Ouvrez la [liste des publications](https://github.com/myflyingbox/magento-extension/releases) et téléchargez le dernier paquet disponible (premier de la liste).

Extraire le fichier depuis le répertoire racine de Magento.

## Configuration

### Configuration générale

Aller dans System -> Configuration, ensuite Sales -> Shipping Methods. La méthode d'expédition 'MyFlyingBox' devrait être listée et contenir un formulaire de configuration.

Les paramètres suivants peuvent être ajuster sur cette page de configuration :
* Identifiant d'API MyFlyingBox (correspondant à l'environnement utilisé); ces identifiants ont été transmis par email lors de l'ouverture du compte.
* Environnement à utiliser (staging où production)
* Informations expéditeur par défaut, automatiquement utilisées lors de l'initialisation d'une expédition MyFlyingBox
* Valeurs par défaut pour le contenu des colis et le pays d'origine de la marchandise, chargées automatiquement lors de l'initialisation d'une expédition
* Règles de calcul et d'ajustement des tarifs lors du check-out par le client (arrondis, surcoût fixe ou pourcentage)

### Configuration de la correspondance poids/dimensions

Ouvrir la page -> MyFlyingBox -> Dimensions setup

Le module MyFlyingBox ne peut pas automatiquement déterminer le conditionnement final d'un panier donné, et va donc utiliser une table de correspondance entre un poids (le poids total du panier) et des dimensions (les dimensions estimées pour ce poids) afin d'initialiser des informations de colisage les plus proches possibles de la réalité.
Le principe est simple, mais la bonne configuration peut être complexe si votre magasin contient un catalogue hétérogène (à la fois des articles légers mais volumineux et des articles lourds et compacts).

Il est très important que vous preniez le temps de configurer correctement ces options, car elles vont déterminer le volume utilisé pour estimer les coûts d'expédition présentés au client. Dans le cas des expéditions internationales le poids volumétrique (qui prend en compte le volume réel des colis) est systématiquement utilisé. Si vous ne configurez pas correctement ce tableau, vous risquez d'avoir d'importantes différences entre ce que le client va payer en frais d'expéditions et ce que vous allez être effectivement facturés par MyFlyingBox. Notez que MyFlyingBox vous facturera systématiquement sur la base du poids réel communiqué par le transporteur après la réalisation de la prestation.

Si votre catalogue est trop hétérogène pour pouvoir déterminer de manière fiable une correspondance poids/volume, nous vous recommandons d'utiliser une stratégie de prix fixes afin de limiter les écarts de tarifs.

### Configuration des services

Ouvrez la page Sales -> MyFlyingBox -> Services setup

Commencez par initialiser la liste des services disponibles en cliquant sur le bouton "Refresh services from API". Tous les services disponibles vont être récupéré à travers l'API.
Vous allez ensuite pouvoir configurer et activer chaque service indépendamment.

Vous trouverez ci-dessous une description des différentes options disponibles.

#### Stratégie d'assurance

L'option assurance 'ad valorem' est disponible pour la plupart des services. Deux options vous permettent de définir le comportement par défaut :
- utiliser l'assurance ad valorem par défaut
- montant minimum à partir duquel l'assurance ad valorem doit être utilisée (si le montant de la commande est inférieure à ce montant, l'assurance ad valorem ne sera pas souscrite par défaut)

Cette option est utilisé dans les mécanismes suivants :
- calcul des coûts d'expédition pendant le checkout de commande par le client (sauf si vous utilisez une grille de tarifs fixes)
- expédition à travers une action en masse
- pré-sélection de l'option assurance ad valorem lors d'une création/modification manuelle d'expédition en back-office

#### Données visibles par le client

Pour chaque service vous pouvez définir le nom du transport et le nom du service tel qu'il sera visible par le client (dans l'email de confirmation d'expédition et sur l'interface web de la commande)

Il est également recommandé de configurer l'URL de suivi. Contactez-nous pour connaître l'URL correspondant aux services que vous utilisez (dans l'avenir ce paramètre sera totalement automatisé, mais pour l'instant il vous faut le définir manuellement).
Si vous ne configurez pas d'URL de suivi, le client n'aura pas accès aux information de suivi.

#### Grille de tarifs fixe

Si vous activez l'option 'flat-rate pricing', les coûts présentés au client seront basés sur un simple tableau de correspondance poids/prix. Les prix retournés par l'API MyFlyingBox seront ignorés.

Cette option n'affecte que le processus de validation de commande (checkout). Les tarifs présentés en back-office seront toujours basés sur les appels API, sans ajustement.

#### Restrictions sur les destinations

Par défaut, l'API MyFlyingBox retourne toutes les offres disponibles pour une route donnée.
Si vous souhaitez restreindre certains services, vous pouvez utiliser des règles d'inclusion/exclusion par pays et/ou code postal.

Toutes les règles d'inclusion et d'exclusion sont systématiquement évaluées, lorsque renseignées. Par exemple, si vous spécifiez 'FR' comme règle d'inclusion le service sera présenté uniquement pour la France, et si vous spécifiez en plus 'FR|75' dans les règles d'exclusion le service ne sera pas présenté pour les codes postaux parisiens.

Ces paramètres n'ont aucun effet en back-office, et ne limitent que les services présentés lors du checkout.

#### Activation

Pensez à activer les services que vous souhaitez présenter au client, une fois configurés.

Ce paramètre n'a aucun effet en back-office, et ne limite que les services présentés lors du checkout.


## Utilisation

### Fonctionnalités front-office (perspective client)

#### Frais de livraison

Lorsqu'un client procède à la commande de son panier, des options de transports seront proposées dynamiquement basé sur les offres récupérées depuis l'API MyFlyingBox et les règles de calcul que vous aurez spécifié sur la page de configuration du module.

Le client peut ensuite sélectionner l'une de ces offres, et sera facturé le montant calculé.

#### Suivi

Lorsqu'une expédition MyFlyingBox est commandée en back-office, cela déclenche le processus standard d'expédition de Magento, enregistrant ainsi une expédition pour la commande concernée et le numéro de suivi pour chaque colis, si vous avez renseigné l'URL de suivi dans la configuration du service.
Ainsi le client peut ouvrir le le lien de suivi depuis le mail de notification ou bien l'interface web de la commande.

### Back-office

#### Expédition manuelle (une à une)

Sur la page de gestion d'une commande (en back-office), vous pouvez initier une expédition avec le bouton 'Ship with MyFlyingBox'.

Les nouvelles expéditions chargent automatiquement toutes les données nécsesaires (expéditeur à partir de votre configuration transporteur, destinataire à partir de la commande, et colis à partir du poids du panier et la correspondance avec les dimensions).

Lorsque vous avez vérifier et ajusté, le cas échéant, tous les détails de l'expédition, choisissez et commandez un service de transport (après avoir sélectionné, le cas échéant, une date d'enlèvement ou un point relais de livraison, ainsi que l'option assurance ad valorem).
Notez que toutes les offres MyFlyingBox disponibles sont présentées en back-office, sans prise en compte de la configuration des services dans Magento, qui n'est utilisée que pour contrôler la manière dont les services sont présentés au client lors de la commande.

Le service choisi par le client est en principe sélectionné par défaut, ainsi que le point relais choisi, si applicable.

#### Expédition automatique (action en masse)

Au lieu de procéder commande par commande, vous pouvez automatiquement déclencher et valider vos expéditions MyFlyingBox pour plusieurs commandes d'un coup.
Sélectionnez les commandes que vous souhaitez expédier et choisissez dans le menu Actions l'entrée 'MyFlyingBox: ship orders'. Comme pour le processus manuel, toutes les données seront chargées automatiquement sur la base des informations disponibles dans la commande et de la configuration du module et du service, mais au lieu de laisser l'expédition en statut 'brouillon' celle-ci sera automatiquement confirmée.

Notez que cette action est définitive, et vous ne pourrez pas vérifier les données des expéditions. Soyez donc très prudent dans l'utilisation de cette fonctionnalité.

Aussi, l'expédition à travers une action en masse n'est possible que pour les commandes ne contenant qu'un seul article. Cette limitation, destinée à prévenir des écarts trop importants entre le colisage calculé et le colisage réel, sera levée dans l'avenir lorsque des mécanismes plus avancés seront mis en place.

Le téléchargement en masse des étiquettes d'expédition n'est pas encore disponible mais le sera très prochainement.

## Support

Ce module est maintenu directement par les développeurs de l'API MyFlyingBox. Vous pouvez nous contacter à l'adresse tech@myflyingbox.com si vous avez besoin d'aide pour l'utilisation, le paramétrage ou l'installation du module sur votre instance Magento. Si vous n'avez pas encore de compte MyFlyingBox ou de clés d'API, envoyez un message à info@myflyingbox.com et vous serez recontacté par notre équipe commerciale.
