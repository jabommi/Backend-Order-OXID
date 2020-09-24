<?php

/**
* Module information
*/
$aModule = array(
    'id' => 'backend_order',
    'title' => 'Backend Order Recalculation',
    'description' => 'Deactivate Recalculating article price, discount, voucher, shipping, payment, wrapping, ts protection costs in backend order managment.',
    'version' => '2.0.0',
    'author' => 'Jason KX / Tobias Merkl',
    'email' => '',
    'url' => 'https://github.com/proudcommerce/Backend-Order-OXID',

    'extend' => array(
        'oxbasket' => 'backend_order/core/jkx_oxbasket',
        'oxbasketitem' => 'backend_order/core/jkx_oxbasketitem',
        'oxorder' => 'backend_order/core/jkx_oxorder'
    ),

    'settings' => array(
        array('group' => 'order', 'name' => 'jkxRecalculateOrderArticlePrice', 'type' => 'bool', 'value' => 'false'),
        array('group' => 'order', 'name' => 'jkxRecalculateOrderDiscount',    'type' => 'bool', 'value' => 'false'),
        array('group' => 'order', 'name' => 'jkxRecalculateOrderVoucher',    'type' => 'bool', 'value' => 'false'),
        array('group' => 'order', 'name' => 'jkxRecalculateOrderDelivery',    'type' => 'bool', 'value' => 'false'),
        array('group' => 'order', 'name' => 'jkxRecalculateOrderPayment',    'type' => 'bool', 'value' => 'false'),
        array('group' => 'order', 'name' => 'jkxRecalculateOrderTSProtection',    'type' => 'bool', 'value' => 'false'),
        array('group' => 'order', 'name' => 'jkxRecalculateOrderWrapping',    'type' => 'bool', 'value' => 'false')
    )
);

