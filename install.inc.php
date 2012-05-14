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
	
	// Schreibrechte setzen
	chmod($REX['INCLUDE_PATH'].'/addons/' . $myself . '/config.inc.php', $REX['FILEPERM']);
	chmod($rxa[$myself]['ajaxdir'], $REX['DIRPERM']);
	chmod($rxa[$myself]['ajaxdir'] . 'frontend/', $REX['DIRPERM']);
	chmod($rxa[$myself]['ajaxdir'] . 'backend/', $REX['DIRPERM']);

	// REDAXO 3.2.x, 4.0.x, 4.1.x - Dateien in Ordner files/addons/ kopieren
	if (($rxa[$myself]['rexversion'] == '32') or ($rxa[$myself]['rexversion'] == '40') or ($rxa[$myself]['rexversion'] == '41'))
	{
		$source_dir = $REX['INCLUDE_PATH'] . '/addons/' . $myself . '/files';
		$dest_dir = $REX['MEDIAFOLDER'] . '/addons/' . $myself;
		$start_dir = $REX['MEDIAFOLDER'] . '/addons';
		
		if (is_dir($source_dir))
		{
			if (!is_dir($start_dir))
			{
				mkdir($start_dir);
			}
			if(!rex_copyDir($source_dir, $dest_dir , $start_dir))
			{
				$REX['ADDON']['installmsg'][$myself] = 'Verzeichnis '.$source_dir.' konnte nicht nach '.$dest_dir.' kopiert werden!';
				$REX['ADDON']['install'][$myself] = 0;
			}
		}
	}
	
	$REX['ADDON']['install'][$myself] = 1;
