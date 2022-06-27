<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="description" content="Prueba del comando ping en las redes informáticas de Internet/Intranet en Linux, iOS, MacOS, Unix, Android, Windows sistemas.">
<meta name="keywords" content="Pruebas, Ping">
<meta name="author" content="elithecomputerguy">
<meta property="og:title" content="Prueba de ping">
<meta property="og:url" content="https://github.com/elithecomputerguy">
<meta property="og:description" content="Elithecomputerguy presenta la prueba del comando ping en las redes informáticas de Internet/Intranet en sistemas Linux, iOS, MacOS, Unix, Android y Windows.">
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
<title>Prueba de Ping</title>
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
<!-- Sólo JavaScript -->
function determine_end_of_line() {
	var OSName="Unknown OS";
	if (navigator.userAgent.indexOf("Win")!=-1) OSName="Windows";
	if (navigator.userAgent.indexOf("Mac")!=-1) OSName="MacOS";
	if (navigator.userAgent.indexOf("X11")!=-1) OSName="UNIX";
	if (navigator.userAgent.indexOf("Linux")!=-1) OSName="Linux";
	
	/* Revise la necesidad de intercambiar en el navigator.platform, i.e.
	* if (navigator.platform.indexOf("Mac") === 0 || navigator.platform === "iPhone") { //something }
	* ...Win32, Win64
	**/
	
	document.getElementById("os").value = OSName;
}
/*
* JS Auto Tip lubatava tehnoloogia jaoks kliendipoolsest küljest, i.e.
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
// Meie serverirakenduste inventuur
/* rakendus „nmap” - pole tavaline pordinupp.
 * Enamikul juhtudel:
 * eeldatakse, et rakendus nmap installitakse vaikimisi.
 * see ei oleks hea, kui Server oleks kompromiteeritud.
 * see ei oleks täiuslik, kui kasutaja sisend ei läbiks
 * valideerimine ja saneerimine.
 * Rakenduse „nmap” installimiseks Linuxi süsteemidesse tehke järgmist:
 * sudo apt update
 * sudo apt-get install nmap
 **/ 
$nmap_status = false;
$check_app_nmap_c = "nmap -h";
$res = shell_exec($check_app_nmap_c);
if (strpos($res, "https://nmap.org")) {
	$nmap_status = true;
	
	//Crear archivo necesario para almacenar los resultados del análisis
	if (file_exists($directory.$delimeter."scan.XML")) {
		unlink($directory.$delimeter."scan.XML");			
	}
		
	$handle = fopen($directory.$delimeter."scan.XML", "x+");
	fclose($handle);
		
	chmod($directory.$delimeter."scan.XML", 0664);
		
	$user_name = get_current_user();
	chown($directory.$delimeter."scan.XML", $user_name);
}

//Almacenar respuestas en el $result 
$result = array();

//Acerca de nuestro cliente (sólo Tip tiempo)
$default_content="\n";

if(isset($_POST["os"])) {
		/* Preparación de archivos para el cliente (Carácter de fin de línea):
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

//Acerca de nuestro servidor (sólo informativo)
$system_content="\n";

/* PHP_OS_FAMILY. Devuelve una cadena de 'Windows', 'BSD', 'OSX', 
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
	//Para relleno automático
	$url = htmlentities($address);
	/* se esperaba una validación de entrada de usuario y (o) 
	 * la definición de los límites de destino
	 **/
	$command = "ping -c 1 ".$address;
	
	$result[] = shell_exec($command);
	
	sleep(1);
	
	/* Análisis de Nmap actual:
	 * Probado con Linux como sólo sistemas
	 * 
	 * Si el servidor no puede escribir en Document_Root:
	 * chown www-data:root /path_to_document_root/
	 * 
	 * scan.XML directorio se puede colocar en el 
	 * subdirectorio más profundo de Document_Root.
	 **/
	if($nmap_status === True && PHP_OS_FAMILY !== "Windows") {		
		/* se esperaba una validación de entrada de usuario y (o) 
		 * la definición de los límites de destino
		**/
		$commandNmap = "nmap -A ".$address." -oN ".$directory.$delimeter."scan.XML";
		shell_exec($commandNmap);
		chmod($directory.$delimeter."scan.XML", 0644);
	}
}
if(isset($_POST["ipfour"]) && !empty($_POST["ipfour"])) {
	$address = $_POST["ipfour"];
	//Para relleno automático
	$ipfour = htmlentities($address);
	/* Validación - comprobación, si los datos cumplen los requisitos (sí/no).
	 * Sanitización - limpiar datos no válidos de acuerdo con las instrucciones definidas.
	 * En la siguiente muestra sólo se muestra el proceso de validación.
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
		$result[] = array('error' => 'Errore: la dirección de protocolo de Internet proporcionada no es IPv4.');
	}
}
?>
<body onload="determine_end_of_line()">
<!-- Header Main Footer -->
<header>
<!-- Logotipo y búsqueda de -->
<nav><!-- Vínculo para navegación --></nav>
</header>
<main>
<nav>
<a href="https://127.0.0.1:443/ping.en.php" hreflang="en-US" target="_SELF">Inglés</a> &nbsp;
<a href="https://127.0.0.1:443/ping.es.php" hreflang="es-es" target="_SELF">Español</a> &nbsp;
<a href="https://127.0.0.1:443/ping.uk.php" hreflang="uk-ua" target="_SELF">Ucraniano</a> &nbsp;
<a href="https://127.0.0.1:443/ping.pl.php" hreflang="pl-pl" target="_SELF">Polaco</a> &nbsp;
<a href="https://127.0.0.1:443/ping.et.php" hreflang="et-ee" target="_SELF">Estonio</a> &nbsp;
<a href="https://127.0.0.1:443/ping.lv.php" hreflang="lv-lv" target="_SELF">Letón</a> &nbsp;
<a href="https://127.0.0.1:443/ping.lt.php" hreflang="lt-lt" target="_SELF">Lituano</a>
</nav>
<h1>Prueba de Ping</h1>
<form action="ping.es.php" method="post" id="fm">
<fieldset>
<legend>Comprobar la conectividad con entradas no validadas y validadas (front-end)</legend>
<div id="left">
<label for="url" id="url">Dirección IP o nombre de dominio:</label><input type="text" name="url" id="url" value="
<?php
if(isset($url)) {
		echo $url;
}
?>
">
<label for="ipfour" id="ipfour">Sólo dirección IPv4:</label><input type="text" name="ipfour" id="ipfour" value="
<?php
if(isset($ipfour)) {
		echo $ipfour;
}
?>
" pattern="[0-9.]+{7,15}">
<!-- html5pattern lista disponible -->
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
Resultados de la prueba de ping:
{$value}
END;
		}
		
	}
}
else {
?>
Resultados de la prueba de ping:
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
Resultados de la prueba de latencia y puerto (nmap):
{$show_ports}
END;
}
else {
?>
Resultados de la prueba de latencia y puerto (nmap):
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
Tres sugerencias fuera del tema sobre:
Actualmente, para descargas de archivos se usaría este carácter de fin de línea: {$content}
END;
?>
</div>
<div style="width:100%; margin:5px; border-left: 5px double gray; text-align: center;">
<blink>La etiqueta HTML del parpadeo es una arma ciega, por lo que Los navegadores de Internet modernos no admiten esta función.</blink>
</div>
<div style="width:100%; margin:5px; border-right: 5px double gray; text-align: center;">
Para aquellos que tienen o desean Enumerar la Red de Computación:
Escanee Internet/Intranet sólo si tiene el privilegio Permiso y (o) privilegio para hacerlo.
</div>
</section>
</article>
</main>
<footer>
<!-- Comuníquese con nosotros, Acerca de nosotros, Política de privacidad -->
<address><!-- Información de contactos del propietario del sitio--><address>
</footer>
</body>
</html>
