<?php
/**
 * Redaxo Addon: REX_Ajax
 * Version: 1.0, 20.07.2010
 * 
 * Autor: Andreas Eberhard, andreas.eberhard@gmail.com
 *        http://rex.andreaseberhard.de
 */
?>
	
<div class="rex-addon-output">
  <h2 class="rex-hl2"><?php echo $rxa[$myself]['i18n']->msg('title_help'); ?></h2>

  <div class="rex-addon-content">
    <div class="addon_template">
<?php
if (strstr($REX['LANG'],'utf8'))
{
	echo utf8_encode(nl2br(file_get_contents($rxa[$myself]['basedir'].'/lang/'.$REX['LANG'].'.help.txt')));
}
else
{
	echo nl2br(file_get_contents($rxa[$myself]['basedir'].'/lang/'.$REX['LANG'].'.help.txt'));
}
?>
    </div>
  </div>
  
</div>

<div class="rex-addon-output">
  <h2 class="rex-hl2"><?php echo $rxa[$myself]['i18n']->msg('title_changelog'); ?></h2>

  <div class="rex-addon-content">
    <div class="addon_template">
<?php
if (strstr($REX['LANG'],'utf8'))
{
	echo utf8_encode(nl2br(htmlspecialchars(file_get_contents($rxa[$myself]['basedir'].'/changelog.txt'))));
}
else
{
	echo nl2br(htmlspecialchars(file_get_contents($rxa[$myself]['basedir'].'/changelog.txt')));
}
?>
    </div>
  </div>
  
</div>
