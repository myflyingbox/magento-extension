<?php
/**
 * Mfb_Myflyingbox extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Mfb
 * @package        Mfb_Myflyingbox
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Myflyingbox module install script
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('mfb_myflyingbox/dimension'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Dimension ID'
    )
    ->addColumn(
        'order_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Order ID'
    )
    ->addColumn(
        'weight_from',
        Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4',
        array(
            'nullable'  => false,
        ),
        'Weight (from)'
    )
    ->addColumn(
        'weight_to',
        Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4',
        array(
            'nullable'  => false,
        ),
        'Weight (up to)'
    )
    ->addColumn(
        'length',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Length (cm)'
    )
    ->addColumn(
        'width',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Width (cm)'
    )
    ->addColumn(
        'height',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Height (cm)'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Dimension Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Dimension Creation Time'
    ) 
    ->setComment('Dimension Table');
$this->getConnection()->createTable($table);


$table = $this->getConnection()
    ->newTable($this->getTable('mfb_myflyingbox/shipment'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Shipment ID'
    )
    ->addColumn(
        'order_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Order ID'
    )
    ->addColumn(
        'api_quote_uuid',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'API Quote ID'
    )
    ->addColumn(
        'api_offer_uuid',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'API offer UUID'
    )
    ->addColumn(
        'api_order_uuid',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'API order uuid'
    )
    ->addColumn(
        'collection_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Pickup date'
    )
    ->addColumn(
        'relay_delivery_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Relay delivery code'
    )
    ->addColumn(
        'relay_delivery_address',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Relay delivery address'
    )
    ->addColumn(
        'shipper_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Shipper name'
    )
    ->addColumn(
        'shipper_company',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Shipper company'
    )
    ->addColumn(
        'shipper_street',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(
            'nullable'  => false,
        ),
        'Shipper address'
    )
    ->addColumn(
        'shipper_city',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Shipper city'
    )
    ->addColumn(
        'shipper_state',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Shipper state'
    )
    ->addColumn(
        'shipper_postal_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Shipper postal code'
    )
    ->addColumn(
        'shipper_country',
        Varien_Db_Ddl_Table::TYPE_TEXT, 2,
        array(
            'nullable'  => false,
        ),
        'Shipper country'
    )
    ->addColumn(
        'shipper_phone',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Shipper phone'
    )
    ->addColumn(
        'shipper_email',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Shipper email'
    )
    ->addColumn(
        'recipient_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Receiver name'
    )
    ->addColumn(
        'recipient_company',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Receiver company'
    )
    ->addColumn(
        'recipient_street',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(
            'nullable'  => false,
        ),
        'Receiver address'
    )
    ->addColumn(
        'recipient_city',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Receiver city'
    )
    ->addColumn(
        'recipient_state',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Receiver state'
    )
    ->addColumn(
        'recipient_postal_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Receiver postal code'
    )
    ->addColumn(
        'recipient_country',
        Varien_Db_Ddl_Table::TYPE_TEXT, 2,
        array(
            'nullable'  => false,
        ),
        'Receiver country'
    )
    ->addColumn(
        'recipient_phone',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Receiver phone'
    )
    ->addColumn(
        'recipient_email',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Receiver email'
    )
    ->addColumn(
        'booked_at',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Date of booking'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Shipment Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Shipment Creation Time'
    ) 
    ->setComment('Shipment Table');

$this->getConnection()->createTable($table);

$table = $this->getConnection()
    ->newTable($this->getTable('mfb_myflyingbox/quote'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Quote ID'
    )
    ->addColumn(
        'shipment_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Shipment ID'
    )
    ->addColumn(
        'api_quote_uuid',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'API quote UUID'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Quote Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Quote Creation Time'
    ) 
    ->addIndex($this->getIdxName('mfb_myflyingbox/shipment', array('shipment_id')), array('shipment_id'))
    ->setComment('Quote Table');
$this->getConnection()->createTable($table);


$table = $this->getConnection()
    ->newTable($this->getTable('mfb_myflyingbox/offer'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Offer ID'
    )
    ->addColumn(
        'quote_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Quote ID'
    )
    ->addColumn(
        'api_offer_uuid',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'API offer uuid'
    )
    ->addColumn(
        'mfb_product_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'MFB product code'
    )
    ->addColumn(
        'mfb_product_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'MFB product name'
    )
    ->addColumn(
        'pickup',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
            'nullable'  => false,
        ),
        'Pickup supported'
    )
    ->addColumn(
        'relay',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
            'nullable'  => false,
        ),
        'Relay delivery supported'
    )
    ->addColumn(
        'base_price_in_cents',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
        ),
        'Base price (in cents)'
    )
    ->addColumn(
        'total_price_in_cents',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
        ),
        'Total price (cents)'
    )
    ->addColumn(
        'currency',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Price curency'
    )
    ->addColumn(
        'collection_dates',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(
            'nullable'  => false,
        ),
        'Possible collection dates (serialized)'
    )
    ->addColumn(
        'delivery_locations',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(
            'nullable'  => false,
        ),
        'Delivery locations (serialized)'
    )
    
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Offer Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Offer Creation Time'
    ) 
    ->addIndex($this->getIdxName('mfb_myflyingbox/quote', array('quote_id')), array('quote_id'))
    ->setComment('Offer Table');
$this->getConnection()->createTable($table);


$table = $this->getConnection()
    ->newTable($this->getTable('mfb_myflyingbox/parcel'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Parcel ID'
    )
    ->addColumn(
        'shipment_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Shipment ID'
    )
    ->addColumn(
        'length',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Length (cm)'
    )
    ->addColumn(
        'width',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Width (cm)'
    )
    ->addColumn(
        'height',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Height (cm)'
    )
    ->addColumn(
        'weight',
        Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4',
        array(
            'nullable'  => false,
        ),
        'Weigh (kg)'
    )
    ->addColumn(
        'shipper_reference',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Shipper reference'
    )
    ->addColumn(
        'recipient_reference',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Receiver reference'
    )
    ->addColumn(
        'customer_reference',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Customer reference'
    )
    ->addColumn(
        'value',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Declared value (in cents)'
    )
    ->addColumn(
        'insurable_value',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Insurabled value (in cents)'
    )
    ->addColumn(
        'currency',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
        ),
        'Value currency'
    )
    ->addColumn(
        'description',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Content description'
    )
    ->addColumn(
        'country_of_origin',
        Varien_Db_Ddl_Table::TYPE_TEXT, 2,
        array(
            'nullable'  => false,
        ),
        'Country of origin'
    )
    ->addColumn(
        'tracking_number',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Tracking number'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Parcel Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Parcel Creation Time'
    ) 
    ->addIndex($this->getIdxName('mfb_myflyingbox/shipment', array('shipment_id')), array('shipment_id'))
    ->setComment('Parcel Table');
$this->getConnection()->createTable($table);


$table = $this->getConnection()
    ->newTable($this->getTable('mfb_myflyingbox/service'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Service ID'
    )
    ->addColumn(
        'code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Code'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'carrier_display_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Carrier display name (shown to customer)'
    )
    ->addColumn(
        'display_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Shipping method display name (shown to customer)'
    )
    ->addColumn(
        'pickup',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
            'nullable'  => false,
        ),
        'Pickup'
    )
    ->addColumn(
        'relay',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
            'nullable'  => false,
        ),
        'Relay'
    )
    ->addColumn(
        'flatrate_pricing',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
            'nullable'  => false,
        ),
        'Flatrate pricing'
    )
    ->addColumn(
        'flatrate_pricelist',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'flatrate_pricelist'
    )
    ->addColumn(
        'included_postcodes',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Limit by country/postcode'
    )
    ->addColumn(
        'excluded_postcodes',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Excluded countries/postcodes'
    )
    ->addColumn(
        'description',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Service description'
    )
    ->addColumn(
        'tracking_url',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Tracking URL'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Service Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Service Creation Time'
    )
    ->addColumn(
        'insurance',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
            'nullable'  => false,
        ),
        'Insurance'
    )
    ->addColumn(
        'insurance_minimum_amount',
        Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'  => false,
    ),
        "Insurance minimum amount"
    )
    ->setComment('Service Table');
$this->getConnection()->createTable($table);
$this->endSetup();
