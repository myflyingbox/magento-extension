MyFlyingBox Magento Module
==========================

(cette documentation est également disponible [en français](README-fr.md))

A Magento module that provides an interface to the MyFlyingBox web services (cf. https://www.myflyingbox.com), provided by MYFLYINGBOX (company registered in France).

IMPORTANT: this is a beta version, not to be used in production yet unless in close contact with the dev team.

Be sure to test extensively on your staging environment before going live.

There are still some rough edges in term of interface and error management. We are working on it. Do not hesitate to contact us to check our progress and suggest improvements.

## Presentation

This module provides two distinct set of features :
- order shipments through the MyFlyingBox API via a dedicated back-office interface (one by one, or through mass actions)
- automatically calculate transportation costs when a customer check out its cart


## Installation

To use this module, you need the following:
- a recent Magento instance up and running (1.7 to 1.9)
- php-curl module activated on the server (used for API calls to the MyFlyingBox webservice)
- an active MyFlyingBox account and API keys

The module was developed and tested on Magento branches 1.7 and 1.9 and therefore may not be compatible with older releases. Be sure to test the module extensively on your instance, as there may be compatibility issues with other modules.

Database install/upgrade scripts have only been tested on MySQL, but should be compatible with other SQL database servers as the scripts are using Magento's DDL. Please contact us if you encounter any issue.

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
The principle is easy: for a given total weight (up to), provide the packaging dimensions expected.

It is very important that you take some time to properly configure these options, as they will determine the volume used to get an estimation of the shipping costs for the cart of the customer. International shipments use volumetric weight, so if you do not properly configure this table, you may get major differences between what the customer paid for shipment and what you will actually have to pay to order the corresponding real shipment. Note that MyFlyingBox will always invoice you based on the real weight reported by the carrier.

If your catalog is complex and you cannot determine a reliable weight/dimension correspondence, please use a flat-rate pricing strategy instead (see below).

### Setup carrier's list

Go to Sales -> MyFlyingBox -> Services setup

Initialize the list of available services by clicking on "Refresh services from API". This will automatically extract all available services. You can configure and activate each service separately.

Below is a description of all available options.

#### Insurance strategy

Ad-valorem insurance is available for most services. Two options allow you to define the default behaviour:
- use ad-valorem insurance by default
- minimum insurable amount: if the insurable value (= total cart value) is below this value, insurance will not be selected by default

This option is used in the following processes:
- calculating shipping costs presented during checkout (unless you use flat-rate pricing)
- shipping through mass action
- pre-check insurance checkbox when manually creating/modifying a MyFlyingBox shipment for a given order


#### Customer-facing data

For each method you can define the carrier name and service name that will be displayed on customer facing views (shipping information on email notification and order view).

It is also highly advised to setup the Tracking URL. Please contact us to know what tracking URL to use for each MyFlyingBox service (in the future this will be totally automated, but for now you must set it manually for each service).
If you do not set a tracking URL, the customer will not have access to any tracking information.

#### Flat-rate pricing

If you activate flat-rate pricing, the shipping costs presented to the customer will be based on a simple weight/price correspondance table. The prices returned by the MyFlyingBox webservice will be ignored.

This option only affects the checkout process. Prices in the back-office are always based on API calls, without any sort of adjustment.

#### Destination restriction

By default, the MyFlyingBox API only returns offers for services that are available for a given route.
If you want to have tighter restrictions, you can use inclusion and exclusion rules to limit the service per country or postcodes.

Both inclusion and exclusion rules are evaluated, when filled. So for instance setting 'FR' as only inclusion rule will automatically limit this service to France, and setting 'FR|75' in the exclusion field will exclude Paris.

These settings have no effect in the back-office. They only limit the services proposed to the customer during checkout.

#### Activation

Remember to active the service (last field) so that it is present to the customer.

This setting has no effect in the back-office. They only limit the services proposed to the customer during checkout.

## Usage

### Front-office features (customer perspective)

#### Delivery costs

When a customer proceeds to cart checkout, transportation offers will be dynamically proposed based on MyFlyingBox API calls and/or the settings you have specificed on the carrier configuration page and service setup.

The customer can then select these offers, and will be invoiced the calculated amount.

#### Tracking

When a MyFlyingBox shipping is booked in back-office, it triggers the standard shipment process of Magento, registering a shipment and a tracking code for each parcel, if you have set the tracking URL in the service setup.
Thus the customer can open the tracking link from the email notification or web interface.

### Back-office

#### Manual shipment, one by one

On the back-office page of an order, a button 'Ship with MyFlyingBox' allows you to initiate a MyFlyingBox shipment.

New shipments automatically load all required data (shipper information based on configuration, receiver information based on order details, parcel based on total weight of cart and corresponding default dimensions [see above weight/dimension table setup]).
Make sure you update the shipment details before booking the shipment if anything needs to be adjusted, especially the parcel details.

When all details are set, select and book a service (after selecting a pickup date or relay delivery location, if applicable, as well as insurance option).

Please note that all available MyFlyingBox services are presented, regardless of what services you have activated for use during the checkout process. The service selected by the customer should be automatically selected by default, as well as relay delivery location if applicable.

#### Automatic shipment (mass action)

Instead of proceeding one by one, you can automatically book multiple shipments at once. Just select the orders you want to ship, and in the action menu select "Myflyingbox: ship orders". Like during the manual process, all data will be automatically filled based on available information and configuration, but instead of letting the shipment in draft state it is immediately booked, for each order selected.

Please note that you cannot review the shipment data when using mass actions, so be very careful when using this feature.

Also note that mass action is for now limited to orders with only one item, to avoid any major error. This limitation will be removed later if/when we add some options to customize these rules.

Mass label printing is not yet available, but will be shortly.

## Getting help

This module is maintained directly by the developers of the MyFlyingBox API. You can contact us at tech@myflyingbox.com if you need any help using or setting up the module on your Magento instance.
