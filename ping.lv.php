<!DOCTYPE html>
<html lang="lv">
<head>
<meta charset="utf-8">
<meta name="description" content="Ping komandas tests Internet/Intranet datortīklos Linux, iOS, MacOS, Unix, Android, Windows sistēmās.">
<meta name="keywords" content="Pārbaude, Ping">
<meta name="author" content="elithecomputerguy">
<meta property="og:title" content="Ping testācija">
<meta property="og:url" content="https://github.com/elithecomputerguy">
<meta property="og:description" content="Elithecomputerguy prezentē Ping komandas testēšanu Internet/Intranet datortīklos Linux, iOS, MacOS, Unix, Android, Windows sistēmās.">
<meta property="og:image" content="https://avatars.githubusercontent.com/u/60199254?v=4">
<base href="https://127.0.0.1:443/ping.en.php">
<link rel="author" href="https://github.com/elithecomputerguy">
<link rel="license" href="https://creativecommons.org/licenses/by/4.0/">
<link rel="alternate" hreflang="x-default" href="https://127.0.0.1:443/ping.en.php">
<link rel="alternate" hreflang="es-es" href="https://127.0.0.1:443/ping.es.php">
<link rel="alternate" hreflang="uk-ua" href="https://127.0.0.1:443/ping.uk.php">
<link rel="alternate" hreflang="pl-pl" href="https://127.0.0.1:443/ping.pl.php">
<link rel="alternate" hreflang="et-ee" href="https://127.0.0.1:443/ping.et.php">
<link rel="alternate" hreflang="lv-lv" href="https://127.0.0.1:443/ping.lv.php">
<link rel="alternate" hreflang="lt-lt" href="https://127.0.0.1:443/ping.lt.php">
<title>Ping testācija</title>
<style>
div {
	display: inline-block;
	position: relative;
	width: 48%;
	}
div#left {
	float: left;
	margin: 0;
	padding: 0;
	text-align: right;
	}
div#right {
	float: right;
	margin: 0;
	padding: 0;
	text-align: left;
	}
fieldset {
	border: 1px solid gray;
	margin: auto;
	width: 82%;
	}
h1 { 
	text-align: center; 
	}
label, input[type="text"], input[type="submit"] {
	display: inline-block;
	padding: 5px;
	position: relative;
	margin-bottom: 2px;
	}
section {
	border-top: 1px solid gray;
	margin-top: 1px;
	padding-top: 10px;
}
#fm {
	margin-top:22px;
	padding: 5px;
	}
</style>
<script type="text/javascript">
<!-- Tikai JavaScript -->
function determine_end_of_line() {
	var OSName="Unknown OS";
	if (navigator.userAgent.indexOf("Win")!=-1) OSName="Windows";
	if (navigator.userAgent.indexOf("Mac")!=-1) OSName="MacOS";
	if (navigator.userAgent.indexOf("X11")!=-1) OSName="UNIX";
	if (navigator.userAgent.indexOf("Linux")!=-1) OSName="Linux";
	
	/* Pārskatīt navigator.platform maiņas nepieciešamību, i.e.
	* if (navigator.platform.indexOf("Mac") === 0 || navigator.platform === "iPhone") { //something }
	* ...Win32, Win64
	**/
	
	document.getElementById("os").value = OSName;
}
/*
* JS Automātiskais padoms iespējotajai tehnikai no klienta puses, i.e.
* cookie
* var cookieEnabled = (navigator.cookieEnabled) ? true : false;
* java (not JavaScript)
* var javaEnabled = (window.navigator.javaEnabled()) ? true : false;
**/
</script>
</head>
<?php
$delimeter = (PHP_OS_FAMILY == "Windows")? "\\" : "/";
$directory = $_SERVER['DOCUMENT_ROOT']; 
//Mūsu Server App Inventory
/* “nmap” App - ne regulāra portu klauvēšana.
 * Vairumā gadījumu:
 * nav paredzams, ka “nmap” App tiks instalēta pēc noklusējuma.
 * nebūtu labi, ja Seržants būtu kompromitēts.
 * tas nebūtu ideāli, ja lietotāja ievade netiktu pārtraukta validācija un sanitārija.
 * Lai instalētu “nmap” App Linux sistēmās:
 * sudo apt update
 * sudo apt-get install nmap
 **/ 
$nmap_status = false;
$check_app_nmap_c = "nmap -h";
$res = shell_exec($check_app_nmap_c);
if (strpos($res, "https://nmap.org")) {
	$nmap_status = true;
	
	//Izveidot nepieciešamo failu skenēšanas rezultātu glabāšanai
	if (file_exists($directory.$delimeter."scan.XML")) {
		unlink($directory.$delimeter."scan.XML");			
	}
		
	$handle = fopen($directory.$delimeter."scan.XML", "x+");
	fclose($handle);
		
	chmod($directory.$delimeter."scan.XML", 0664);
		
	$user_name = get_current_user();
	chown($directory.$delimeter."scan.XML", $user_name);
}

//Saglabāt atbildes $result 
$result = array();

//Par mūsu klientu (tikai padoma laiks)
$default_content="\n";

if(isset($_POST["os"])) {
		/* Notiek failu sagatavošana klientam (rindas beigu rakstzīme):
		* windows = \r\n <CR><LF> U+000D U+000A
		* linux = \n <LF> U+000A
		* unix = \n <LF> U+000A
		* mac = \r U+000D
		**/
		if($_POST["os"] == "Windows") $default_content= "\r\n";
		if($_POST["os"] == "OSX") $default_content= "\r";
		if($_POST["os"] == "BSD") $default_content= "\n";
		if($_POST["os"] == "MacOS") $default_content= "\n";
		if($_POST["os"] == "UNIX") $default_content= "\n";
		if($_POST["os"] == "Linux") $default_content= "\n";		
}

//Par mūsu serveri (tikai informatīvā)
$system_content="\n";

/* PHP_OS_FAMILY. Tā atgriež virkni vai nu 'Windows', 'BSD', 'OSX', 
* 'Solaris', 'Linux' or 'Unknown'..
**/
if(PHP_OS_FAMILY == "Windows") $system_content="\r\n";
if(PHP_OS_FAMILY == "OSX") $system_content="\r";
if(PHP_OS_FAMILY == "BSD") $system_content="\n";
if(PHP_OS_FAMILY == "Linux") $system_content="\n";
if(PHP_OS_FAMILY == "Solaris") $system_content="\n";
if(PHP_OS_FAMILY == "Unknown") $system_content="\n";


if(isset($_POST["url"]) && !empty($_POST["url"])) {
	$address = $_POST["url"];
	//Automātiskai aizpildīšanai
	$url = htmlentities($address);
	/* paredzēta lietotāja ievades validācija un (vai) 
	 * mērķa robežu definīcija 
	 **/
	$command = "ping -c 1 ".$address;
	
	$result[] = shell_exec($command);
	
	sleep(1);
	
	/* Pašreizējā Nmap skenēšana:
	 * Testēts ar Linux, piemēram, Tikai sistēmas
	 * 
	 * Ja serveris nevar rakstīt dokumentā Document_Root:
	 * chown www-data:root /path_to_document_root/
	 * 
	 * scan.XML direktoriju varēja ievietot Document_Root dziļākajā apakšdirektorijā.
	 **/
	if($nmap_status === True && PHP_OS_FAMILY !== "Windows") {		
		/* paredzēta lietotāja ievades validācija un (vai) 
		 * mērķa robežu definīcija
		**/
		$commandNmap = "nmap -A ".$address." -oN ".$directory.$delimeter."scan.XML";
		shell_exec($commandNmap);
		chmod($directory.$delimeter."scan.XML", 0644);
	}
}
if(isset($_POST["ipfour"]) && !empty($_POST["ipfour"])) {
	$address = $_POST["ipfour"];
	//Automātiskai aizpildīšanai
	$ipfour = htmlentities($address);
	/* Validācija — pārbaude, ja dati atbilst prasībām (jā/nē).
	 * Sanitizācija — nederīgu datu tīrīšana atbilstoši noteiktajām instrukcijām.
	 * Sekošana paraugam rāda tikai validācijas procesu.
	 **/ 
	if (filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
		/* flags: FILTER_FLAG_IPV4,
		* FILTER_FLAG_IPV6
		* FILTER_FLAG_NO_PRIV_RANGE
		* FILTER_FLAG_NO_RES_RANGE
		**/
		$command = "ping -c 1 ".$address;
		
		$result[] = shell_exec($command);
    }
    else {
		$result[] = array('error' => 'Kļūda: norādīts, ka interneta protokola adrese nav IPv4.');
	}
}
?>
<body onload="determine_end_of_line()">
<!-- Header Main Footer -->
<header>
<!-- Logotips un meklēšana -->
<nav><!-- Saite navigācijai --></nav>
</header>
<main>
<nav>
<a href="https://127.0.0.1:443/ping.en.php" hreflang="en-US" target="_SELF">Angļu</a> &nbsp;
<a href="https://127.0.0.1:443/ping.es.php" hreflang="es-es" target="_SELF">Spāņu</a> &nbsp;
<a href="https://127.0.0.1:443/ping.uk.php" hreflang="uk-ua" target="_SELF">Ukraiņu</a> &nbsp;
<a href="https://127.0.0.1:443/ping.pl.php" hreflang="pl-pl" target="_SELF">Poļu</a> &nbsp;
<a href="https://127.0.0.1:443/ping.et.php" hreflang="et-ee" target="_SELF">Igauņu</a> &nbsp;
<a href="https://127.0.0.1:443/ping.lv.php" hreflang="lv-lv" target="_SELF">Latviešu</a> &nbsp;
<a href="https://127.0.0.1:443/ping.lt.php" hreflang="lt-lt" target="_SELF">Lietuviešu</a>
</nav>
<h1>Ping testācija</h1>
<form action="ping.lv.php" method="post" id="fm">
<fieldset>
<legend>Pārbaudīt savienojamību ar neapstiprinātu un Validētu ievadi (priekšgalu)</legend>
<div id="left">
<label for="url" id="url">IP adrese vai domēna nosaukums:</label><input type="text" name="url" id="url" value="
<?php
if(isset($url)) {
		echo $url;
}
?>
">
<label for="ipfour" id="ipfour">Tikai IPv4 adrese:</label><input type="text" name="ipfour" id="ipfour" value="
<?php
if(isset($ipfour)) {
		echo $ipfour;
}
?>
" pattern="[0-9.]+{7,15}">
<!-- html5pattern saraksts pieejams -->
<input type="hidden" name="os" id="os" value="">
</div>
<div id="right">
<input type="submit">
</div>
</fieldset>
</form>
<article>
<section>
<!-- One Section of the Article -->
<?php
if(isset($result) && is_array($result) && !empty($result)) {
	foreach($result as $key => $value) {
	
		if(is_array($value) && array_key_exists('error', $value)) {
?>
<!-- Inline CSS -->
<span style="color: red;">
<?php			
			echo $value['error'];
?>
</span>
<?php
			continue;
		}
		if (strpos($value, "Destination Host Unreachable")) {
				echo $address." is DOWN";
		}
		else {
echo <<<END
Ehotesta testa rezultāti:
{$value}
END;
		}
		
	}
}
else {
?>
Ehotesta testa rezultāti:
<?php		
}
?>
</section>
<section>
<!-- Other Section of the Article -->
<?php
sleep(1);
if (file_exists($directory.$delimeter."scan.XML")) {
	$show_ports = file_get_contents($directory.$delimeter."scan.XML");
	$show_ports = str_replace("\n", "<br>", $show_ports);
echo <<<END
Porta un latentuma testa (nmap) rezultāti:
{$show_ports}
END;
}
else {
?>
Porta un latentuma testa (nmap) rezultāti:
<?php
}
?>
</section>
<section>
<!-- Tips -->
<div style="width:100%; margin:5px; border-right: 5px double gray; text-align: center;">
<?php
$content = str_replace("\r\n", "&frasl;r&frasl;n", $default_content);
$content = str_replace("\r", "&frasl;r", $content);
$content = str_replace("\n", "&frasl;n", $content);
echo <<<END
Trīs padomi ārpus tēmas:
Pašlaik failu lejupielādēm tiks izmantota šī rindiņas rakstzīme: {$content}
END;
?>
</div>
<div style="width:100%; margin:5px; border-left: 5px double gray; text-align: center;">
<blink>Mirgošanas HTML tags ir saistošais zobrats, tāpēc Modernie interneta pārlūki neatbalsta šo funkciju.</blink>
</div>
<div style="width:100%; margin:5px; border-right: 5px double gray; text-align: center;">
Tiem, kam ir/ir vēlme numurēt datortīklu:
Skenējiet internetu/iekštīklu tikai tad, ja jums ir atļauja un (vai) tiesības to darīt.
</div>
</section>
</article>
</main>
<footer>
<!-- Sazinieties ar mums, Par mums, Privātuma politika -->
<address><!-- Vietnes īpašnieka kontaktpersonu informācija --><address>
</footer>
</body>
</html>
