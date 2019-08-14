<?php

class Ds_Similarorder_Model_Similarorder extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('similarorder/similarorder');
    }
}