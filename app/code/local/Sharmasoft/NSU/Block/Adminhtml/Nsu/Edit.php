<?php

class Sharmasoft_NSU_Block_Adminhtml_NSU_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'nsu';
        $this->_controller = 'adminhtml_nsu';
        
        $this->_updateButton('save', 'label', Mage::helper('nsu')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('nsu')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('nsu_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'nsu_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'nsu_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('nsu_data') && Mage::registry('nsu_data')->getId() ) {
            return Mage::helper('nsu')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('nsu_data')->getTitle()));
        } else {
            return Mage::helper('nsu')->__('Add Item');
        }
    }
}