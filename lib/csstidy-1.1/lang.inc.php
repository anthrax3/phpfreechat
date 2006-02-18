<?php
if(isset($_GET['lang']))
{
	$l = $_GET['lang'];
}
else
{
	$l = getenv('HTTP_ACCEPT_LANGUAGE');
	$l = strtolower(substr($l,0,2));
}
$l = ($l == 'de' || $l == 'fr') ? $l : 'en';

$lang = array();
$lang['en'][0] = 'CSS Formatter and Optimiser/Optimizer (based on CSSTidy ';
$lang['de'][0] = 'CSS Formatierer und Optimierer (basierend auf CSSTidy ';
$lang['en'][1] = 'CSS Formatter and Optimiser';
$lang['de'][1] = 'CSS Formatierer und Optimierer';
$lang['en'][2] = '(based on';
$lang['de'][2] = '(basierend auf';
$lang['en'][3] = '(plaintext)';
$lang['de'][3] = '(Textversion)';
$lang['en'][4] = 'Important Notes:';
$lang['de'][4] = 'Wichtige Hinweise:';
$lang['en'][5] = 'the same selectors and properties are automatically merged';
$lang['de'][5] = 'die gleichen Selektoren und Eigenschaften werden automatisch zusammengefasst';
$lang['en'][6] = 'your code should be well-formed. This is <strong>no validator</strong> which points out errors in your CSS code. To make sure that your code is valid, use the <a href="http://jigsaw.w3.org/css-validator/">W3C Validator</a>.';
$lang['de'][6] = 'der CSS Code sollte wohlgeformt sein. Der CSS Code wird <strong>nicht auf Gültigkeit überprüft</strong>. Um sicherzugehen dass dein Code valide ist, benutze den <a href="http://jigsaw.w3.org/css-validator/">W3C Validierungsservice</a>.';
$lang['en'][7] = 'all comments are removed';
$lang['de'][7] = 'alle Kommentare werden entfernt';
$lang['en'][8] = 'CSS Input:';
$lang['de'][8] = 'CSS Eingabe:';        
$lang['en'][9] = 'CSS-Code:';
$lang['de'][9] = 'CSS-Code:';         
$lang['en'][10] = 'CSS from URL:';
$lang['de'][10] = 'CSS von URL:';      
$lang['en'][11] = 'Code Layout:';
$lang['de'][11] = 'Code Layout:';             
$lang['en'][12] = 'Compression&nbsp;(code&nbsp;layout):';
$lang['de'][12] = 'Komprimierung&nbsp;(Code&nbsp;Layout):';           
$lang['en'][13] = 'Highest (no readability, smallest size)';
$lang['de'][13] = 'Höchste (keine Lesbarkeit, niedrigste Größe)';    
$lang['en'][14] = 'High (moderate readability, smaller size)';
$lang['de'][14] = 'Hoch (mittelmäßige Lesbarkeit, geringe Größe)';      
$lang['en'][15] = 'Standard (balance between readability and size)';
$lang['de'][15] = 'Standard (Kompromiss zwischen Lesbarkeit und Größe)';
$lang['en'][16] = 'Low (higher readability)';
$lang['de'][16] = 'Niedrig (höhere Lesbarkeit)';   
$lang['en'][17] = 'Custom (enter below)';
$lang['de'][17] = 'Benutzerdefiniert (unten eingeben)';      
$lang['en'][18] = 'Custom <a href="http://csstidy.sourceforge.net/templates.php">template</a>';
$lang['de'][18] = 'Benutzerdefinierte <a href="http://csstidy.sourceforge.net/templates.php">Vorlage</a>';   
$lang['en'][19] = 'Options';
$lang['de'][19] = 'Optionen';   
$lang['en'][20] = 'Sort Selectors (caution)';
$lang['de'][20] = 'Selektoren sortieren (Vorsicht)';     
$lang['en'][21] = 'Sort Properties';
$lang['de'][21] = 'Eigenschaften sortieren';      
$lang['en'][22] = 'Optimise selectors and their properties';
$lang['de'][22] = 'Selektoren und Eigenschaften optimieren';        
$lang['en'][23] = 'Merge shorthand properties';
$lang['de'][23] = 'Eigenschaften zusammenfassen (margin,...)';     
$lang['en'][24] = 'Compress colors';
$lang['de'][24] = 'Farben komprimieren';       
$lang['en'][25] = 'Lowercase selectors';
$lang['de'][25] = 'Selektoren in Kleinbuchstaben';       
$lang['en'][26] = 'Case for properties:';
$lang['de'][26] = 'Groß-/Kleinschreibung für Eigenschaften';         
$lang['en'][27] = 'Lowercase';
$lang['de'][27] = 'Kleinbuchstaben';     
$lang['en'][28] = 'No or invalid CSS input or wrong URL!'; 
$lang['de'][28] = 'Keine oder ungültige CSS Eingabe oder falsche URL!';
$lang['en'][29] = 'Uppercase';
$lang['de'][29] = 'Großbuchstaben';  
$lang['en'][30] = 'lowercase elementnames needed for XHTML';
$lang['de'][30] = 'kleingeschriebene Elementnamen benötigt für XHTML';   
$lang['en'][31] = 'Remove unnecessary backslashes';
$lang['de'][31] = 'Unnötige Backslashes entfernen'; 
$lang['en'][32] = 'convert !important-hack';
$lang['de'][32] = '!important-Hack konvertieren';
$lang['en'][33] = 'Output as file';
$lang['de'][33] = 'Als Datei ausgeben';
$lang['en'][34] = 'Bigger compression because of smaller newlines (copy &amp; paste doesn\'t work)';
$lang['de'][34] = 'Größere Komprimierung augrund von kleineren Neuezeile-Zeichen';    
$lang['en'][35] = 'Process CSS';
$lang['de'][35] = 'CSS verarbeiten';    
$lang['en'][36] = 'Compression Ratio';
$lang['de'][36] = 'Komprimierungsrate';   
$lang['en'][37] = 'Input';
$lang['de'][37] = 'Eingabe';    
$lang['en'][38] = 'Output';
$lang['de'][38] = 'Ausgabe';
$lang['en'][39] = 'Language';
$lang['de'][39] = 'Sprache';
$lang['en'][41] = 'Attention: This may change the behaviour of your CSS Code!';
$lang['de'][41] = 'Achtung: Dies könnte das Verhalten ihres CSS-Codes verändern!';
$lang['en'][42] = 'Remove last ;';
$lang['de'][42] = 'Letztes ; entfernen';
$lang['en'][43] = 'Discard invalid properties';
$lang['de'][43] = 'Ungültige Eigenschaften entfernen';
$lang['en'][44] = 'Only safe optimisations';
$lang['de'][44] = 'Nur sichere Optimierungen';
$lang['en'][45] = 'Compress font-weight';
$lang['de'][45] = 'font-weight komprimieren';
$lang['en'][46] = 'Save comments';
$lang['de'][46] = 'Kommentare beibehalten';
$lang['en'][47] = 'Do not change anything';
$lang['en'][48] = 'Only seperate selectors (split at ,)';
$lang['en'][49] = 'Merge selectors with the same properties (fast)';
$lang['en'][50] = 'Merge selectors intelligently (slow)';
$lang['de'][47] = 'Nichts ändern';
$lang['de'][48] = 'Selektoren nur trennen (am Komma)';
$lang['de'][49] = 'Selektoren mit gleichen Eigenschaften zusammenfassen (schnell)';
$lang['de'][50] = 'Selektoren intelligent zusammenfassen (langsam!)';

$lang['fr'][0] = 'CSS Formatteur et Optimiseur (basé sur CSSTidy  ';
$lang['fr'][1] = 'CSS Formatteur et Optimiseur';
$lang['fr'][2] = '(basé sur ';
$lang['fr'][3] = '(Version Text)';
$lang['fr'][4] = 'notes Importantes:';
$lang['fr'][5] = 'les mêmes sélecteurs et les propriétés sont automatiquement fusionnés';
$lang['fr'][6] = 'votre code doit être Valide. Ce n\'est <strong>pas un validateur</strong> qui signale des erreurs dans votre code de CSS. Assurez-vous que votre code est correct en utilisant le le validateur : <a href="http://jigsaw.w3.org/css-validator/">W3C Validator</a>';
$lang['fr'][7] = 'tous les commentaires sont enlevés';
$lang['fr'][8] = 'Champ CSS:';
$lang['fr'][9] = 'CSS-Code:';
$lang['fr'][10] = 'CSS en provenance d\'une URL:<br />';
$lang['fr'][11] = 'Mise en page du code:';
$lang['fr'][12] = 'Compression (Mise en page du code):';
$lang['fr'][13] = 'Le plus compact (aucune lisibilité, plus petite taille)';
$lang['fr'][14] = 'Trés compact (lisibilité modérée, plus petite taille)';
$lang['fr'][15] = 'Normale (équilibre entre la lisibilité et la taille)';
$lang['fr'][16] = 'Peu compact (une lisibilité plus élevée)';
$lang['fr'][17] = 'Sur mesure (entrer ci-dessous)';
$lang['fr'][18] = 'Sur mesure <a href="http://csstidy.sourceforge.net/templates.php">Gabarit</a>';
$lang['fr'][19] = 'Options';
$lang['fr'][20] = 'Trier les sélecteurs (attention)';
$lang['fr'][21] = 'Trier les propriétés';
$lang['fr'][22] = 'Fusionner les sélecteurs avec les mêmes propriétés';
$lang['fr'][23] = 'Fusionner les propriétés (sténographie)';
$lang['fr'][24] = 'Compresser les couleurs';
$lang['fr'][25] = 'Sélecteurs en minuscules';
$lang['fr'][26] = 'Cases (Minuscules &amp; Majuscule) pour les propriétés:';
$lang['fr'][27] = 'Minuscules';
$lang['fr'][28] = 'CSS non valide ou URL incorrecte!';
$lang['fr'][29] = 'Majuscule';
$lang['fr'][30] = 'les noms des éléments en minuscules (indispensables pour XHTML)';
$lang['fr'][31] = 'enlever les antislashs inutiles';
$lang['fr'][32] = 'convertir !important-hack';
$lang['fr'][33] = 'Sauver en tant que fichier';
$lang['fr'][34] = 'Une plus grande compression en raison de plus petits caractères';
$lang['fr'][35] = 'Compresser le CSS';
$lang['fr'][36] = 'Facteur de Compression';
$lang['fr'][37] = 'Entrée';
$lang['fr'][38] = 'Sortie';
$lang['fr'][39] = 'Language';
$lang['fr'][41] = 'Attention :  ceci peut changer le comportement de votre code de CSS !';
$lang['fr'][42] = 'Enlever le dernier ;';
$lang['fr'][43] = 'Supprimer les propriétés non valide';
$lang['fr'][44] = 'Seulement les optimisations sûres';
$lang['fr'][45] = 'Compresser font-weight';
$lang['fr'][46] = 'Sauvegarder les commentaires ';
$lang['fr'][47] = 'Ne changer rien';
$lang['fr'][48] = 'Sépare les sélecteurs (sépare au niveau de ,)';
$lang['fr'][49] = 'Fusionne les sélecteurs avec les mêmes propriétés (rapides)';
$lang['fr'][50] = 'Fusionne les sélecteurs intelligemment (lent)';
?>