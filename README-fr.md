Module Magento MyFlyingBox
==========================

Module Magento fournissant une interface pour les services web MyFlyingBox (cf. https://www.myflyingbox.com), fournis par MYFLYINGBOX (société française).

IMPORTANT: il s'agit d'une version beta, à ne pas utiliser en production sauf si vous êtes en contact direct avec l'équipe de développement.

Assurez-vous de tester de manière exhaustive le module sur votre environnement de test avant de passer en production.

## FONCTIONNALITES MANQUANTES

Les fonctionnalités suivantes ne sont pas encore implémentées et seront ajoutées le plus rapidement possible :
- sélection de point relais pendant le check-out du panier
- information de suivi des colis
- liste de prix statique (pour ignorer les tarifs de l'API et utiliser une grille statique)
- traductions en français

Il reste des choses à affiner, notamment en terme d'interface et de gestion d'erreurs. Nous sommes dessus. N'hésitez pas à nous contacter pour voir où nous en sommes ou suggérer des améliorations.

## Présentation

Ce module fournit deux ensembles de fonctionnalités indépendants :
- la commande d'expéditions à travers l'API MyFlyingBox par une interface back-office dédiée
- le calcul automatisé des coûts de transport pour le panier d'un client, au moment de la commande

## Installation

Pour utiliser ce module, vous avez besoin de :
- un Magento récent, installé et fonctionnel
- le module php-curl activé sur le serveur
- un compte MyFlyingBox actif et les clés d'API correspondantes

Le module a été développé et testé sur Magento 1.9.2.0 et peut donc ne pas être compatible avec d'anciennes versions. Si c'est le cas, merci de nous le signaler.

### Installation à partir des sources

Clonez le code du module à partir de notre dépôt Github (note : le répertoire du module DOIT être lowcostexpress) :

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

### Setup carrier options

Go to System -> Configuration, then Sales -> Shipping Methods. The 'MyFlyingBox' shipping method should be listed, containing a form.

The following settings can be fine-tuned on the carrier configuration page:
* MyFlyingBox API ID and password (corresponding to the environment you will use)
* MyFlyingBox environment to use (staging or production)
* Default shipper information, automatically loaded when creating a shipment
* Default parcel content type and country of origin, automatically loaded when creating a shipment
* Calculation rules for automatic shipping cost evaluation during cart check-out (surcharge, rounding)

### Setup dimensions table

Go to Sales -> MyFlyingBox -> Dimensions setup

The module cannot guess the final packing dimensions for a given cart, so it will only base its calculation on the total weight of the cart and the correspondance rules you define on the dimension's table.
The principle is easy: for a given total weight (up to), provide the minimum packaging dimensions expected.

It is very important that you take some time to properly configure these options, as they will determine the volume used to get an estimation of the shipping costs for the cart of the customer. International shipments use volumetric weight, so if you do not properly configure this table, you may get major differences between what the customer paid for shipment and what you will actually have to pay to order the corresponding real shipment.

### Setup carrier's list

Go to Sales -> MyFlyingBox -> Services setup

Initialize the list of available services by clicking on "Refresh services from API". This will automatically create all available services. You can configure and activate each service separately.

Available options allow you to:
- define the name of the shipping method, as displayed to the customer
- activate destination restrictions
- activate flatrate pricing (NOT FUNCTIONAL YET!!)

## Utilisation

### Fonctionnalités front-office (perspective client)

#### Frais de livraison

Lorsqu'un client procède à la commande de son panier, des options de transports seront proposées dynamiquement basé sur les offres récupérées depuis l'API MyFlyingBox et les règles de calcul que vous aurez spécifié sur la page de configuration du module.

Le client peut ensuite sélectionner l'une de ces offres, et sera facturé le montant calculé.

#### Suivi

PAS ENCORE FONCTIONNEL

### Back-office

Sur la page de gestion des commandes (en back-office), un nouvel onglet 'MyFlyingBox shipments' est affichée. A partir de ce cadre, vous pouvez initier une nouvelle expédition.

Les nouvelles expéditions chargent automatiquement toutes les données nécsesaires (expéditeur à partir de votre configuration transporteur, destinataire à partir de la commande, et colis à partir du poids du panier et la correspondance avec les dimensions).

Lorsque vous avez saisi tous les détails, choisissez et commandez un service de transport (après avoir sélectionné, le cas échéant, une date d'enlèvement ou un point relais de livraison).
Notez que toutes les offres MyFlyingBox disponibles sont présentées en back-office, sans prise en compte de la configuration des services dans Magento, qui n'est utilisée que pour contrôler la manière dont les services sont présentés au client lors de la commande.
Le service choisi par le client est en principe sélectionné par défaut.

## Support

Ce module est maintenu directement par les développeurs de l'API MyFlyingBox. Vous pouvez nous contacter à l'adresse tech@myflyingbox.com si vous avez besoin d'aide pour l'utilisation, le paramétrage ou l'installation du module sur votre instance Magento. Si vous n'avez pas encore de compte MyFlyingBox ou de clés d'API, envoyez un message à info@myflyingbox.com et vous serez recontacté par notre équipe commerciale.
