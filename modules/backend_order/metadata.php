<?php

/**
* Module information
*/
$aModule = array(
    'id' => 'backend_order',
    'title' => 'Backend Order',
    'description' => 'Deactivate Recalculating price, discount, shipping, payment in Backend Order Managment.',
    'version' => '0.6',
    'author' => 'Jason KX',


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
    )
);

