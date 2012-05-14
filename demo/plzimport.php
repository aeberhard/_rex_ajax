<?php
/**
 * Postleitzahlen und Ort aus OpenGeoDB importieren
 *
 * Das Programm in das Root-Verzeichnis der REDAXO-Installation kopieren
 * und die Postleitzahlendatei im gleichen Verzeichnis speichern.
 * Dann einfach plzimport.php starten ;-)
 *
 * Daten aus OpenGeoDB
 * http://opengeodb.giswiki.org/wiki/OpenGeoDB_Downloads
 * 
 * Downloads: http://fa-technik.adfc.de/code/opengeodb/
 * Datei für Deutschland: http://fa-technik.adfc.de/code/opengeodb/DE.tab
 *
 */
	
	// REDAXO Config/API/usw. ohne Warnings einbinden
	unset($REX);
	$REX['REDAXO'] = false;
	$REX['GG'] = false;
	$REX['HTDOCS_PATH'] = './';
	include('redaxo/include/master.inc.php');

	// Dateiname der lokalen Postleitzahlen-Datei
	$_plzfilename = $REX['HTDOCS_PATH'] . 'DE.tab';
	
	// Falls allow_url_fopen aktiviert ist kann die Datei auch direkt vom Server verarbeitet werden
	// hierzu einfach die folgende Zeile auskommentieren
	//$_plzfilename = 'http://fa-technik.adfc.de/code/opengeodb/DE.tab';

	// SQL zum anlegen der Tabelle
	$_sql_create = "
	CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%9999_plz` (
	  `satznr` int(11) NOT NULL auto_increment,
	  `plz` varchar(7) NOT NULL default '',
	  `ort` varchar(255) NOT NULL default '',
	  `ascii` varchar(255) NOT NULL default '',
	  `lat` double NOT NULL default '0',
	  `kz` varchar(5) NOT NULL default '',	  
	  `lng` double NOT NULL default '0',
	  `typ` varchar(30) NOT NULL default '',
	  `level` tinyint(1) NOT NULL default '0',
	  PRIMARY KEY  (`satznr`)
	) TYPE=MyISAM AUTO_INCREMENT=1 ;
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
	$_query = 'TRUNCATE TABLE ' . $REX['TABLE_PREFIX'] . '9999_plz';
	$_sql->setQuery($_query);
	if ($_sql->hasError())
	{
		echo "<br />\n" . 'Error Message: ' . htmlspecialchars($_sql->getError());
		echo "<br />\n" . 'Error Code: ' . $_sql->getErrno();
		echo "<br />\n" . 'Query: ' . $_query;
	}
	
	// Eingabe-Datei Postleitzahlen öffnen
	$_fp = fopen($_plzfilename, 'r');
	if (!$_fp)
	{
		echo "<br />\n" . 'Beim öffnen der Datei '.$plzfilename.' ist ein Fehler aufgetreten!';
	}
	else
	{
		$_count = 0;
		while ($_line = fgets($_fp))
		{
			$_values = explode("\t", utf8_encode($_line));

			foreach ($_values as $_k => $_v) 
			{
				$_values[$_k] = trim($_values[$_k]);
			}
			
			if (((string)$_values[0] == (string)(int)$_values[0]) and ($_values[7]<>''))
			{
				$_plzdata = explode(',', trim($_values[7]));
			
				if (count($_plzdata)>0)
				{
					foreach ($_plzdata as $_key => $_plz)
					{
						if (trim($_plz)<>'')
						{			
							$_query = 'INSERT INTO ' . $REX['TABLE_PREFIX'] . '9999_plz' . ' ( satznr, plz, ort, ascii, lat, lng, kz, typ, level ) ';
							$_query .= ' VALUES ( NULL, "' . $_plz . '", "' . $_values[3] . '", "' . $_values[2] . '", "' . $_values[4] . '", "' . $_values[5] . '", "' . $_values[11] . '", "' . $_values[12] . '", "' . $_values[13] . '" );';
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
					}	
				}
			}
		}	
	}
	
	echo "<br />\n" . $_count . ' Datensätze in Tabelle eingefügt!';
