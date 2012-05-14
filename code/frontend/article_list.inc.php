<?php // title: Liste aller REDAXO-Artikel einer Kategorie ?>
<?php
/**
 * Beispielmodul Liste aller REDAXO-Artikel einer Kategorie
 */

	// Artikel-ID
	$catid = rex_request('cat', 'int');

	$content = '';

	if ($catid <> 0)
	{
		$cat = OOCategory::getCategoryById($catid);
		if ($cat)
		{
			$article = $cat->getArticles();
			if (is_array($article))
			{
				$content = '<ul>';
				foreach ($article as $var) 
				{
					$articleId = $var->getId();
					$articleName = $var->getName();
					$articleDescription = $var->getDescription();
					if (!$var->isStartpage() and $var->isOnline()) 
					{
						$content .= '<li><a href="'.rex_getUrl($articleId).'" class="faq">'.$articleName.'</a></li>';
					}
				}
				$content .= '</ul>';
			}
		}
	}	

	// Ausgabe, evtl. mit Header / Fehlermeldung
	header('Content-Type: text/html; charset=utf-8'); 
	if ((trim($content) == '') or (trim($content) == '<ul></ul>'))
	{
		echo 'Keine Artikel gefunden!';
	}
	else
	{
		echo $content;
	}