<?php

class Sharmasoft_NSU_Block_Adminhtml_NSU_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('nsu_form', array('legend'=>Mage::helper('nsu')->__('Item information')));
     
		$fieldset->addField('stud_id', 'text', array(
          'label'     => Mage::helper('nsu')->__('Student ID'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'stud_id',
      ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('nsu')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('nsu')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('nsu')->__('Disabled'),
              ),
          ),
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getNSUData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getNSUData());
          Mage::getSingleton('adminhtml/session')->setNSUData(null);
      } elseif ( Mage::registry('nsu_data') ) {
          $form->setValues(Mage::registry('nsu_data')->getData());
      }
      return parent::_prepareForm();
  }
}