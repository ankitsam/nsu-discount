<?php
class Sharmasoft_NSU_Block_NSU extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getNSU()     
     { 
        if (!$this->hasData('nsu')) {
            $this->setData('nsu', Mage::registry('nsu'));
        }
        return $this->getData('nsu');
        
    }
}