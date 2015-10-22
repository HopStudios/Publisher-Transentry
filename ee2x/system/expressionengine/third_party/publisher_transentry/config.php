<?php
$config['name']='Publisher Transentry';
$config['version']='0.1';
$config['nsm_addon_updater']['versions_xml']='http://www.hopstudios.com/software/versions/edit_this/';

// Version constant
if (!defined("PUBLISHER_TRANSENTRY_VERSION")) {
	define('PUBLISHER_TRANSENTRY_VERSION', $config['version']);
}