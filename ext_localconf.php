<?php

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

if ('BE' == TYPO3_MODE) {
	// Do not show the IP records in the listing
	$allowedTablesTS = "
		mod.web_list.deniedNewTables := addToList(tx_aoeipauth_domain_model_ip)
		mod.web_list.hideTables := addToList(tx_aoeipauth_domain_model_ip)
	";
	t3lib_extMgm::addPageTSConfig($allowedTablesTS);

	// Hooks
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$_EXTKEY] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/Tcemain.php:Tx_AoeIpauth_Hooks_Tcemain';
} else if ('FE' == TYPO3_MODE) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['nc_staticfilecache/class.tx_ncstaticfilecache.php']['createFile_initializeVariables'][$_EXTKEY] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/Staticfilecache.php:Tx_AoeIpauth_Hooks_Staticfilecache->createFileInitializeVariables';
}

// IP Authentication Service
t3lib_extMgm::addService($_EXTKEY,  'auth',  'tx_aoeipauth_typo3_service_authentication',
	array(
		'title' => 'IP Authentication',
		'description' => 'Authenticates against IP addresses and ranges.',
		'subtype' => 'getUserFE,getGroupsFE',
		'available' => TRUE,
		// Must be higher than for tx_sv_auth (50) or tx_sv_auth will deny request unconditionally
		'priority' => 80,
		'quality' => 50,
		'os' => '',
		'exec' => '',
		'classFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Classes/Typo3/Service/Authentication.php',
		'className' => 'Tx_AoeIpauth_Typo3_Service_Authentication',
	)
);
?>