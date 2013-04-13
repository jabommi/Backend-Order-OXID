<?php


class jkx_oxBasket extends jkx_oxBasket_parent{

    /**
     * Order of this Basket
     * @var oxOrder
     */
    protected $_oOrder = null;

    
    /**
     * Executes all needed functions to calculate basket price and other needed
     * info. You can specify recalculating or not article price, discounts, vouchers, payment cost, delivery costs, wrapping costs, ts costs
     * in Backend.
     *
     * @param bool $blForceUpdate set this parameter to TRUE to force basket recalculation
     *
     * @return null
     */
    public function calculateBasket( $blForceUpdate = false )
    {
        if(!$this->getConfig()->isAdmin()){

            //Use oxid standard function for Frontend
            parent::calculateBasket( $blForceUpdate);
            
        }else{


        /*
        //would be good to perform the reset of previous calculation
        //at least you can use it for the debug
        $this->_aDiscounts = array();
        $this->_aItemDiscounts = array();
        $this->_oTotalDiscount = null;
        $this->_dDiscountedProductNettoPrice = 0;
        $this->_aDiscountedVats = array();
        $this->_oPrice = null;
        $this->_oNotDiscountedProductsPriceList = null;
        $this->_oProductsPriceList = null;
        $this->_oDiscountProductsPriceList = null;*/


        if ( !$this->isEnabled() ) {
            return;
        }

        if ( !$this->_blUpdateNeeded && !$blForceUpdate ) {
            return;
        }

        $this->_aCosts = array();

        $this->_oPrice = oxNew( 'oxprice' );
        $this->_oPrice->setBruttoPriceMode();

        //  1. saving basket to the database
        $this->_save();

        //  2. remove all bundles
        $this->_clearBundles();

        //  3. generate bundle items
        $this->_addBundles();

        // reserve active basket
        if ($this->getConfig()->getConfigParam( 'blPsBasketReservationEnabled' )) {
            $this->getSession()->getBasketReservations()->reserveBasket($this);
        }

        //  4. calculating item prices
        $this->_calcItemsPrice();

        /***START MOD BACKEND ORDER Recalculate Order ***/

        //Create oxOrder Object from origin order stored in db
        $soxId = $this->getOrderId();
        if($soxId != null && $soxId != '-1'){
            $this->_oOrder = oxNew( 'oxorder');
            $this->_oOrder->load($soxId);
        }

        //If discount recalculation is active or something has gone wrong with oxOrder Object, recalculate
        if ($this->_oOrder== null || $this->getConfig()->getConfigParam( 'jbRecalculateOrderDiscount' )) {
            //  5. calculating/applying discounts
            $this->_calcBasketDiscount();
            //  6. calculating basket total discount
            $this->_calcBasketTotalDiscount();
        }else{

            //Get basket total Discount from origin order stored in db
            $dTotalDiscount = $this->_oOrder->getFieldData('OXDISCOUNT');
            $this->_oTotalDiscount = oxNew( 'oxprice');
            $this->_oTotalDiscount->setBruttoPriceMode();
            $this->_oTotalDiscount->add($dTotalDiscount);
            if($dTotalDiscount != null && $dTotalDiscount != 0){
                $this->_aDiscounts[0] = oxNew( 'oxdiscount');
                $this->_aDiscounts[0]->dDiscount = $dTotalDiscount;
            }
        }

        //If voucher recalculation is active or something has gone wrong with oxOrder Object, recalculate
        if ($this->_oOrder== null || $this->getConfig()->getConfigParam( 'jbRecalculateOrderVoucher' )) {
            //  7. check for vouchers
            $this->_calcVoucherDiscount();
        }else{
            //Get Voucher Discount from origin order stored in db
            $dVoucherDiscount = $this->_oOrder->getFieldData('OXVOUCHERDISCOUNT');
            $this->_oVoucherDiscount = oxNew( 'oxPrice' );
            $this->_oVoucherDiscount->setBruttoPriceMode();
            $this->_oVoucherDiscount->add($dVoucherDiscount);
        }



        //  8. applies all discounts to pricelist
        $this->_applyDiscounts();


        //  9. calculating additional costs:
        //  9.1: delivery
        //If delivery costs recalculation is active or something has gone wrong with oxOrder Object, recalculate
        if ($this->_oOrder== null || $this->getConfig()->getConfigParam( 'jbRecalculateOrderDelivery' )) {
            $this->setCost( 'oxdelivery', $this->_calcDeliveryCost() );
        }else{
            //Get Delivery Costs from origin order stored in db
            $dDeliveryCosts = $this->_oOrder->getFieldData('OXDELCOST');
            $oDCosts = oxNew( 'oxPrice' );
            $oDCosts->setBruttoPriceMode();
            $oDCosts->setVAT($this->_oOrder->getFieldData('OXDELVAT'));
            $oDCosts->add($dDeliveryCosts);
            $this->setCost( 'oxdelivery', $oDCosts );
        }



        //  9.2: adding wrapping costs
        if ($this->_oOrder== null || $this->getConfig()->getConfigParam( 'jbRecalculateOrderWrapping' )) {
            $this->setCost( 'oxwrapping', $this->_calcBasketWrapping() );
        }else{
            //Get Wrapping Costs from origin order stored in db
            $dWrapCosts = $this->_oOrder->getFieldData('OXWRAPCOST');
            $oWCosts = oxNew( 'oxPrice' );
            $oWCosts->setBruttoPriceMode();
            $oWCosts->setVAT($this->_oOrder->getFieldData('OXWRAPVAT'));
            $oWCosts->add($dWrapCosts);
            $this->setCost( 'oxwrapping', $oWCosts );
        }



        //  9.3: adding payment cost
        if ($this->_oOrder== null || $this->getConfig()->getConfigParam( 'jbRecalculateOrderPayment' )) {
            $this->setCost( 'oxpayment', $this->_calcPaymentCost() );
        }else{
             //Get Payment Costs from origin order stored in db
             $dPaymentCosts = $this->_oOrder->getFieldData('OXPAYCOST');
             $oPCosts = oxNew( 'oxPrice' );
             $oPCosts->setBruttoPriceMode();
             $oPCosts->setVAT($this->_oOrder->getFieldData('OXPAYVAT'));
             $oPCosts->add($dPaymentCosts);
             $this->setCost( 'oxpayment', $oPCosts );
        }



        //  9.4: adding TS protection cost
        if ($this->_oOrder== null || $this->getConfig()->getConfigParam( 'jbRecalculateOrderTSProtection' )) {
            $this->setCost( 'oxtsprotection', $this->_calcTsProtectionCost() );
        }else{
             //Get TS Protection Costs from origin order stored in db
             $dTSProtectionCosts = $this->_oOrder->getFieldData('OXTSPROTECTCOSTS');
             $oTSCosts = oxNew( 'oxPrice' );
             $oTSCosts->setBruttoPriceMode();
             $oTSCosts->add($dTSProtectionCosts);
             $this->setCost( 'oxtsprotection', $oTSCosts );
        }


        /***END MOD BACKEND ORDER Recalculate Order ***/

        //  10. calculate total price
        $this->_calcTotalPrice();

        //  11. setting deprecated values
        $this->_setDeprecatedValues();

        //  12.setting to up-to-date status
        $this->afterUpdate();

        }

        
    }



    /**
     * Iterates through basket items and calculates its prices and discounts
     *
     * @return null
     */
    protected function _calcItemsPrice()
    {
        // resetting
        $this->setSkipDiscounts( false );
        $this->_iProductsCnt = 0; // count different types
        $this->_dItemsCnt    = 0; // count of item units
        $this->_dWeight      = 0; // basket weight

        // resetting
        $this->_aItemDiscounts = array();

        $this->_oProductsPriceList = oxNew( 'oxpricelist' );
        $this->_oDiscountProductsPriceList = oxNew( 'oxpricelist' );
        $this->_oNotDiscountedProductsPriceList = oxNew( 'oxpricelist' );

        $oDiscountList = oxDiscountList::getInstance();

        foreach ( $this->_aBasketContents as $oBasketItem ) {


            $this->_iProductsCnt++;
            $this->_dItemsCnt += $oBasketItem->getAmount();
            $this->_dWeight   += $oBasketItem->getWeight();

            if ( !$oBasketItem->isDiscountArticle() && ( $oArticle = $oBasketItem->getArticle() ) ) {


                /***START MOD BACKEND ORDER Recalculate ***/
                if($this->getConfig()->isAdmin()){
                    $tempBasketItem = clone $oBasketItem;
                    $tempBasketItem->setAmount(1);

                    if($tempBasketItem->getPrice() == null || $this->getConfig()->getConfigParam( 'jbRecalculateOrderArticlePrice' )){
                        $oBasketPrice = $oArticle->getBasketPrice( $oBasketItem->getAmount(), $oBasketItem->getSelList(), $this );
                    }else{
                        $tempBasketItem->getPrice()->divide($oBasketItem->getAmount());
                        $oBasketPrice = $tempBasketItem->getPrice();
                    }
                }else{
                    $oBasketPrice = $oArticle->getBasketPrice( $oBasketItem->getAmount(), $oBasketItem->getSelList(), $this );
                }

                $oBasketItem->setPrice( $oBasketPrice );
                /***END MOD BACKEND ORDER Recalculate  ***/

                //P adding product price
                $this->_oProductsPriceList->addToPriceList( $oBasketItem->getPrice() );

                $oBasketPrice->setBruttoPriceMode();
                if ( !$oArticle->skipDiscounts() && $this->canCalcDiscounts() ) {
                    // apply basket type discounts
                    //#3857 added clone in order not to influence the price
                    $aItemDiscounts = $oDiscountList->applyBasketDiscounts( clone $oBasketPrice, $oDiscountList->getBasketItemDiscounts( $oArticle, $this, $this->getBasketUser() ), $oBasketItem->getAmount() );
                    if ( is_array($this->_aItemDiscounts) && is_array($aItemDiscounts) ) {
                        $this->_aItemDiscounts = $this->_mergeDiscounts( $this->_aItemDiscounts, $aItemDiscounts);
                    }
                } else {
                    $oBasketItem->setSkipDiscounts( true );
                    $this->setSkipDiscounts( true );
                }
                $oBasketPrice->multiply( $oBasketItem->getAmount() );

                //P collect discount values for basket items which are discountable
                if ( !$oArticle->skipDiscounts() ) {
                    $this->_oDiscountProductsPriceList->addToPriceList( $oBasketPrice );
                } else {
                    $this->_oNotDiscountedProductsPriceList->addToPriceList( $oBasketPrice );
                    $oBasketItem->setSkipDiscounts( true );
                    $this->setSkipDiscounts( true );
                }
            } elseif ( $oBasketItem->isBundle() ) {
                // if bundles price is set to zero
                $oPrice = oxNew( "oxprice");
                $oBasketItem->setPrice( $oPrice );
            }
        }
    }
}
 
