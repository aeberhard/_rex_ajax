<?php
/**
 * Redaxo Addon: REX_Ajax
 * Version: 1.0, 20.07.2010
 * 
 * Autor: Andreas Eberhard, andreas.eberhard@gmail.com
 *        http://rex.andreaseberhard.de
 */

	// Name des Addons
	//$myself = '_rex_ajax_';
	include(dirname(__FILE__) . '/config.inc.php');
	if ($rxa[$myself]['rexversion'] < 32)
	{
		return;
	}

	// REDAXO 3.2.3, 4.0.x, 4.1.x - Dateien in Ordner files/addons/ kopieren
	if (($rxa[$myself]['rexversion'] == '32') or ($rxa[$myself]['rexversion'] == '40') or ($rxa[$myself]['rexversion'] == '41'))
	{
		$addon_filesdir = $REX['MEDIAFOLDER'] . '/addons/' . $myself;
		if (is_dir($addon_filesdir))
		{
			if(!rex_deleteDir($addon_filesdir, true))
			{
				$REX['ADDON']['installmsg'][$myself] = 'Verzeichnis '.$addon_filesdir.' konnte nicht gelöscht werden!';
				$REX['ADDON']['install'][$myself] = 1;	
			}
		}
	}	

	$REX['ADDON']['install'][$myself] = 0;
