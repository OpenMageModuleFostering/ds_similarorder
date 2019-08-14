<?php
class Ds_Similarorder_Block_Adminhtml_Similarorder_Grid extends Mage_Adminhtml_Block_Widget_Grid
{    
	public function __construct()
    {
		
        parent::__construct();
        $this->setId('ds_order_grid');
        $this->setDefaultSort('increment_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
		$this->setFilterVisibility(false);
	
    }
	
    protected function _prepareCollection()
    {
		$ordersearch = Mage::getResourceModel('sales/order_collection');
		if ($data = $this->getRequest()->getPost()) 
		{
			if($data['order_id'] != '')
			{
				$ordersearch = $ordersearch->addAttributeToFilter('increment_id', array('neq' => $data['order_id']));
				
				$order = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('increment_id',$data['order_id'])->getFirstItem();
				if($order->getData())
				{
					if(in_array("IP", $data['filter']))
					{
						$remote_ip	=	$order->getData('remote_ip'); 
						
						$ordersearch = $ordersearch->addFieldToFilter('remote_ip',$remote_ip);
						/* echo "<pre>"; print_r($ordersearch->getData()); exit; */
					}
					/* if(in_array("cookie", $data['filter']))
					{
						$cookie	=	$order->getData('cookie'); 
						$ordersearch = $ordersearch->addFieldToFilter('cookie',$cookie);
					} */
					
						$addData	=	$order->getBillingAddress()->getData();
						
						$postcode	=	$addData['postcode'];
						$street		=	$addData['street'];
						$city		=	$addData['city'];
						$telephone	=	$addData['telephone'];
						$country_id	=	$addData['country_id'];
						/* $cPhones[]=$telephone; */
					
					if(in_array("Address", $data['filter']))
					{
						$ordersearch = $ordersearch->addFieldToFilter('a.postcode',$postcode)
										->addFieldToFilter('a.street',$street)
										->addFieldToFilter('a.city',$city)									
										->addFieldToFilter('a.country_id',$country_id);
										
					}
					if(in_array("Phone", $data['filter']))
					{
						$ordersearch = $ordersearch->addFieldToFilter('a.telephone',$telephone);
					}
					
				}
				else
				{
					$ordersearch = $ordersearch->addAttributeToFilter('increment_id', "0");
				}
			}
		}
		
		
		$checkArray=array("IP","Phone","Address");		
		if(count(array_intersect($data['filter'], $checkArray)) == 0 || $data['order_id'] == '')
		{
			$ordersearch = $ordersearch->addAttributeToFilter('increment_id', "0");
		}
		
        $collection = $ordersearch
            ->join(array('a' => 'sales/order_address'), 'main_table.entity_id = a.parent_id AND a.address_type = \'billing\'', array(
                'city'       => 'city',
                'country_id' => 'country_id'
            ))
            ->addExpressionFieldToSelect(
                'fullname',
                'CONCAT({{customer_firstname}}, \' \', {{customer_lastname}})',
                array('customer_firstname' => 'main_table.customer_firstname', 'customer_lastname' => 'main_table.customer_lastname'))
            ->addExpressionFieldToSelect(
                'products',
                '(SELECT GROUP_CONCAT(\' \', x.name)
                    FROM sales_flat_order_item x
                    WHERE {{entity_id}} = x.order_id
                        AND x.product_type != \'configurable\')',
                array('entity_id' => 'main_table.entity_id')
            );
			
        #echo $collection->getSelect()."<hr>";
		/* echo "<pre>"; print_r($collection->getData()); exit; */
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }
	
    protected function _prepareColumns()
    {
	
        $helper = Mage::helper('similarorder');
        $currency = (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE);
        $this->addColumn('increment_id', array(
            'header' => $helper->__('Order #'),
			'filter'    => false,
			'sortable'  => false,
            'index'  => 'increment_id'
        ));
        $this->addColumn('purchased_on', array(
            'header' => $helper->__('Purchased On'),
            'type'   => 'datetime',
			'filter'    => false,
			'sortable'  => false,
            'index'  => 'created_at'
        ));
        $this->addColumn('products', array(
            'header'       => $helper->__('Products Purchased'),
            'index'        => 'products',
			'filter'    => false,
			'sortable'  => false,
            'filter_index' => '(SELECT GROUP_CONCAT(\' \', x.name) FROM sales_flat_order_item x WHERE main_table.entity_id = x.order_id AND x.product_type != \'configurable\')'
        ));
		$this->addColumn('remote_ip', array(
            'header' => $helper->__('Ip Address'),
			'filter'    => false,
			'sortable'  => false,
            'index'  => 'remote_ip'
        ));
        $this->addColumn('fullname', array(
            'header'       => $helper->__('Name'),
            'index'        => 'fullname',
			'filter'    => false,
			'sortable'  => false,
            'filter_index' => 'CONCAT(customer_firstname, \' \', customer_lastname)'
        ));
        $this->addColumn('city', array(
            'header' => $helper->__('City'),
			'filter'    => false,
			'sortable'  => false,
            'index'  => 'city'
        ));
        $this->addColumn('country', array(
            'header'   => $helper->__('Country'),
			'filter'    => false,
			'sortable'  => false,
            'index'    => 'country_id',
            'renderer' => 'adminhtml/widget_grid_column_renderer_country'
        ));
        $this->addColumn('grand_total', array(
            'header'        => $helper->__('Grand Total'),
            'index'         => 'grand_total',
			'filter'    => false,
			'sortable'  => false,
            'type'          => 'currency',
            'currency_code' => $currency
        ));
        $this->addColumn('order_status', array(
            'header'  => $helper->__('Status'),
            'index'   => 'status',
			'filter'    => false,
			'sortable'  => false,
            'type'    => 'options',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));
       
        return parent::_prepareColumns();
    }
	
    public function getGridUrl()
    {
        return $this->getUrl('similarorder/adminhtml_ordergrid/grid', array('_current' => true));
    }
	
	public function getRowUrl($row)
	{
		return $this->getUrl('adminhtml/sales_order/view', array('order_id' => $row->getId()));
	}

}