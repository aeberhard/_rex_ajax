<?php
/**
 * Redaxo Addon: REX_Ajax
 * Version: 1.0, 20.07.2010
 * 
 * Autor: Andreas Eberhard, andreas.eberhard@gmail.com
 *        http://rex.andreaseberhard.de
 */

	// Name des Addons
	$myself = '_rex_ajax_';
	
	$rxa[$myself]['basedir'] = dirname(__FILE__);

	if (strstr($REX['LANG'],'utf8'))
	{
		echo utf8_encode(nl2br(file_get_contents($rxa[$myself]['basedir'].'/lang/'.$REX['LANG'].'.help.txt')));
	}
	else
	{
		echo nl2br(file_get_contents($rxa[$myself]['basedir'].'/lang/'.$REX['LANG'].'.help.txt'));
	}
