<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
 <STYLE type="text/css">
   A {text-decoration: none}
   SPAN.ko {color: #006010; font-weight: bold; font-size: larger}
 </STYLE>
</HEAD>
<BODY>

<?php
$enwp = "https://en.wikipedia.org/";
$kowp = "https://ko.wikipedia.org/wiki/";

$input ="";


if ($_GET["input"]) {

$input = $_GET["input"];

} 

$entree = preg_replace("/ /","_",$input);

$url = $enwp."wiki/".$entree;

$page = file_get_contents($url);

$pat = '|interwiki-ko.+a href.+ko.wikipedia.org.wiki.([0-9A-Z%_]+)" title..([^"]*) –.+|';  // pattern to retreive Korean link

if ($page) {

echo "<h2> $input : ";

if (preg_match_all($pat,$page,$matches)) {

  echo "{$matches[2][0]}";
  echo " [<a href=\"$kowp{$matches[1][0]}\">→</a>]</h2>\n";

} else {

  echo "Pas de lien →ko pour $url\n</h2> ";
  exit; 
}

echo "<p>\n";

if (preg_match_all('|<a href../wiki/([^"]+)"[^>]*>([A-Z][ \w\-]+)</a>|',$page,$liens)) {  // collects links in the page


  for ($i = 0, $size = sizeof($liens[1]); $i < $size; ++$i)
    {
      $u = $liens[1][$i];
      $lienw = $enwp."wiki/".$u;
      if (!$done[$u]) {
	$pagew = file_get_contents($lienw);
	if (preg_match($pat,$pagew,$matches)) {
	  $nextu = http_build_query(array('input'=>$liens[2][$i]));
	  echo "<a href=\"en2ko.php?$nextu\" target=\"_blank\">{$liens[2][$i]}</a> : ";
	  echo "<SPAN class=\"ko\">{$matches[2]}</SPAN>";  // résultat ko
	  echo " [<a href=\"$kowp{$matches[1]}\">→</a>]<p>\n";
	
	} 
	//	else {  echo "{$liens[2][$i]} : pas trouvé<p>\n";}
	$done[$u] = TRUE ;
      }
      //      echo "$liens[$i][1]<p>\n";
    }

} else {
  echo "pas trouvé"; }

} else { echo "Page <a href=\"$url\">$url</a> non trouvée<p>"; } 

?>

</BODY>
