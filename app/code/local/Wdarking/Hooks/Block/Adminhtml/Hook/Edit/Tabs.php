<?php
class Wdarking_Hooks_Block_Adminhtml_Hook_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId("hook_tabs");
        $this->setDestElementId("edit_form");
        $this->setTitle(Mage::helper("hooks")
            ->__("Item Information"));
    }
    protected function _beforeToHtml()
    {
        $this->addTab("form_section", array(
            "label" => Mage::helper("hooks")
                ->__("Item Information") ,
            "title" => Mage::helper("hooks")
                ->__("Item Information") ,
            "content" => $this->getLayout()
                ->createBlock("hooks/adminhtml_hook_edit_tab_form")
                ->toHtml() ,
        ));
        return parent::_beforeToHtml();
    }

}

