<?php
class Wdarking_Hooks_Block_Adminhtml_Hook_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("hooks_form", array(
            "legend" => Mage::helper("hooks")
                ->__("Item information")
        ));

        $fieldset->addField("event", "text", array(
            "label" => Mage::helper("hooks")
                ->__("event") ,
            "class" => "required-entry",
            "required" => true,
            "name" => "event",
        ));

        $fieldset->addField("url", "text", array(
            "label" => Mage::helper("hooks")
                ->__("URL") ,
            "class" => "required-entry",
            "required" => true,
            "name" => "url",
        ));

        $fieldset->addField("token", "text", array(
            "label" => Mage::helper("hooks")
                ->__("Token") ,
            "name" => "token",
        ));

        $fieldset->addField("enabled", "text", array(
            "label" => Mage::helper("hooks")
                ->__("Enabled") ,
            "class" => "required-entry",
            "required" => true,
            "name" => "enabled",
        ));

        if (Mage::getSingleton("adminhtml/session")
            ->getHookData())
        {
            $form->setValues(Mage::getSingleton("adminhtml/session")
                ->getHookData());
            Mage::getSingleton("adminhtml/session")
                ->setHookData(null);
        }
        elseif (Mage::registry("hook_data"))
        {
            $form->setValues(Mage::registry("hook_data")
                ->getData());
        }
        return parent::_prepareForm();
    }
}

