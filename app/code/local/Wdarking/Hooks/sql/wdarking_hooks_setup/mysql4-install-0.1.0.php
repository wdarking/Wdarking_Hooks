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
$installer->endSetup();
