<?php
class Ds_Similarorder_Block_Adminhtml_Orderform extends Mage_Adminhtml_Block_Widget
{
  public function __construct()
  {
    parent::__construct();
	 $this->setTemplate('similarorder/orderfilter.phtml'); 
  }
}