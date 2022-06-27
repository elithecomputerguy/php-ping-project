<!DOCTYPE html>
<html lang="uk">
<head>
<meta charset="utf-8">
<meta name="description" content="Перевірка команди Ping в компютерних мережах Інтернету/Iнтрамережі в системах Linux, iOS, MacOS, Unix, Android, Windows.">
<meta name="keywords" content="Тестування, Пінг">
<meta name="author" content="elithecomputerguy">
<meta property="og:title" content="Перевірка Пінгу">
<meta property="og:url" content="https://github.com/elithecomputerguy">
<meta property="og:description" content="Elithecomputerguy представляє тест команди Ping в комп 'ютерних мережах Інтернету/інтрамережі в системах Linux, iOS, MacOS, Unix, Android, Windows.">
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
<title>Перевірка Пінгу</title>
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
<!-- Лише JavaScript -->
function determine_end_of_line() {
	var OSName="Unknown OS";
	if (navigator.userAgent.indexOf("Win")!=-1) OSName="Windows";
	if (navigator.userAgent.indexOf("Mac")!=-1) OSName="MacOS";
	if (navigator.userAgent.indexOf("X11")!=-1) OSName="UNIX";
	if (navigator.userAgent.indexOf("Linux")!=-1) OSName="Linux";
	
	/* Перегляньте необхідність заміни navigator.platform, i.e.
	* if (navigator.platform.indexOf("Mac") === 0 || navigator.platform === "iPhone") { //something }
	* ...Win32, Win64
	**/
	
	document.getElementById("os").value = OSName;
}
/*
* JS Auto Tip для включених технологій з боку клієнта, i.e.
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
//Наш сервер App інвентаризації
/* додаток «Nmap» - не звичайний нокер портів.
 * У більшості випадків:
 * не очікується, що програма «Nmap» буде встановлена за замовчуванням.
 * було б недобре, якби сервер був скомпрометований.
 * було б недосконало, якби вхід користувача не пройшов
 * перевірка та дезінфекція.
 * Встановити додаток «Nmap» в Linux системах:
 * sudo apt update
 * sudo apt-get install nmap
 **/ 
$nmap_status = false;
$check_app_nmap_c = "nmap -h";
$res = shell_exec($check_app_nmap_c);
if (strpos($res, "https://nmap.org")) {
	$nmap_status = true;
	
	//Створити потрібний файл для збереження результатів сканування
	if (file_exists($directory.$delimeter."scan.XML")) {
		unlink($directory.$delimeter."scan.XML");			
	}
		
	$handle = fopen($directory.$delimeter."scan.XML", "x+");
	fclose($handle);
		
	chmod($directory.$delimeter."scan.XML", 0664);
		
	$user_name = get_current_user();
	chown($directory.$delimeter."scan.XML", $user_name);
}

//Зберігати відповіді в $result 
$result = array();

//Про нашого клієнта (тільки час підказки)
$default_content="\n";

if(isset($_POST["os"])) {
		/* Підготовка файлів для клієнта (символ кінця рядка):
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

//Про наш сервер (тільки інформативний)
$system_content="\n";

/* PHP_OS_FAMILY. Повертає рядок будь-якого з 'Windows', 'BSD', 'OSX', 
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
	//Для автозаповнення
	$url = htmlentities($address);
	/* очікується перевірка вхідних даних користувача та (або) 
	 * визначення цільових меж
	 **/
	$command = "ping -c 1 ".$address;
	
	$result[] = shell_exec($command);
	
	sleep(1);
	
	/* Поточний сканування Nmap:
	 * Протестовані тільки з Linux тількo Systems
	 * 
	 * Якщо сервер не може записати до document_root:
	 * chown www-data:root /path_to_document_root/
	 * 
	 * scan.XML каталог можна розмістити в більш глибокому 
	 * підкаталозі document_root.
	 **/
	if($nmap_status === True && PHP_OS_FAMILY !== "Windows") {		
		/* очікується перевірка вхідних даних користувача та (або) 
		 * визначення цільових меж 
		**/
		$commandNmap = "nmap -A ".$address." -oN ".$directory.$delimeter."scan.XML";
		shell_exec($commandNmap);
		chmod($directory.$delimeter."scan.XML", 0644);
	}
}
if(isset($_POST["ipfour"]) && !empty($_POST["ipfour"])) {
	$address = $_POST["ipfour"];
	//Для автозаповнення
	$ipfour = htmlentities($address);
	/* Перевірка - перевірка, якщо дані відповідають вимогам (так/ні).
	 * Дезінфікування - очищення некоректних даних за визначеними інструкціями.
	 * Наступний зразок показує лише процес перевірки.
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
		$result[] = array('error' => 'Помилка: надана адреса Інтернет-протоколу не IPv4.');
	}
}
?>
<body onload="determine_end_of_line()">
<!-- Header Main Footer -->
<header>
<!-- Логотип і пошук -->
<nav><!-- Посилання для навігації --></nav>
</header>
<main>
<nav>
<a href="https://127.0.0.1:443/ping.en.php" hreflang="en-US" target="_SELF">Англійська</a> &nbsp;
<a href="https://127.0.0.1:443/ping.es.php" hreflang="es-es" target="_SELF">Іспанська</a> &nbsp;
<a href="https://127.0.0.1:443/ping.uk.php" hreflang="uk-ua" target="_SELF">Український</a> &nbsp;
<a href="https://127.0.0.1:443/ping.pl.php" hreflang="pl-pl" target="_SELF">Польський</a> &nbsp;
<a href="https://127.0.0.1:443/ping.et.php" hreflang="et-ee" target="_SELF">Естонську</a> &nbsp;
<a href="https://127.0.0.1:443/ping.lv.php" hreflang="lv-lv" target="_SELF">Латиську</a> &nbsp;
<a href="https://127.0.0.1:443/ping.lt.php" hreflang="lt-lt" target="_SELF">Литовську</a>
</nav>
<h1>Перевірка Пінгу</h1>
<form action="ping.uk.php" method="post" id="fm">
<fieldset>
<legend>Перевірити з 'єднання з неперевіреним і перевіреним входом (front-end)</legend>
<div id="left">
<label for="url" id="url">IP адреса або імя доменa:</label><input type="text" name="url" id="url" value="
<?php
if(isset($url)) {
		echo $url;
}
?>
">
<label for="ipfour" id="ipfour">Лише адреса IPv4:</label><input type="text" name="ipfour" id="ipfour" value="
<?php
if(isset($ipfour)) {
		echo $ipfour;
}
?>
" pattern="[0-9.]+{7,15}">
<!-- html5pattern список доступний -->
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
Результати тестування на пінг:
{$value}
END;
		}
		
	}
}
else {
?>
Результати тестування на пінг:
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
Результати перевірки порту та затримки (Nmap):
{$show_ports}
END;
}
else {
?>
Результати перевірки порту та затримки (Nmap):
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
Три позатематичні поради щодо:
Зараз для завантаження файлів буде використано цей символ кінця рядка: {$content}
END;
?>
</div>
<div style="width:100%; margin:5px; border-left: 5px double gray; text-align: center;">
<blink>HTML-тег кліпання - це сліпка зброя, так сучасні інтернет-браузери не підтримують цю функцію.</blink>
</div>
<div style="width:100%; margin:5px; border-right: 5px double gray; text-align: center;">
Для тих, хто має/хоче перерахувати компютерну мережу:
Сканувати Інтернет/інтрамережу, лише якщо у вас є дозвіл і (або) право на це.
</div>
</section>
</article>
</main>
<footer>
<!-- Звяжіться з нами, про нас, Політика конфіденційності -->
<address><!-- онтактна інформація власника сайту --><address>
</footer>
</body>
</html>
