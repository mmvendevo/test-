<?php

// Page cr?par Shepard [Fabian Pijcke] <Shepard8@laposte.net>
// Arno Esterhuizen <arno.esterhuizen@gmail.com>
// et Romain Bourdon <romain@romainbourdon.com>
//  
// Mise  our par Herve eclerc herve.leclerc@alterway.fr
// ic?? par Mark James <http://www.famfamfam.com/lab/icons/silk/>



//chemin jusqu'au fichier de conf de WampServer
$wampConfFile = '../wampmanager.conf';

//chemin jusqu'aux fichiers alias
$aliasDir = '../alias/';



// on charge le fichier de conf locale
if (!is_file($wampConfFile))
    die ('Unable to open WampServer\'s config file, please change path in index.php file');
//require $wampConfFile;
$fp = fopen($wampConfFile,'r');
$wampConfFileContents = fread ($fp, filesize ($wampConfFile));
fclose ($fp);


//on r??s les versions des applis
preg_match('|phpVersion = (.*)\n|',$wampConfFileContents,$result);
$phpVersion = str_replace('"','',$result[1]);
preg_match('|apacheVersion = (.*)\n|',$wampConfFileContents,$result);
$apacheVersion = str_replace('"','',$result[1]);
preg_match('|mysqlVersion = (.*)\n|',$wampConfFileContents,$result);
$mysqlVersion = str_replace('"','',$result[1]);
preg_match('|wampserverVersion = (.*)\n|',$wampConfFileContents,$result);
$wampserverVersion = str_replace('"','',$result[1]);



// repertoires  gnorer dans les projets
$projectsListIgnore = array ('.','..');


// textes
$langues = array(
	'en' => array(
		'langue' => 'English',
		'autreLangue' => 'Version Fran&ccedil;aise',
		'autreLangueLien' => 'fr',
		'titreHtml' => 'WAMPSERVER Homepage',
		'titreConf' => 'Server Configuration',
		'versa' => 'Apache Version :',
		'versp' => 'PHP Version :',
		'versm' => 'MySQL Version :',
		'phpExt' => 'Loaded Extensions : ',
		'titrePage' => 'Tools',
		'txtProjet' => 'Your Projects',
		'txtNoProjet' => 'No projects yet.<br />To create a new one, just create a directory in \'www\'.',
		'txtAlias' => 'Your Aliases',
		'txtNoAlias' => 'No Alias yet.<br />To create a new one, use the WAMPSERVER menu.',
		'faq' => 'http://www.en.wampserver.com/faq.php'
	),
	'fr' => array(
		'langue' => 'Fran?s',
		'autreLangue' => 'English Version',
		'autreLangueLien' => 'en',
		'titreHtml' => 'Accueil WAMPSERVER',
		'titreConf' => 'Configuration Serveur',
		'versa' => 'Version de Apache:',
		'versp' => 'Version de PHP:',
		'versm' => 'Version de MySQL:',
		'phpExt' => 'Extensions Charg&eacute;es: ',
		'titrePage' => 'Outils',
		'txtProjet' => 'Vos Projets',
		'txtNoProjet' => 'Aucun projet.<br /> Pour en ajouter un nouveau, cr&eacute;ez simplement un r&eacute;pertoire dans \'www\'.',
		'txtAlias' => 'Vos Alias',
		'txtNoAlias' => 'Aucun alias.<br /> Pour en ajouter un nouveau, utilisez le menu de WAMPSERVER.',
		'faq' => 'http://www.wampserver.com/faq.php'
	)
);



//affichage du phpinfo
if (isset($_GET['phpinfo']))
{
	phpinfo();
	exit();
}


//affichage des images
if (isset($_GET['img']))
{
    switch ($_GET['img'])
    {
        case 'pngFolder' :
        header("Content-type: image/png");
        echo base64_decode($pngFolder);
        exit();
        
        case 'pngFolderGo' :
        header("Content-type: image/png");
        echo base64_decode($pngFolderGo);
        exit();
        
        case 'gifLogo' :
        header("Content-type: image/gif");
        echo base64_decode($gifLogo);
        exit();
        
        case 'pngPlugin' :
        header("Content-type: image/png");
        echo base64_decode($pngPlugin);
        exit();
        
        case 'pngWrench' :
        header("Content-type: image/png");
        echo base64_decode($pngWrench);
        exit();
        
        case 'favicon' :
        header("Content-type: image/x-icon");
        echo base64_decode($favicon);
        exit();
    }
}



// D?nition de la langue et des textes 

if (isset ($_GET['lang']))
{
	$langue = $_GET['lang'];
}
elseif (isset ($_SERVER['HTTP_ACCEPT_LANGUAGE']) AND preg_match("/^fr/", $_SERVER['HTTP_ACCEPT_LANGUAGE']))
{
	$langue = 'fr';
}
else
{
	$langue = 'en';
}

//initialisation
$aliasContents = '';

// recuperation des alias
if (is_dir($aliasDir))
{
    $handle=opendir($aliasDir);
    while ($file = readdir($handle)) 
    {
	    if (is_file($aliasDir.$file) && strstr($file, '.conf'))
	    {		
		    $msg = '';
		    $aliasContents .= '<li><a href="'.str_replace('.conf','',$file).'/">'.str_replace('.conf','',$file).'</a></li>';
	    }
    }
    closedir($handle);
}
if (!isset($aliasContents))
	$aliasContents = $langues[$langue]['txtNoAlias'];


// recuperation des projets
$handle=opendir(".");
$projectContents = '';
while ($file = readdir($handle)) 
{
	if (is_dir($file) && !in_array($file,$projectsListIgnore)) 
	{		
		$projectContents .= '<li><a href="'.$file.'">'.$file.'</a></li>';
	}
}
closedir($handle);
if (!isset($projectContents))
	$projectContents = $langues[$langue]['txtNoProjet'];


//initialisation
$phpExtContents = '';

// recuperation des extensions PHP
$loaded_extensions = get_loaded_extensions();
foreach ($loaded_extensions as $extension)
	$phpExtContents .= "<li>${extension}</li>";




$pageContents = <<< EOPAGE
<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html lang="en" xml:lang="en">
<head>
	<title>{$langues[$langue]['titreHtml']}</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

	<style type="text/css">
* {
	margin: 0;
	padding: 0;
}

html {
	background: #ddd;
}
body {
	margin: 1em 10%;
	padding: 1em 3em;
	font: 80%/1.4 tahoma, arial, helvetica, lucida sans, sans-serif;
	border: 1px solid #999;
	background: #eee;
	position: relative;
}
#head {
	margin-bottom: 1.8em;
	margin-top: 1.8em;
	padding-bottom: 0em;
	border-bottom: 1px solid #999;
	letter-spacing: -500em;
	text-indent: -500em;
	height: 125px;
	background: url(index.php?img=gifLogo) 0 0 no-repeat;
}
.utility {
	position: absolute;
	right: 4em;
	top: 145px;
	font-size: 0.85em;
}
.utility li {
	display: inline;
}

h2 {
	margin: 0.8em 0 0 0;
}

ul {
	list-style: none;
	margin: 0;
	padding: 0;
}
#head ul li, dl ul li, #foot li {
	list-style: none;
	display: inline;
	margin: 0;
	padding: 0 0.2em;
}
ul.aliases, ul.projects, ul.tools {
	list-style: none;
	line-height: 24px;
}
ul.aliases a, ul.projects a, ul.tools a {
	padding-left: 22px;
	background: url(index.php?img=pngFolder) 0 100% no-repeat;
}
ul.tools a {
	background: url(index.php?img=pngWrench) 0 100% no-repeat;
}
ul.aliases a {
	background: url(index.php?img=pngFolderGo) 0 100% no-repeat;
}
dl {
	margin: 0;
	padding: 0;
}
dt {
	font-weight: bold;
	text-align: right;
	width: 11em;
	clear: both;
}
dd {
	margin: -1.35em 0 0 12em;
	padding-bottom: 0.4em;
	overflow: auto;
}
dd ul li {
	float: left;
	display: block;
	width: 16.5%;
	margin: 0;
	padding: 0 0 0 20px;
	background: url(index.php?img=pngPlugin) 2px 50% no-repeat;
	line-height: 1.6;
}
a {
	color: #024378;
	font-weight: bold;
	text-decoration: none;
}
a:hover {
	color: #04569A;
	text-decoration: underline;
}
#foot {
	text-align: center;
	margin-top: 1.8em;
	border-top: 1px solid #999;
	padding-top: 1em;
	font-size: 0.85em;
}
</style>
    
	<link rel="shortcut icon" href="index.php?img=favicon" type="image/ico" />
</head>

<body>
	<div id="head">
		<h1><abbr title="Windows">W</abbr><abbr title="Apache">A</abbr><abbr title="MySQL">M</abbr><abbr title="PHP">P</abbr></h1>
		<ul>
			<li>PHP 5</li>
			<li>Apache 2</li>
			<li>MySQL 5</li>
		</ul>
	</div>

	<ul class="utility">
		<li>Version ${wampserverVersion}</li>
		<li><a href="?lang={$langues[$langue]['autreLangueLien']}">{$langues[$langue]['autreLangue']}</a></li>
	</ul>

	<h2> {$langues[$langue]['titreConf']} </h2>

	<dl class="content">
		<dt>{$langues[$langue]['versa']}</dt>
		<dd>${apacheVersion} &nbsp;</dd>
		<dt>{$langues[$langue]['versp']}</dt>
		<dd>${phpVersion} &nbsp;</dd>
		<dt>{$langues[$langue]['phpExt']}</dt> 
		<dd>
			<ul>
			${phpExtContents}
			</ul>
		</dd>
		<dt>{$langues[$langue]['versm']}</dt>
		<dd>${mysqlVersion} &nbsp;</dd>
	</dl>
	<h2>{$langues[$langue]['titrePage']}</h2>
	<ul class="tools">
		<li><a href="?phpinfo=1">phpinfo()</a></li>
		<li><a href="phpmyadmin/">phpmyadmin</a></li>
	</ul>
	<h2>{$langues[$langue]['txtProjet']}</h2>
	<ul class="projects">
	$projectContents
	</ul>
	<h2>{$langues[$langue]['txtAlias']}</h2>
	<ul class="aliases">
	${aliasContents}			
	</ul>
	<ul id="foot">
		<li><a href="http://www.wampserver.com">WampServer</a></li> - 
        <li><a href="http://www.wampserver.com/en/donations.php">Donate</a></li> -
		<li><a href="http://www.alterway.fr">Alter Way</a></li>
	</ul>
</body>
</html>
EOPAGE;

echo $pageContents;
?>
