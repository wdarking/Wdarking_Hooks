<?php
$installer = $this;
$installer->startSetup();

$installer->run("
    -- DROP TABLE IF EXISTS {$installer->getTable('hooks/propagation')};
    CREATE TABLE `{$installer->getTable('hooks/propagation')}` (
      `id` int(11) NOT NULL auto_increment,
      `event` varchar(255) default NULL,
      `data` json default NULL,
      `response_status` varchar(255) default NULL,
      `response_data` text default NULL,
      `tries` int(11) default NULL,
      `propagated_at` datetime default NULL,
      `backoff_at` datetime default NULL,
      `created_at` datetime default NULL,
      `updated_at` datetime default NULL,
      PRIMARY KEY  (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
    -- DROP TABLE IF EXISTS {$installer->getTable('hooks/hook')};
    CREATE TABLE `{$installer->getTable('hooks/hook')}` (
      `id` int(11) NOT NULL auto_increment,
      `event` varchar(255) default NULL,
      `url` varchar(500) default NULL,
      `token` varchar(500) default NULL,
      `enabled` boolean default NULL,
      `created_at` datetime default NULL,
      `updated_at` datetime default NULL,
      PRIMARY KEY  (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$attribute = Mage::getResourceModel('catalog/eav_attribute')
    ->loadByCode('customer', 'wdk_hooks_propagation_status');

if (!$attribute->getId()) {
    Mage::log("wdarking_hooks_setup: wdk_hooks_propagation_status will be created.");

    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');

    $setup->addAttribute("customer", "wdk_hooks_propagation_status",  array(
        "type"     => "varchar",
        "backend"  => "",
        "label"    => "Hooks Propagation Status",
        "input"    => "text",
        "source"   => "",
        "visible"  => true,
        "required" => false,
        "default" => "",
        "frontend" => "",
        "unique"     => false,
        "note"       => ""
    ));

    $attribute   = Mage::getSingleton("eav/config")->getAttribute("customer", "wdk_hooks_propagation_status");

    $used_in_forms = array();

    $used_in_forms[] = "adminhtml_customer";
    $used_in_forms[] = "adminhtml_checkout";
    $attribute->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", true)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 100);

    $attribute->save();

} else {
    Mage::log("wdarking_hooks_setup: wdk_propagation_created_at was already created.");
}

$installer->endSetup();
