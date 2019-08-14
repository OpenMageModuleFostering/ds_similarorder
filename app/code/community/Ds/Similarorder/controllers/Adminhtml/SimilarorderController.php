<?php

class Ds_Similarorder_Adminhtml_SimilarorderController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('similarorder/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}
	
	public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

	public function orderfilterAction()
    {
       $this->_title($this->__('Similar Order'))
             ->_title($this->__('Manage Items'));

        $this->_title($this->__('Order Filter'));

        $this->loadLayout()
            //->_setActiveMenu('ordergrid/items')
            ->_addContent($this->getLayout()->createBlock('similarorder/adminhtml_orderfilter'))
            ->renderLayout();
    }
	
	public function filterbyorderAction()
    {
		
       $this->_title($this->__('Similar Order'))
             ->_title($this->__('Manage Items'));

        $this->_title($this->__('Order Filter'));

        $this->loadLayout()
            //->_setActiveMenu('ordergrid/items')
            ->_addContent($this->getLayout()->createBlock('similarorder/adminhtml_orderfilter'))
            ->renderLayout();

    }
}