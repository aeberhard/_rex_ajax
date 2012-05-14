<?php
/**
 * Redaxo Addon: REX_Ajax
 * Version: 1.0, 20.07.2010
 * 
 * Autor: Andreas Eberhard, andreas.eberhard@gmail.com
 *        http://rex.andreaseberhard.de
 */

	$_ajaxdir = $rxa[$myself]['ajaxdir'] . 'frontend/';
	if( strtoupper (substr(PHP_OS, 0,3)) == 'WIN' )
	{
		$_ajaxdir = str_replace("/", "\\", $_ajaxdir);
	}	
	$error = false;

	// Modul Löschen
	if ($func == 'delete') 
	{
		$_module = $_ajaxdir . $modul . '.inc.php';
		unlink($_module);
		echo rex_info($rxa[$myself]['i18n']->msg('message_deleted', $modul));	
	}

	// neues Modul anlegen
	if ($func == 'add') 
	{
		$mcode = '';
		$oldname = '';
	}

	// Modul speichern
	if ($func == 'save') 
	{
		$_module = $_ajaxdir . $modul . '.inc.php';

		$_codeoutput = '<?php // title: '.$mdesc.' ?>'."\n";
		$_codeoutput .= stripslashes($mcode);
		
		if (trim($modul) == '')
		{
			echo rex_warning($rxa[$myself]['i18n']->msg('message_error_name'));		
			$error = true;
		}
		if (!preg_match("/^[a-zA-Z0-9_]*$/", $modul))
		{
			echo rex_warning($rxa[$myself]['i18n']->msg('message_error_name2'));		
			$error = true;
		}

		if ((trim($modul) <> '') and ($oldname == '') and file_exists($_module))
		{
			echo rex_warning($rxa[$myself]['i18n']->msg('message_error_dup', $modul));		
			$error = true;
		}
		if ((trim($modul) <> '') and ($oldname <> '') and ($oldname <> $modul) and file_exists($_module))
		{
			echo rex_warning($rxa[$myself]['i18n']->msg('message_error_dup', $modul));		
			$error = true;
		}
		
		if (trim($mdesc) == '')
		{
			echo rex_warning($rxa[$myself]['i18n']->msg('message_error_desc'));		
			$error = true;
		}

		if (!$error)
		{
			if (@file_put_contents($_module, $_codeoutput) === false)
			{
				echo rex_warning($rxa[$myself]['i18n']->msg('message_error_save', $modul));	
				$error = true;
				$func = 'edit';	
			}
			else
			{
				echo rex_info($rxa[$myself]['i18n']->msg('message_saved', $modul));	
				$oldname = $modul;	
				if ($apply <> '')
				{
					$func = 'edit';
				}
				if ($REX["ADDON"][$rxa[$myself]['name']]["settings"]["syntax_active"])
				{
					$_erc = rex_401_eval_code($_module, $modul);
					if (isset($_erc['phperror']) and $_erc['phperror'] <> '')
					{
						echo rex_warning($_erc['phperror']);	
					}
				}
			}
		}
		else 
		{
			$func = 'edit';
		}
	}

	// Editieren
	if (!$error and $func == 'edit') 
	{
		$_module = $_ajaxdir . $modul . '.inc.php';
		$lines = file($_module);
		$mdesc = $lines[0];
		if (strstr($mdesc, '<?php // title:'))
		{
			$va = explode('title:', $mdesc);
			$mdesc = trim(htmlspecialchars(str_replace('?>', '', $va[1])));
			$lines[0] = '';
		}
		else 
		{
			$mdesc = '';
		}
		$mcode = htmlspecialchars(trim(implode('', $lines)));
		$oldname = $modul;
	}
	else
	{
		$mcode = stripslashes($mcode);
		$oldname = $oldname;
	}

	if (($func == 'edit') or ($func == 'add'))
	{
?>



<div class="rex-addon-output">
<h2 class="rex-hl2"><?php echo $rxa[$myself]['i18n']->msg('editform_edit_title'); ?></h2>

<?php
	if (($rxa[$myself]['rexversion'] == '32') or ($rxa[$myself]['rexversion'] == '40') or ($rxa[$myself]['rexversion'] == '41'))
	{
		echo '<div class="rex-addon-content">';
	}
	else
	{
		echo '<div class="rex-form">';
	}
	if ($REX["ADDON"][$myself]["settings"]["syntax_highlight"])
	{
		echo '  <form action="index.php" method="post" onsubmit="document.getElementById(\'mcode\').value = editmcode.getCode();">';
	}
	else
	{
		echo '  <form action="index.php" method="post">';
	}
?>

    <fieldset class="rex-form-col-1">
    <div class="rex-form-wrapper">

      <input type="hidden" name="page" value="<?php echo $myself; ?>" />
      <input type="hidden" name="subpage" value="<?php echo $subpage; ?>" />
      <input type="hidden" name="oldname" value="<?php echo $oldname; ?>" />
      <input type="hidden" name="func" value="save" />
<?php
	if ($REX["ADDON"][$myself]["settings"]["syntax_highlight"])
	{
		echo '      <input type="hidden" name="mcode" id="mcode" value="" />';
	}
?>

      <div class="rex-form-row">
        <p class="rex-form-col-a rex-form-text">
          <label for="modul"><?php echo $rxa[$myself]['i18n']->msg('editform_edit_name'); ?></label>
          <input class="rex-form-text" type="text" size="100" id="modul" name="modul" value="<?php echo $modul; ?>" />
        </p>
      </div>

      <div class="rex-form-row">
        <p class="rex-form-col-a rex-form-text">
          <label for="mdesc"><?php echo $rxa[$myself]['i18n']->msg('editform_edit_desc'); ?></label>
          <input class="rex-form-text" type="text" size="100" id="mdesc" name="mdesc" value="<?php echo $mdesc; ?>" />
        </p>
      </div>

      <div class="rex-form-row">
        <p class="rex-form-col-a rex-form-textarea">
          <label for="mcode"><?php echo $rxa[$myself]['i18n']->msg('editform_edit_code'); ?></label>
<?php
	if ($REX["ADDON"][$myself]["settings"]["syntax_highlight"])
	{		  
		echo '          <textarea class="rex-form-textarea codepress javascript linenumbers-on autocomplete-off" cols="100" rows="25" name="editmcode" id="editmcode">'.$mcode.'</textarea>'."\n";
	}
	else
	{
		echo '          <textarea class="rex-form-textarea" cols="100" rows="25" name="mcode" id="mcode">'.$mcode.'</textarea>'."\n";
	}
?>		  
        </p>
      </div>

      <div class="rex-form-row">
        <p class="rex-form-col-a rex-form-submit">
          <input class="rex-form-submit" type="submit" value="<?php echo $rxa[$myself]['i18n']->msg('button_msave'); ?>" />
          <input type="submit" class="rex-form-submit rex-form-submit-2" name="apply" value="<?php echo $rxa[$myself]['i18n']->msg('button_mapply'); ?>" />
        </p>
      </div>

    </div>
    </fieldset>

  </form>

</div>

</div>


<?php
		return;
	}
?> 



<div class="rex-addon-output">
  <h2 class="rex-hl2"><?php echo $rxa[$myself]['i18n']->msg('title_frontend'); ?></h2>

  <div class="rex-addon-content">
<?php 
	$_showajaxdir = $_ajaxdir;
	if (strlen($_ajaxdir)>30)
	{
		$_showajaxdir = substr($_ajaxdir, 0, 20) . ' ... ' . substr($_ajaxdir, -30);
	}
	echo $rxa[$myself]['i18n']->msg('modules_dir', $_showajaxdir, $_ajaxdir); 

	$_host = 'http://meinedomain.de/';
	if (file_exists($_ajaxdir . 'article.inc.php'))
	{
		$splitURL = explode('files/', dirname($_SERVER['REQUEST_URI']));	
		$_host = 'http://' . $_SERVER["HTTP_HOST"] . ((substr($splitURL[0], -7) == '/redaxo') ? substr($splitURL[0], 0, strlen($splitURL[0])-6) : $splitURL[0]);
	}	
	echo $rxa[$myself]['i18n']->msg('beispiel_frontend', $_host);
	
	$_modules = glob($_ajaxdir. '*.inc.php');
	if (count($_modules) == 0)
	{
		echo $rxa[$myself]['i18n']->msg('modules_notfound');
	}
	else
	{
		echo $rxa[$myself]['i18n']->msg('modules_count', count($_modules));
	}
?> 
  </div>
  
</div>



<?php
	$_func_add = 'page='.$myself.'&amp;subpage=&amp;func=add';
	$_func_edit = 'page='.$myself.'&amp;subpage=&amp;func=edit&amp;modul=';
	$_func_del = 'page='.$myself.'&amp;subpage=&amp;func=delete&amp;modul=';
?>
<div class="rex-addon-output-v2">

  <table summary="<?php echo $rxa[$myself]['i18n']->msg('editform_summary_fe'); ?>" class="rex-table">
    <caption><?php echo $rxa[$myself]['i18n']->msg('editform_caption_fe'); ?></caption>
    <colgroup>
      <col width="40" />
      <col width="0" />
      <col width="*" />
      <col width="0" />
      <col width="0" />
    </colgroup>
    <thead>
      <tr>
<?php
	if (($rxa[$myself]['rexversion'] == '32') or ($rxa[$myself]['rexversion'] == '40') or ($rxa[$myself]['rexversion'] == '41'))
	{
		echo '<th class="rex-icon"><a class="rex-i-element rex-i-module-add" href="index.php?'.$_func_add.'"><img src="media/modul_plus.gif" alt="'.$rxa[$myself]['i18n']->msg('editform_func_create').'" title="'.$rxa[$myself]['i18n']->msg('editform_func_create').'" /></a></th>';
	}
	else
	{
		echo '<th class="rex-icon"><a class="rex-i-element rex-i-module-add" href="index.php?'.$_func_add.'" title="'.$rxa[$myself]['i18n']->msg('editform_func_create').'"><span class="rex-i-element-text" title="'.$rxa[$myself]['i18n']->msg('editform_func_create').'">'.$rxa[$myself]['i18n']->msg('editform_func_create').'</span></a></th>';
	}
?>
        <th><?php echo $rxa[$myself]['i18n']->msg('editform_title_name'); ?></th>
        <th><?php echo $rxa[$myself]['i18n']->msg('editform_title_desc'); ?></th>
        <th colspan="2"><?php echo $rxa[$myself]['i18n']->msg('editform_title_func'); ?></th>
      </tr>
    </thead>

<?php
	if (count($_modules) >= 1)
	{
		echo '    <tbody>'."\n";
		natsort($_modules);
		foreach ($_modules as $_module)
		{
			$modul = str_replace('.inc.php', '', basename($_module));

			$lines = file($_module);
			$mdesc = $lines[0];

			if (strstr($mdesc, '<?php // title:'))
			{
				$va = explode('title:', $mdesc);
				$mdesc = trim(htmlspecialchars(str_replace('?>', '', $va[1])));
			}
			else
			{
				$mdesc = '';
			}
			if ($mdesc == '') $mdesc = '[untitled]';
?>
	<tr>
		<td class="rex-icon">
<?php
	if (($rxa[$myself]['rexversion'] == '32') or ($rxa[$myself]['rexversion'] == '40') or ($rxa[$myself]['rexversion'] == '41'))
	{
		echo '<a class="rex-i-element rex-i-module" href="index.php?'. $_func_edit . $modul .'"><img src="media/modul.gif" alt="'.$modul.'" title="'.$modul.'" /></a>';
	}
	else
	{
		echo '<a class="rex-i-element rex-i-module" href="index.php?'. $_func_edit . $modul .'"><span class="rex-i-element rex-i-module"><span class="rex-i-element-text">'.$modul.'</span></span></a>';
	}
?>
		</td>
		<td><a href="index.php?<?php echo $_func_edit . $modul; ?>"><?php echo $modul; ?></a></td>
		<td><?php echo $mdesc; ?></td>
		<td><a href="index.php?<?php echo $_func_edit . $modul; ?>"><?php echo $rxa[$myself]['i18n']->msg('editform_func_edit'); ?></a></td>
		<td><a href="index.php?<?php echo $_func_del . $modul; ?>" onclick="return confirm('<?php echo $rxa[$myself]['i18n']->msg('editform_func_delmod'); ?>')"><?php echo $rxa[$myself]['i18n']->msg('editform_func_del'); ?></a></td>
	</tr>
<?php
		}
		echo '    </tbody>'."\n";
	}
?>

  </table>

</div>


    <script type="text/javascript">
      var editor = CodeMirror.fromTextArea('editmcode', {
        height: "350px",
        parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js",
                     "../contrib/php/js/tokenizephp.js", "../contrib/php/js/parsephp.js",
                     "../contrib/php/js/parsephphtmlmixed.js"],
        stylesheet: ["../../css/xmlcolors.css", "../../css/jscolors.css", "../../css/csscolors.css", "css/phpcolors.css"],
        path: "../../js/",
        continuousScanning: 500
      });
    </script>
