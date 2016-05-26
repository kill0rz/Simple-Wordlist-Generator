<html>
<head>
<title>Simple Wordlist Generator v1</title>
</head>
<body>
<form method="post" action="">
Zeichenpool ausw‰hlen:<br />
<input type="checkbox" name="lowercase" value="true"> kleine Buchstaben <br />
<input type="checkbox" name="uppercase" value="true"> groﬂe Buchstaben <br />
<input type="checkbox" name="numbers" value="true"> Zahlen <br />
<input type="checkbox" name="spcialchars" value="true"> Sonderzeichen <br />
<input type="checkbox" name="userdefined" value="true"> Benutzerdefiniert (eins pro Zeile): <br />
<textarea name="userdefined_data" rows=10 cols=50></textarea> <br /> <br />


L&auml;nge: <input type="text" name="length" value="5" size="2"><br /> <br />

<input type="submit" />
</form>
<?php
ini_set('memory_limit', '1G');

$handle = opendir("tmp/");
while ($datei = readdir ($handle)) {
	if($datei != "." and $datei != ".." and $datei != "index.php") unlink("tmp/".$datei);
}

$charpool = array();

if(isset($_POST['lowercase']) and trim($_POST['lowercase']) == "true"){
	$charpool = array_merge($charpool,array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","v","w","x","y","z","ﬂ","‰","¸","ˆ"));
}
if(isset($_POST['uppercase']) and trim($_POST['uppercase']) == "true"){
	$charpool = array_merge($charpool,array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","ƒ","÷","‹"));
}
if(isset($_POST['numbers']) and trim($_POST['numbers']) == "true"){
	$charpool = array_merge($charpool,array(0,1,2,3,4,5,6,7,8,9));
}
if(isset($_POST['spcialchars']) and trim($_POST['spcialchars']) == "true"){
	$charpool = array_merge($charpool,array("!","ß","$","%","&","/","(",")","=","?","*","'","-",":",";",",",".","-","#","+","¥","`","\\","}","]","[","{","^","∞","@","µ","|",'"',"~"));
}
if(isset($_POST['userdefined']) and trim($_POST['userdefined']) == "true" and isset($_POST['userdefined_data']) and trim($_POST['userdefined_data']) != ""){
	$usercharpool = array_filter(array_map("trim",explode("\n",$_POST['userdefined_data'])),'strlen');
	$charpool = array_merge($charpool,$usercharpool);
}

if(!count($charpool) > 0) die();
natsort($charpool);
$charpool = array_unique($charpool);
if(isset($_POST['length'])) $length = intval($_POST['length']);
else die();

$anzahlaller = pow(count($charpool),$length);
$endarray = array_fill(0, $anzahlaller, "");

for($i = $length-1; $i >= 0; $i--){
	$sprungzaehler = pow(count($charpool), $i);
	$argumentzaehler = 0;
	$argumentenzaehler = 0;
	for($j = 0; $j < count($endarray); $j++){
		if($argumentzaehler < $sprungzaehler){
			$argumentzaehler++;
		}else{
			$argumentzaehler = 1;
			$argumentenzaehler++;
			if(!isset($charpool[$argumentenzaehler])){
				$argumentenzaehler = 0;
			}
		}
		$endarray[$j] .= $charpool[$argumentenzaehler];
	}
}

$time = time();

file_put_contents("tmp/".$time.".txt","");
foreach($endarray as $l){
	file_put_contents("tmp/".$time.".txt",$l."\n",FILE_APPEND);
}

echo "<h2> Es wurden ".count($endarray)." Kombinationen generiert.</h2>";
echo "<a href='tmp/".$time.".txt'>DOWNLOAD</a>";