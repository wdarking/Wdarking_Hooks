<?php

class Wdarking_Hooks_Block_Adminhtml_Hook extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {

        $this->_controller = "adminhtml_hook";
        $this->_blockGroup = "hooks";
        $this->_headerText = Mage::helper("hooks")
            ->__("Hook Manager");
        $this->_addButtonLabel = Mage::helper("hooks")
            ->__("Add New Item");
        parent::__construct();

    }

}

