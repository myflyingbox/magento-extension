MyFlyingBox Magento Module
==========================

(cette documentation est également disponible [en français](README-fr.md))

A Magento module that provides an interface to the MyFlyingBox web services (cf. https://www.myflyingbox.com), provided by MYFLYINGBOX (company registered in France).

IMPORTANT: this is a beta version, not to be used in production yet unless in close contact with the dev team.

Be sure to test extensively on your staging environment before going live.

## MISSING FEATURES

The following features are still missing and will be added as soon as possible:
- relay delivery location selection during checkout
- tracking information (both in back-office and from customer perspective)
- flatrate pricing (to ignore API rates and use a static table based on weight instead)

There are still some rough edges in term of interface and error management. We are working on it. Do not hesitate to contact us to check our progress and suggest improvements.

## Presentation

This module provides two distinct set of features :
- order shipments through the MyFlyingBox API via a dedicated back-office interface
- automatically calculate transportation costs when a customer check out its cart


## Installation

To use this module, you need the following:
- a recent Magento instance up and running
- php-curl module activated on the server
- an active MyFlyingBox account and API keys

The module was developed and tested on Magento 1.9.2.0 and therefore may not be compatible with older releases. If this is the case and you cannot use the module, please let us know and we will try to improve compatibility.

### Install from source

Get the code by cloning the github repository to a temporary directory:

```bash
git clone --recursive https://github.com/myflyingbox/magento-extension.git myflyingbox
```

One of the module libs (php-lce) has dependencies of its own, which you must initialize with 'composer':

```bash
cd myflyingbox/lib/php-lce
curl -s http://getcomposer.org/installer | php
php composer.phar install
```

Finally, copy the content of the module to your Magento root folder:
```bash
cp -R myflyingbox/* /path/to/magento/
```

### Install from package

Go to the [list of releases](https://github.com/myflyingbox/magento-extension/releases) and download the latest package.

Extract the archive inside the root directory of your Magento instance.

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

## Usage

### Front-office features (customer perspective)

#### Delivery costs

When a customer proceeds to cart checkout, transportation offers will be dynamically proposed based on MyFlyingBox API calls and the settings you have specificed on the carrier configuration page and service setup.

The customer can then select these offers, and will be invoiced the calculated amount.

#### Tracking

NOT IMPLEMENTED YET

### Back-office

On the back-office page of an order, a new tab 'MyFlyingBox shipments' will be accessible. From there, you can initiate a new shipment.

New shipments automatically load all required data (shipper information based on configuration, receiver information based on order details, parcel based on total weight of cart and corresponding default dimensions).
Make sure you update the shipment details before booking the shipment.

When all details are set, select and book a service (after selecting a pickup date or relay delivery location, if applicable).
Please note that all available MyFlyingBox services are presented, regardless of what services you have activated for use during the checkout process. The service selected by the customer should be automatically selected by default, if applicable.

## Getting help

This module is maintained directly by the developers of the MyFlyingBox API. You can contact us at tech@myflyingbox.com if you need any help using or setting up the module on your Magento instance.
