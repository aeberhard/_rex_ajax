<?php
/**
 * Redaxo Addon: REX_Ajax
 * Version: 1.0, 20.07.2010
 * 
 * Autor: Andreas Eberhard, andreas.eberhard@gmail.com
 *        http://rex.andreaseberhard.de
 */

	if (in_array($rxa[$myself]['rexversion'], array('32')))
	{
		include_once($rxa[$myself]['basedir'] . '/classes/class.rex_select.inc.php');
	}

	$myself = rex_request('page', 'string');
	$myREX = $REX['ADDON'][$myself];

	if ($func == 'savesettings')
	{
		$content = '';
		foreach($_POST as $key => $val)
		{
			if(!in_array($key,array('page', 'subpage', 'minorpage', 'func', 'submit', 'PHPSESSID')))
			{
				$myREX['settings'][$key] = $val;
				if(is_array($val))
				{
					$content .= '$REX[\'ADDON\'][$myself][\'settings\'][\''.$key.'\'] = '.var_export($val,true).';'."\n";
				}
				else
				{
					if(is_numeric($val))
					{
						$content .= '$REX[\'ADDON\'][$myself][\'settings\'][\''.$key.'\'] = '.$val.';'."\n";
					}
					else
					{
						$content .= '$REX[\'ADDON\'][$myself][\'settings\'][\''.$key.'\'] = \''.$val.'\';'."\n";
					}
				}
			}
		}

		$file = $REX['INCLUDE_PATH'].'/addons/' . $myself . '/config.inc.php';
		rex_replace_dynamic_contents($file, $content);
		echo rex_info('Einstellungen wurden gespeichert.');
	}

	$tmp = new rex_select();
	$tmp->setSize(1);
	$tmp->setName('frontend_active');
	$tmp->setId('frontend_active');
	$tmp->addOption('nein',0);
	$tmp->addOption('ja',1);
	$tmp->setSelected($myREX['settings']['frontend_active']);
	$_frontend = $tmp->get();

	$tmp = new rex_select();
	$tmp->setSize(1);
	$tmp->setName('backend_active');
	$tmp->setId('backend_active');
	$tmp->addOption('nein',0);
	$tmp->addOption('ja',1);
	$tmp->setSelected($myREX['settings']['backend_active']);
	$_backend = $tmp->get();   	

	$tmp = new rex_select();
	$tmp->setSize(1);
	$tmp->setName('syntax_active');
	$tmp->setId('syntax_active');
	$tmp->addOption('nein',0);
	$tmp->addOption('ja',1);
	$tmp->setSelected($myREX['settings']['syntax_active']);
	$_syntax = $tmp->get(); 

	$tmp = new rex_select();
	$tmp->setSize(1);
	$tmp->setName('syntax_highlight');
	$tmp->setId('syntax_highlight');
	$tmp->addOption('nein',0);
	$tmp->addOption('ja',1);
	$tmp->setSelected($myREX['settings']['syntax_highlight']);
	$_highlight = $tmp->get(); 
?>

<div class="rex-addon-output">

	<h2 class="rex-hl2"><?php echo $rxa[$myself]['i18n']->msg('title_settings'); ?></h2>

<?php
	if (($rxa[$myself]['rexversion'] == '32') or ($rxa[$myself]['rexversion'] == '40') or ($rxa[$myself]['rexversion'] == '41'))
	{
		echo '	<div class="rex-addon-content">';
	}
	else
	{
		echo '	<div class="rex-form">';
	}
?>	
	<form action="index.php" method="post">
	<fieldset class="rex-form-col-1">
	
		<div class="rex-form-wrapper">

			<input type="hidden" name="page" value="<?php echo $myself; ?>" />
			<input type="hidden" name="subpage" value="<?php echo $subpage; ?>" />
			<input type="hidden" name="func" value="savesettings" />

			<div class="rex-form-row rex-form-element-v2">
				<p class="rex-form-col-a rex-form-select">
					<label for="frontend_active"><?php echo $rxa[$myself]['i18n']->msg('label_frontend'); ?></label>
					<?php echo $_frontend; ?>
				</p>
			</div>
			
			<div class="rex-form-row rex-form-element-v2">
				<p class="rex-form-col-a rex-form-select">
					<label for="backend_active"><?php echo $rxa[$myself]['i18n']->msg('label_backend'); ?></label>
					<?php echo $_backend; ?>
				</p>
			</div>
			
			<div class="rex-form-row rex-form-element-v2">
				<p class="rex-form-col-a rex-form-select">
					<label for="syntax_active"><?php echo $rxa[$myself]['i18n']->msg('label_syntax'); ?></label>
					<?php echo $_syntax; ?>
				</p>
			</div>			

			<div class="rex-form-row rex-form-element-v2">
				<p class="rex-form-col-a rex-form-select">
					<label for="syntax_highlight"><?php echo $rxa[$myself]['i18n']->msg('label_highlight'); ?></label>
					<?php echo $_highlight; ?>
				</p>
			</div>			
			
			<div class="rex-form-row rex-form-element-v2">
				<p class="rex-form-col-a rex-form-text">
					<label for="errormail"><?php echo $rxa[$myself]['i18n']->msg('label_errormail'); ?></label>
					<input id="errormail" class="rex-form-text" type="text" name="errormail" value="<?php echo stripslashes($myREX['settings']['errormail']); ?>" />
				</p>
			</div>
			
			<div class="rex-form-row rex-form-element-v2">
				<p class="rex-form-submit">
					<input type="submit" class="rex-form-submit" name="submit" value="<?php echo $rxa[$myself]['i18n']->msg('button_save'); ?>" />
				</p>
			</div>
			
		</div>

	</fieldset>
	</form>
	</div>

</div>
