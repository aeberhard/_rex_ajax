<?php
/**
 * Bankleitzahlen importieren
 *
 * Das Programm in das Root-Verzeichnis der REDAXO-Installation kopieren
 * und die Bankleitzahlendatei im gleichen Verzeichnis unter blz.txt speichern.
 * Dann einfach blzimport.php starten ;-)
 *
 * Daten im Web
 * http://www.bundesbank.de/zahlungsverkehr/zahlungsverkehr_bankleitzahlen_download.php
 * hier die ungepackte Textdatei zum Download auswählen
 *
 */
	
	// REDAXO Config/API/usw. ohne Warnings einbinden
	unset($REX);
	$REX['REDAXO'] = false;
	$REX['GG'] = false;
	$REX['HTDOCS_PATH'] = './';
	include('redaxo/include/master.inc.php');

	// Dateiname der lokalen Bankleitzahlen-Datei
	$_blzfilename = $REX['HTDOCS_PATH'] . 'blz.txt';
	
	// Falls allow_url_fopen aktiviert ist kann die Datei auch direkt vom Server verarbeitet werden
	// hierzu einfach die folgende Zeile auskommentieren
	//$_blzfilename = 'http://www.bundesbank.de/download/zahlungsverkehr/bankleitzahlen/20100906/blz_20100906.txt';

	// SQL zum anlegen der Tabelle
	$_sql_create = "
	CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%9999_blz` (
	  `blz` int(8) NOT NULL default '0',
	  `merkmal` char(1) NOT NULL default '',
	  `bezeichnung` varchar(58) NOT NULL default '',
	  `plz` varchar(5) NOT NULL default '',
	  `ort` varchar(35) NOT NULL default '',
	  `kurzbezeichnung` varchar(27) NOT NULL default '',
	  `pan` varchar(5) NOT NULL default '',
	  `bic` varchar(11) NOT NULL default '',
	  `prz` char(2) NOT NULL default '',
	  `satznr` int(6) NOT NULL default '0',
	  `aendkz` char(1) NOT NULL default '',
	  `blzloesch` char(1) NOT NULL default '',
	  `blznachfolge` int(8) NOT NULL default '0',
	  PRIMARY KEY  (`satznr`)
	) TYPE=MyISAM;
	";

	// Tabelle anlegen
	$_sql = new rex_sql();
	$_sql->debugsql = false;

	$_sql_create = str_replace('%TABLE_PREFIX%', $REX['TABLE_PREFIX'], $_sql_create);
	$_sql->setQuery($_sql_create);
	if ($_sql->hasError())
	{
		echo "<br />\n" . 'Error Message: ' . htmlspecialchars($_sql->getError());
		echo "<br />\n" . 'Error Code: ' . $_sql->getErrno();
		echo "<br />\n" . 'Query: ' . $_sql_create;
	}
	
	// Tabelle leeren falls schon vorhanden
	$_query = 'TRUNCATE TABLE ' . $REX['TABLE_PREFIX'] . '9999_blz';
	$_sql->setQuery($_query);
	if ($_sql->hasError())
	{
		echo "<br />\n" . 'Error Message: ' . htmlspecialchars($_sql->getError());
		echo "<br />\n" . 'Error Code: ' . $_sql->getErrno();
		echo "<br />\n" . 'Query: ' . $_query;
	}
	
	// Eingabe-Datei Postleitzahlen öffnen
	$_fp = fopen($_blzfilename, 'r');
	if (!$_fp)
	{
		echo "<br />\n" . 'Beim öffnen der Datei '.$blzfilename.' ist ein Fehler aufgetreten!';
	}
	else
	{
		$_count = 0;
		
		while ($line = fgets($_fp))
		{
			$data['blz'] = substr($line, 0, 8);
			$data['merkmal'] = substr($line, 8, 1);
			$data['bezeichnung'] = substr($line, 9, 58);
			$data['plz'] = substr($line, 67, 5);
			$data['ort'] = substr($line, 72, 35);
			$data['kurzbezeichnung'] = substr($line, 107, 27);
			$data['pan'] = substr($line, 134, 5);
			$data['bic'] = substr($line, 139, 11);
			$data['prz'] = substr($line, 150, 2);
			$data['satznr'] = substr($line, 152, 6);
			$data['aendkz'] = substr($line, 158, 1);
			$data['blzloesch'] = substr($line, 159, 1);
			$data['blznachfolge'] = substr($line, 160, 8);

			$fieldnames = '';
			$fieldvalues = '';
			foreach ($data as $key => $value)
			{
				if ($fieldnames <> '') $fieldnames .= ',';
				if ($fieldvalues <> '') $fieldvalues .= ',';
				$fieldnames .= ' `' . $key . '` ';
				$fieldvalues .= ' "' . utf8_encode($value) . '" ';
			}
			$fieldnames = trim($fieldnames);
			$fieldvalues = trim($fieldvalues);
						
			$_query = 'INSERT INTO ' . $REX['TABLE_PREFIX'].'9999_blz' . ' ( ' . $fieldnames .' ) VALUES ( ' . $fieldvalues . ' );';
			//echo '<br>'.$_query ;
			$_sql->setQuery($_query);
			if ($_sql->hasError())
			{
				echo "<br />\n" . 'Error Message: ' . htmlspecialchars($_sql->getError());
				echo "<br />\n" . 'Error Code: ' . $_sql->getErrno();
				echo "<br />\n" . 'Query: ' . $_query;
				echo "<pre>"; var_dump($_values); echo "</pre>";
				die;
			} 
			else 
			{
				$_count++;
			}
		}
		echo "<br />\n" . $_count . ' Datensätze in Tabelle eingefügt!';
	}
