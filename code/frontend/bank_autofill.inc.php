<?php // title: Autofill Bankname bei Eingabe von Bankleitzahlen ?>
<?php
/**
 * Zugriff fuer Autocomplete Bankname bei Eingabe von Bankleitzahlen
 */
 
	// Query
	$_q = rex_request('q', 'string');

	// Zugriff auf Bankleitzahlentabelle
	$_sql = new rex_sql();
	$_result = $_sql->setQuery('SELECT blz, bezeichnung FROM ' . $REX['TABLE_PREFIX'] . '9999_blz WHERE merkmal = "1" AND blz LIKE "' . mysql_escape_string($_q) . '%" ORDER BY blz ASC LIMIT 50 ');
	
	for ($i=0; $i < $_sql->getRows(); $i++)
	{
		echo $_sql->getValue('blz') . '|' . htmlspecialchars($_sql->getValue('bezeichnung')) . "\n";
		$_sql->next();
	}	