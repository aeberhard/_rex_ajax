<?php
/**
 * Redaxo Addon: REX_Ajax
 * Version: 1.0, 20.07.2010
 * 
 * Autor: Andreas Eberhard, andreas.eberhard@gmail.com
 *        http://rex.andreaseberhard.de
 */

	// Parameters
	$myself = rex_request('page', 'string');
	$subpage = rex_request('subpage', 'string');
	$func = rex_request('func', 'string');
	
	$modul = rex_request('modul', 'string');
	$oldname = rex_request('oldname', 'string');
	$apply = rex_request('apply', 'string');
	$mdesc = rex_request('mdesc', 'string');
	$mcode = rex_request('mcode', 'string');

	// Syntaxhighlighter einbinden
	if ($REX["ADDON"][$myself]["settings"]["syntax_highlight"])
	{
		if ((($subpage == '') or ($subpage == 'backend')) and (($func == 'edit') or ($func == 'add') or ($func == 'save')))
		{
			//$links  = '  <script type="text/javascript" src="../files/addons/'. $myself .'/codepress/codepress.js"></script>'."\n";
			$links  = '  <link rel="stylesheet" type="text/css" href="../files/addons/'. $myself .'/codemirror/css/docs.css"/>'."\n";
			$links  .= '  <script type="text/javascript" src="../files/addons/'. $myself .'/codemirror/js/codemirror.js"></script>'."\n";
			rex_register_extension('PAGE_HEADER', create_function('$params', 'return $params[\'subject\'].\''. $links .'\';')); 
		}
	}

	// Include Header and Navigation
	include $REX['INCLUDE_PATH'].'/layout/top.php';

	// Fix für REDAXO < 4.2.x
	if (isset($REX_USER)) 
	{
		$REX['USER'] = $REX_USER;
	}

	// Build Subnavigation
	$rxa[$myself]['subpages'] = array();
	$rxa[$myself]['subpages'][] = array('', $rxa[$myself]['i18n']->msg('menu_frontend'));
	$rxa[$myself]['subpages'][] = array('backend', $rxa[$myself]['i18n']->msg('menu_backend'));
	$rxa[$myself]['subpages'][] = array('settings', $rxa[$myself]['i18n']->msg('menu_settings'));
	$rxa[$myself]['subpages'][] = array('info', $rxa[$myself]['i18n']->msg('menu_info'));

	$REX['ADDON'][$myself]['SUBPAGES'] = $rxa[$myself]['subpages'];	
	
	// Title
	if ( in_array($rxa[$myself]['rexversion'], array('3.11')) )
	{
		title($rxa[$myself]['i18n']->msg('title'), $rxa[$myself]['subpages']);
	}
	else
	{
		rex_title($rxa[$myself]['i18n']->msg('title'), $rxa[$myself]['subpages']);
	}

	// Include der Seite
	switch($subpage)
	{
		case 'frontend':
			include ($rxa[$myself]['basedir'] .'/pages/frontend.inc.php');
		break;
		case 'backend':
			include ($rxa[$myself]['basedir'] .'/pages/backend.inc.php');
		break;
		case 'settings':
			include ($rxa[$myself]['basedir'] .'/pages/settings.inc.php');
		break;
		case 'info':
			include ($rxa[$myself]['basedir'] .'/pages/info.inc.php');
		break;
		default:
			include ($rxa[$myself]['basedir'] .'/pages/frontend.inc.php');
		break;		
	}

	// Include Footer
	include $REX['INCLUDE_PATH'].'/layout/bottom.php';
?>