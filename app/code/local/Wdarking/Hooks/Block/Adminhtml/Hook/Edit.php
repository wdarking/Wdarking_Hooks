<?php
class Wdarking_Hooks_Block_Adminhtml_Hook_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {

        parent::__construct();
        $this->_objectId = "id";
        $this->_blockGroup = "hooks";
        $this->_controller = "adminhtml_hook";
        $this->_updateButton("save", "label", Mage::helper("hooks")
            ->__("Save Item"));
        $this->_updateButton("delete", "label", Mage::helper("hooks")
            ->__("Delete Item"));

        $this->_addButton("saveandcontinue", array(
            "label" => Mage::helper("hooks")
                ->__("Save And Continue Edit") ,
            "onclick" => "saveAndContinueEdit()",
            "class" => "save",
        ) , -100);

        $this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
    }

    public function getHeaderText()
    {
        if (Mage::registry("hook_data") && Mage::registry("hook_data")
            ->getId())
        {

            return Mage::helper("hooks")
                ->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("hook_data")
                ->getId()));

        }
        else
        {

            return Mage::helper("hooks")
                ->__("Add Item");

        }
    }
}

