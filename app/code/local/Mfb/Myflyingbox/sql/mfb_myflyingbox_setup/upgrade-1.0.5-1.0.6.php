<?php

$installer = $this;
$installer->startSetup();

$connection = $installer->getConnection();
$table      = $installer->getTable('mfb_myflyingbox/offer');

$connection->addColumn(
              $table,
              'insurance_price_in_cents',
              array(
                  'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
                  'nullable'  => false,
                  'comment' => 'insurance price (cents)'
              )
          );
$connection->addColumn(
              $table,
              'insurable',
              array(
                  'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                  'nullable'  => false,
                  'comment' => 'insurable ?'
              )
          );

$table      = $installer->getTable('mfb_myflyingbox/parcel');
$connection->addColumn(
        $table,
        'insurable_value',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'nullable'  => false,
            'unsigned'  => true,
            'comment' => 'Insurabled value (in cents)'
        )
    );


$table      = $installer->getTable('mfb_myflyingbox/service');
$connection->addColumn(
        $table,
        'insurance',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
            'nullable'  => false,
            'comment' => 'Insurance'
        )
    );

$connection->addColumn(
        $table,
        'insurance_minimum_amount',
        array(
          'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
          'scale' => 4,
          'precision' => 12,
          'nullable'  => false,
          'comment' => 'Insurance minimum amount'
        )
    );


$installer->endSetup();
?>
