<?php
class Sharmasoft_NSU_Block_Adminhtml_Nsu extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_nsu';
    $this->_blockGroup = 'nsu';
    $this->_headerText = Mage::helper('nsu')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('nsu')->__('Add Item');
    parent::__construct();
  }
}