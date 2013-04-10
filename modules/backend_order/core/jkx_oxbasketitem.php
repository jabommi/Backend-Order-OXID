<?php
class jkx_oxBasketitem extends jkx_oxBasketitem_parent{


     /**
     * Initializes basket item from oxorderarticle object
     *  - oxbasketitem::_setFromOrderArticle() - assigns $oOrderArticle parameter
     *  to oxBasketItem::_oArticle. Thus oxOrderArticle is used as oxArticle (calls
     *  standard methods implemented by oxIArticle interface);
     *  - oxbasketitem::setAmount();
     *  - oxbasketitem::_setSelectList();
     *  - oxbasketitem::setPersParams().
     *  - oxbasketitem::setPrice().
     *
     * @param oxorderarticle $oOrderArticle order article to load info from
     *
     * @return null
     */
    public function initFromOrderArticle( $oOrderArticle)
    {
        parent::initFromOrderArticle( $oOrderArticle );

        if($this->getConfig()->isAdmin()){
            $this->setPrice($oOrderArticle->getPrice());
        }
    }




}
