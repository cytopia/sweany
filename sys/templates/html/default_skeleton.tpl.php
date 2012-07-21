<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"<?php echo class_exists('HtmlTemplate')?HtmlTemplate::getXmlNameSpace():'';?> xml:lang="<?php echo $GLOBALS['HTML_DEFAULT_LANG_SHORT'];?>" dir="ltr" lang="<?php echo $GLOBALS['HTML_DEFAULT_LANG_LONG'];?>" <?php echo class_exists('HtmlTemplate')?HtmlTemplate::getHtmlAttribute():'';?>>
<head<?php echo class_exists('HtmlTemplate')?HtmlTemplate::getHeadPrefix():'';?>>
	<title><?php echo class_exists('HtmlTemplate')?HtmlTemplate::getTitle():$GLOBALS['HTML_DEFAULT_PAGE_TITLE'];?></title>
	<meta name="keywords" content="<?php echo class_exists('HtmlTemplate')?HtmlTemplate::getKeywords():$GLOBALS['HTML_DEFAULT_PAGE_KEYWORDS'];?>" />
	<meta name="description" content="<?php echo class_exists('HtmlTemplate')?HtmlTemplate::getDescription():$GLOBALS['HTML_DEFAULT_PAGE_DESCRIPTION'];?>" />

	<!-- Content type information -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Language" content="<?php echo $GLOBALS['HTML_DEFAULT_LANG_SHORT'];?>" />
	<meta name="Content-Language" content="<?php echo $GLOBALS['HTML_DEFAULT_LANG_SHORT'];?>" />
	<!-- / Content type information -->

	<!-- Custom Meta Tags -->
<?php echo class_exists('HtmlTemplate')?HtmlTemplate::getMetaTags():'';?>

	<!-- No cache headers -->
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta http-equiv="Cache-Control" content="no-cache" />
	<!-- / No cache headers -->

	<!-- Robots -->
	<meta name="robots" content="yes, all, index, follow, noodp" />
	<meta name="revisit-after" content="1 day" />
	<!-- / Robots -->

	<link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico" />

	<!-- CSS Stylesheet -->
	<?php /* TODO: replace with std css files or define in config.php */ ?>
	<link type="text/css" rel="Stylesheet" href="/css/style.css" />
	<link type="text/css" rel="Stylesheet" href="/css/layout.css" />
<?php echo class_exists('Css')?Css::getFiles():'';?>
	<!-- / CSS Stylesheet -->

	<!-- Javascript -->
<?php echo class_exists('Javascript')?Javascript::getVars():'';?>
<?php echo class_exists('Javascript')?Javascript::getFunctions():'';?>
<?php echo class_exists('Javascript')?Javascript::getFiles():'';?>
	<!-- / Javascript -->

</head>
<body<?php echo class_exists('Javascript')?Javascript::getOnPageLoadFunction():'';?>>
<?php echo $layout;?>
</body>
</html>