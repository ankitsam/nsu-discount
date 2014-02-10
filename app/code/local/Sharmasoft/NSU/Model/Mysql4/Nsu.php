<?php

class Sharmasoft_NSU_Model_Mysql4_NSU extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the nsu_id refers to the key field in your database table.
        $this->_init('nsu/nsu', 'nsu_id');
    }
}