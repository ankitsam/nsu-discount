<?php

class Sharmasoft_NSU_Block_Adminhtml_NSU_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('nsu_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('nsu')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('nsu')->__('Item Information'),
          'title'     => Mage::helper('nsu')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('nsu/adminhtml_nsu_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}