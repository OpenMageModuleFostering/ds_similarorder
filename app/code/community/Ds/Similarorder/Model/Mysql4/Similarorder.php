<?php

class Ds_Similarorder_Model_Mysql4_Similarorder extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the similarorder_id refers to the key field in your database table.
        $this->_init('similarorder/similarorder', 'similarorder_id');
    }
}