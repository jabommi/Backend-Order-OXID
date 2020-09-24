<?php

/**
 * Class jkx_oxOrder
 */
class jkx_oxOrder extends jkx_oxOrder_parent
{

    /**
     * Recalculates order. Starts transactions, deletes current order and order articles from DB,
     * adds current order articles to virtual basket and finally recalculates order by calling oxorder::finalizeOrder()
     * If no errors, finishing transaction.
     *
     * @param array $aNewArticles article list of new order
     *
     * @throws Exception
     */
    public function recalculateOrder($aNewArticles = [])
    {
        oxDb::getDb()->startTransaction();

        try {
            $oBasket = $this->_getOrderBasket();

            // add this order articles to virtual basket and recalculates basket
            $this->_addOrderArticlesToBasket($oBasket, $this->getOrderArticles(true));

            // adding new articles to existing order
            $this->_addArticlesToBasket($oBasket, $aNewArticles);

            // recalculating basket
            $oBasket->calculateBasket(true);

            /*** START MOD BACKEND ORDER RECALCULATE ***/
            if ($this->getConfig()->isAdmin()) {
                if ($this->getConfig()->getConfigParam('jkxRecalculateOrderVoucher')
                    || $this->getConfig()->getConfigParam('jkxRecalculateOrderDiscount')) {

                    $this->reloadDiscount(true);
                } else {
                    $this->reloadDiscount(false);
                }
            }
            /*** END MOD BACKEND ORDER RECALCULATE ***/

            //finalizing order (skipping payment execution, vouchers marking and mail sending)
            $iRet = $this->finalizeOrder($oBasket, $this->getOrderUser(), true);

            //if finalizing order failed, rollback transaction
            if ($iRet !== 1) {
                oxDb::getDb()->rollbackTransaction();
            } else {
                oxDb::getDb()->commitTransaction();
            }

        } catch (Exception $oE) {
            // if exception, rollBack everything
            oxDb::getDb()->rollbackTransaction();

            if (defined('OXID_PHP_UNIT')) {
                throw $oE;
            }
        }
    }

}
