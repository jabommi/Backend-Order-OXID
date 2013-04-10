<?php


class jkx_oxBasket extends jkx_oxBasket_parent{



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


                /***START MOD BACKEND ORDER Recalculate Article Price ***/
               
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
                
                /***END MOD BACKEND ORDER Recalculate Article Price ***/

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
 
