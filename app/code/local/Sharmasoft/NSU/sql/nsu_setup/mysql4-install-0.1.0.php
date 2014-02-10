<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('nsu')};
CREATE TABLE {$this->getTable('nsu')} (
	`nsu_id` int(11) unsigned NOT NULL auto_increment,
	`stud_id` varchar(255) NOT NULL default '',
	`status` smallint(6) NOT NULL default '0',
	`created_time` datetime NULL,
	`update_time` datetime NULL,
  PRIMARY KEY (`nsu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 