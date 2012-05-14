<?php // title: REDAXO-Artikel mit Artikel-ID bereitstellen ?>
<?php
/**
 * Beispielmodul Artikel mit Artikel-ID bereitstellen
 */

	// Include der Textile-Klasse
	include ($REX['INCLUDE_PATH'] . '/addons/textile/config.inc.php');

	// Artikel-ID
	$artid = rex_request('article_id', 'int');

	$content = '';

	// Artikel bereitstellen
	if ($artid <> 0)
	{
		if (class_exists('article'))
		{
			$art = new article;
		}
		else
		{
			$art = new rex_article;
		}
		if ($art->setArticleID($artid))
		{
			$content = $art->getArticle('1'); 
		}
	}

	// Ausgabe, evtl. mit Header / Fehlermeldung
	header('Content-Type: text/html; charset=utf-8'); 
	if (trim($content) == '')
	{
		echo 'Artikel nicht gefunden!';
	}
	else
	{
		echo $content;
	}