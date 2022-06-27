<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="utf-8">
<meta name="description" content="Test polecenia ping w sieciach komputerowych Internetu/Intranetu w systemach Linux, iOS, MacOS, Unix, Android, Windows.">
<meta name="keywords" content="Testowanie, Ping">
<meta name="author" content="elithecomputerguy">
<meta property="og:title" content="Testowanie Pinga">
<meta property="og:url" content="https://github.com/elithecomputerguy">
<meta property="og:description" content="Elithecomputerguy prezentuje test polecenia ping w sieciach komputerowych Internetu/Intranetu w systemach Linux, iOS, MacOS, Unix, Android, Windows.">
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
<title>Testowanie Pinga</title>
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
<!-- Tylko JavaScript -->
function determine_end_of_line() {
	var OSName="Unknown OS";
	if (navigator.userAgent.indexOf("Win")!=-1) OSName="Windows";
	if (navigator.userAgent.indexOf("Mac")!=-1) OSName="MacOS";
	if (navigator.userAgent.indexOf("X11")!=-1) OSName="UNIX";
	if (navigator.userAgent.indexOf("Linux")!=-1) OSName="Linux";
	
	/* Sprawdź potrzebę zamiany na navigator.platform, i.e.
	* if (navigator.platform.indexOf("Mac") === 0 || navigator.platform === "iPhone") { //something }
	* ...Win32, Win64
	**/
	
	document.getElementById("os").value = OSName;
}
/*
* JS Auto Wskazówka dla włączonej technologii od strony klienta, i.e.
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
//Nasz spis aplikacji serwera
/* aplikacja „nmap” - nie jest zwykłym knockerem portów.
 * W większości przypadków:
 * nie oczekuje się, że aplikacja „nmap” zostanie zainstalowana domyślnie.
 * nie byłoby dobrze, gdyby serwer został naruszony.
 * nie byłoby idealnie, gdyby dane użytkownika nie zostały poddane
 * walidacja i sanityzacja.
 * Aby zainstalować aplikację „nmap” w systemach:
 * sudo apt update
 * sudo apt-get install nmap
 **/ 
$nmap_status = false;
$check_app_nmap_c = "nmap -h";
$res = shell_exec($check_app_nmap_c);
if (strpos($res, "https://nmap.org")) {
	$nmap_status = true;
	
	//Utwórz plik potrzebny do przechowywania wyników skanowania
	if (file_exists($directory.$delimeter."scan.XML")) {
		unlink($directory.$delimeter."scan.XML");			
	}
		
	$handle = fopen($directory.$delimeter."scan.XML", "x+");
	fclose($handle);
		
	chmod($directory.$delimeter."scan.XML", 0664);
		
	$user_name = get_current_user();
	chown($directory.$delimeter."scan.XML", $user_name);
}

//Odpowiedzi sklepu w $result 
$result = array();

//O naszym kliencie (tylko wskazówkę czas)
$default_content="\n";

if(isset($_POST["os"])) {
		/* Przygotowywanie plików dla klienta (znak końca wiersza):
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

//Informacje o naszym serwerze (tylko informacyjne)
$system_content="\n";

/* PHP_OS_FAMILY. Zwraca ciąg dowolny z 'Windows', 'BSD', 'OSX', 
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
	//Do automatycznego wypełniania
	$url = htmlentities($address);
	/* oczekiwano sprawdzenia poprawności danych wejściowych użytkownika 
	 * i (lub) określenia granic docelowych
	 **/
	$command = "ping -c 1 ".$address;
	
	$result[] = shell_exec($command);
	
	sleep(1);
	
	/* Bieżące skanowanie Nmap:
	 * Testowane wyłącznie z systemem Linux Like Systems
	 * 
	 * Jeśli serwer nie może zapisać do katalogu Document_Root:
	 * chown www-data:root /path_to_document_root/
	 * 
	 * katalog z scan.XML można umieścić w głębszym podkatalogu Document_Root.
	 **/
	if($nmap_status === True && PHP_OS_FAMILY !== "Windows") {		
		/* oczekiwano sprawdzenia poprawności danych wejściowych użytkownika 
		 * i (lub) określenia granic docelowych
		**/
		$commandNmap = "nmap -A ".$address." -oN ".$directory.$delimeter."scan.XML";
		shell_exec($commandNmap);
		chmod($directory.$delimeter."scan.XML", 0644);
	}
}
if(isset($_POST["ipfour"]) && !empty($_POST["ipfour"])) {
	$address = $_POST["ipfour"];
	//Do automatycznego wypełniania
	$ipfour = htmlentities($address);
	/* Walidacja - sprawdzanie, czy dane spełniają wymagania (tak/nie).
	 * Sanityzacja - czyszczenie nieprawidłowych danych zgodnie z 
	 * określonymi instrukcjami.
	 * Poniższa próbka pokazuje tylko proces sprawdzania poprawności.
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
		$result[] = array('error' => 'Błąd: podany adres protokołu internetowego nie jest adresem IPv4.');
	}
}
?>
<body onload="determine_end_of_line()">
<!-- Header Main Footer -->
<header>
<!-- Logo i Szukaj -->
<nav><!-- Łącze do nawigacji --></nav>
</header>
<main>
<nav>
<a href="https://127.0.0.1:443/ping.en.php" hreflang="en-US" target="_SELF">Angielski</a> &nbsp;
<a href="https://127.0.0.1:443/ping.es.php" hreflang="es-es" target="_SELF">Hiszpański</a> &nbsp;
<a href="https://127.0.0.1:443/ping.uk.php" hreflang="uk-ua" target="_SELF">Ukraiński</a> &nbsp;
<a href="https://127.0.0.1:443/ping.pl.php" hreflang="pl-pl" target="_SELF">Polski</a> &nbsp;
<a href="https://127.0.0.1:443/ping.et.php" hreflang="et-ee" target="_SELF">Estoński</a> &nbsp;
<a href="https://127.0.0.1:443/ping.lv.php" hreflang="lv-lv" target="_SELF">Łotwa</a> &nbsp;
<a href="https://127.0.0.1:443/ping.lt.php" hreflang="lt-lt" target="_SELF">Litewski</a>
</nav>
<h1>Testowanie Pinga</h1>
<form action="ping.pl.php" method="post" id="fm">
<fieldset>
<legend>Sprawdź łączność z niesprawdzonym i zweryfikowanym wejściem (front-end)</legend>
<div id="left">
<label for="url" id="url">Adres IP lub nazwa domeny:</label><input type="text" name="url" id="url" value="
<?php
if(isset($url)) {
		echo $url;
}
?>
">
<label for="ipfour" id="ipfour">Tylko adres IPv4:</label><input type="text" name="ipfour" id="ipfour" value="
<?php
if(isset($ipfour)) {
		echo $ipfour;
}
?>
" pattern="[0-9.]+{7,15}">
<!-- html5pattern lista dostępna -->
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
Wyniki testu ping:
{$value}
END;
		}
		
	}
}
else {
?>
Wyniki testu ping:
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
Wyniki testu portowego i opóźnienia (nmap):
{$show_ports}
END;
}
else {
?>
Wyniki testu portowego i opóźnienia (nmap):
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
Trzy porady off-topic dotyczące:
Obecnie do pobierania plików będzie używany ten znak końca wiersza: {$content}
END;
?>
</div>
<div style="width:100%; margin:5px; border-left: 5px double gray; text-align: center;">
<blink>Tag HTML mrugnięcia jest bronią oślepiającą, więc współczesne przeglądarki internetowe nie obsługują tej funkcji.</blink>
</div>
<div style="width:100%; margin:5px; border-right: 5px double gray; text-align: center;">
Dla tych, którzy mają/chcą wyliczyć sieć komputerową:
Skanuj Internet/Intranet tylko wtedy, gdy masz do tego uprawnienie i (lub) uprawnienia.
</div>
</section>
</article>
</main>
<footer>
<!-- Skontaktuj się z nami, o nas, Polityka prywatności -->
<address><!-- Informacje kontaktowe właściciela witryny --><address>
</footer>
</body>
</html>
