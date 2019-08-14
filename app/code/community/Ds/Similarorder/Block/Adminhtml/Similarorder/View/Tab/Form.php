<?php

class Ds_Similarorder_Block_Adminhtml_Similarorder_View_Tab_Form extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		
		$fieldset = $form->addFieldset('similarorder_form', array(
			'legend' => Mage::helper('similarorder')->__('Item information')
		))->setRenderer($this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset')->setTemplate(
			'similarorder/orderform.phtml'
		)); 
		
		return parent::_prepareForm();
	}
	
	public function getTabLabel()
    {
        return Mage::helper('similarorder')->__('Find Similar');
    }
    
    public function getTabTitle()
    {
        return Mage::helper('similarorder')->__('Find Similar');
    }
    
    public function canShowTab()
    {
        return true;
    }
    
    public function isHidden()
    {
        return false;
    }
}