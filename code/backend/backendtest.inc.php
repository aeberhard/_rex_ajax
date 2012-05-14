<?php // title: Test für das Backend ?>
<?php

	// Zugriff auf Usertabelle
	$_sql = new rex_sql();
	$_result = $_sql->setQuery('SELECT login,name,description,session_id FROM ' . $REX['TABLE_PREFIX'] . 'user WHERE session_id <> "" ORDER BY login ASC ');
	
	echo '<h1>Angemeldete Benutzer</h1>';
	echo '<ul>';
	for ($i=0; $i < $_sql->getRows(); $i++)
	{
		echo '<li>' . $_sql->getValue('login') . ': ' . $_sql->getValue('name') . ' ' . $_sql->getValue('description') . '</li>';
		$_sql->next();
	}
	echo '</ul>';