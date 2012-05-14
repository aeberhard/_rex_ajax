<?php // title: Autofill Ortsname bei Eingabe von Postleitzahlen ?>
<?php
/**
 * Zugriff fuer Autocomplete Ort bei Eingabe von Postleitzahlen
 */

	// Query
	$_q = rex_request('q', 'string');
	
	// Zugriff auf Postleitzahlentabelle
	$_sql = new rex_sql();
	$_result = $_sql->setQuery('SELECT plz, ort FROM ' . $REX['TABLE_PREFIX'] . '9999_plz WHERE level = 6 AND plz LIKE "' . mysql_escape_string($_q) . '%" ORDER BY plz ASC LIMIT 50 ');
	
	for ($i=0; $i < $_sql->getRows(); $i++)
	{
		echo $_sql->getValue('plz') . '|' . htmlspecialchars($_sql->getValue('ort')) . "\n";
		$_sql->next();
	}