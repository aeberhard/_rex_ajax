<?php
/**
 * Redaxo Addon: REX_Ajax
 * Version: 1.0, 20.07.2010
 * 
 * Autor: Andreas Eberhard, andreas.eberhard@gmail.com
 *        http://rex.andreaseberhard.de
 */

if (!function_exists('a401_perform_ajax')) {
function a401_perform_ajax($_rex_ajax, $rxa, $REX)
{

	$rxa['ajaxdir'] .= ($REX['REDAXO']) ? 'backend/' : 'frontend/';
	
	if (!$REX['REDAXO'] and !$rxa['settings']['frontend_active'])
	{
		return;
	}
	if ($REX['REDAXO'] and !$rxa['settings']['backend_active'])
	{
		return;
	}

	if (strtoupper(substr(PHP_OS, 0,3)) == 'WIN')
	{
		$rxa['ajaxdir'] = str_replace("/", "\\", $rxa['ajaxdir']);
	}
	
	// Code ausführen falls die Datei vorhanden ist
	if (file_exists($rxa['ajaxdir'] . $_rex_ajax . '.inc.php'))
	{
		@ob_end_clean();
		@ob_end_clean();

		// Fix für REDAXO < 4.2.x
		if (isset($REX_ARTICLE)) 
		{
			$REX['ARTICLE'] = $REX_ARTICLE;	
		}
		
		// Include der Datei
		include_once($rxa['ajaxdir'] . $_rex_ajax . '.inc.php');
		exit;
	}
	else
	// ungültiger Aufruf, evtl. Email senden
	{
		if (trim($REX["ADDON"][$rxa['name']]["settings"]["errormail"])<>'')
		{
			$_subject = 'REX_Ajax Error ' . $REX['SERVER'] . ' ' . $REX['SERVERNAME'];

			$_mailtext = $_subject . "\n\n" . date('d.m.Y h:i:s'). "\n\n";
			
			ob_start();
			var_dump($_REQUEST);
			$_mailtext .= "_REQUEST\n";
			$_mailtext .= ob_get_contents();
			ob_end_clean();
			
			ob_start();
			var_dump($_SESSION);
			$_mailtext .= "\n_SESSION\n";
			$_mailtext .= ob_get_contents();
			ob_end_clean();

			ob_start();
			var_dump($_SERVER);
			$_mailtext .= "\n_SERVER\n";
			$_mailtext .= ob_get_contents();
			ob_end_clean();
			
			$_to = $REX["ADDON"][$rxa['name']]["settings"]["errormail"];
			$_header = 'From: ' . $REX['ERROR_EMAIL'] . "\r\n" . 'Reply-To: ' . $REX['ERROR_EMAIL'] . "\r\n" . 'X-Mailer: PHP/' . phpversion();

			if (OOAddon::isAvailable('phpmailer'))
			{
				if (!isset($I18N))
				{
					$I18N = rex_create_lang($REX['LANG']);
				}
				include ($REX['INCLUDE_PATH'] . '/addons/phpmailer/config.inc.php');
				$mail = new rex_mailer();
				$mail->From = $REX['ERROR_EMAIL'];
				$mail->Subject = $_subject; 			
				$mail->Body = $_mailtext;
				$mail->AddAddress($_to, '');
				$mail->Send();
			}
			else
			{
				@mail($_to, $_subject, $_mailtext, $_header);
			}
		}
	}

}
}

/**
 * PHP-Code ausführen mit Syntaxcheck
 */
if (!function_exists('a401_eval_code')) {
function a401_eval_code($code, $mod)
{
	$evalrc = array();
	$evalrc['phperror'] = shell_exec('php -l "'.$code.'"');
	if (strstr($evalrc['phperror'], 'No syntax errors')) 
	{
		$evalrc['phperror'] = '';
	}

	$evalrc['phperror'] = str_replace($code, $mod, nl2br($evalrc['phperror']));
	if( strtoupper (substr(PHP_OS, 0,3)) == 'WIN' )
	{
		$code = str_replace("/", "\\", $code);
	}
	$evalrc['phperror'] = str_replace($code, $mod, nl2br($evalrc['phperror']));
	$evalrc['phperror'] = str_replace('Errors parsing', '<br />Errors parsing', $evalrc['phperror']);

	$suchmuster = "/<b>(\d)<\/b>/e";
	$ersetzung = "$1-1";
	$evalrc['phperror'] = preg_replace($suchmuster, $ersetzung, $evalrc['phperror']);
	$evalrc['phperror'] = str_replace('on line', 'on line ', $evalrc['phperror']);
	
	return $evalrc;
}
}

