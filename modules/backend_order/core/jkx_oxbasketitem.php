<?php

/**
 * Class jkx_oxBasketitem
 */
class jkx_oxBasketitem extends jkx_oxBasketitem_parent
{
    /**
     * Initializes basket item from oxorderarticle object
     *  - oxbasketitem::_setFromOrderArticle() - assigns $oOrderArticle parameter
     *  to oxBasketItem::_oArticle. Thus oxOrderArticle is used as oxArticle (calls
     *  standard methods implemented by oxIArticle interface);
     *  - oxbasketitem::setAmount();
     *  - oxbasketitem::_setSelectList();
     *  - oxbasketitem::setPersParams().
     *
     * @param oxorderarticle $oOrderArticle order article to load info from
     */
    public function initFromOrderArticle($oOrderArticle)
    {
        parent::initFromOrderArticle($oOrderArticle);

        /*** START MOD BACKEND ORDER RECALCULATE ***/
        if ($this->getConfig()->isAdmin()) {
            $this->setPrice($oOrderArticle->getPrice());
        }
        /*** END MOD BACKEND ORDER RECALCULATE ***/
    }

}
