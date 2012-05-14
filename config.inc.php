<?php
/**
 * Redaxo Addon: REX_Ajax
 * Version: 1.0, 20.07.2010
 * 
 * Autor: Andreas Eberhard, andreas.eberhard@gmail.com
 *        http://rex.andreaseberhard.de
 */
error_reporting(E_ALL);
	// Name des Addons
	$myself = '_rex_ajax_';

	// Credits-Anzeige
	$REX['ADDON']['rxid'][$myself] = '401';
	$REX['ADDON']['version'][$myself] = '1.0';
	$REX['ADDON']['author'][$myself] = 'Andreas Eberhard';
	$REX['ADDON']['supportpage'][$myself] = 'forum.redaxo.de';

	// REDAXO-Version
	$rxa[$myself]['rexversion'] = str_replace('.', '', (isset($REX['VERSION']) ? $REX['VERSION'] . $REX['SUBVERSION'] : $REX['version'] . $REX['subversion']));
	if (strlen($rxa[$myself]['rexversion']) > 2) $rxa[$myself]['rexversion'] = substr($rxa[$myself]['rexversion'], 0 , 2);
	//echo "version=".$rxa[$myself]['rexversion'];
	if ($rxa[$myself]['rexversion'] < 32)
	{
		return;
	}
	
	// Pfade und sonstige Einstellungen
	$rxa[$myself]['basedir'] = dirname(__FILE__);
	$rxa[$myself]['ajaxdir'] = $rxa[$myself]['basedir'] . '/code/';
	$rxa[$myself]['lang_path'] = $REX['INCLUDE_PATH']. '/addons/'. $myself .'/lang';

	// Für Kompatibilität REDAXO 3.2.x, 4.0.x
	if (in_array($rxa[$myself]['rexversion'], array('32', '40')))
	{
		include_once($rxa[$myself]['basedir'] . '/functions/functions.rex_ajax.compat.inc.php');
	}


// Konfigurationsvariablen, werden in pages/settings.inc.php geschrieben

// --- DYN
$REX['ADDON'][$myself]['settings']['frontend_active'] = 1;
$REX['ADDON'][$myself]['settings']['backend_active'] = 1;
$REX['ADDON'][$myself]['settings']['syntax_active'] = 0;
$REX['ADDON'][$myself]['settings']['syntax_highlight'] = 0;
$REX['ADDON'][$myself]['settings']['errormail'] = '';
// --- /DYN



	// Einstellungen aus Konfiguration
	$rxa[$myself]['settings'] = $REX["ADDON"][$myself]['settings'];

/**
 * --------------------------------------------------------------------
 * Nur im Backend: Sprachobjekt, Addon-Berechtigungen
 * --------------------------------------------------------------------
 */
	if ($REX['REDAXO'])
	{
		include_once($rxa[$myself]['basedir'] . '/functions/functions.rex_ajax.inc.php');

		// Sprachobjekt anlegen
		$rxa[$myself]['i18n'] = new i18n($REX['LANG'], $rxa[$myself]['lang_path']);

		// Anlegen eines Navigationspunktes im REDAXO Hauptmenu
		$REX['ADDON']['page'][$myself] = $myself;
		
		// Namensgebung für den Navigationspunkt
		$REX['ADDON']['name'][$myself] = $rxa[$myself]['i18n']->msg('menu_link');

		// Addon-Subnavigation für das REDAXO-Menue
		if (isset($REX['USER']))
		{
			$rxa[$myself]['subpages'] = array();
			$rxa[$myself]['subpages'][] = array('', $rxa[$myself]['i18n']->msg('menu_frontend'));
			$rxa[$myself]['subpages'][] = array('backend', $rxa[$myself]['i18n']->msg('menu_backend'));
			$rxa[$myself]['subpages'][] = array('settings', $rxa[$myself]['i18n']->msg('menu_settings'));
			$rxa[$myself]['subpages'][] = array('info', $rxa[$myself]['i18n']->msg('menu_info'));
			$REX['ADDON'][$myself]['SUBPAGES'] = $rxa[$myself]['subpages'];
		}

		// Berechtigung für das Addon
		$REX['ADDON']['perm'][$myself] = $myself.'[]';

		// Berechtigung in die Benutzerverwaltung einfügen
		$REX['PERM'][] = $myself.'[]';
	}

/**
 * --------------------------------------------------------------------
 * Bei vorhandenem Request Code ausführen
 * --------------------------------------------------------------------
 */
 	$_rex_ajax = rex_request('rex_ajax', 'string');
	
	if ($_rex_ajax <> '')
	{
		include_once($rxa[$myself]['basedir'] . '/functions/functions.rex_ajax.inc.php');
		a401_perform_ajax($_rex_ajax, $rxa[$myself], $REX);
	}
