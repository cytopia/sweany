<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"<?php echo HtmlTemplate::getXmlNameSpace();?> xml:lang="<?php echo \Core\Init\CoreLanguage::getLangShort();?>" dir="ltr" lang="<?php echo \Core\Init\CoreLanguage::getLangLong();?>" <?php echo HtmlTemplate::getHtmlAttribute();?>>
<head<?php echo HtmlTemplate::getHeadPrefix();?>>
	<title><?php echo strlen(HtmlTemplate::getTitle())?HtmlTemplate::getTitle():$GLOBALS['HTML_DEFAULT_PAGE_TITLE'];?></title>
	<meta name="keywords" content="<?php echo strlen(HtmlTemplate::getKeywords())?HtmlTemplate::getKeywords():$GLOBALS['HTML_DEFAULT_PAGE_KEYWORDS'];?>" />
	<meta name="description" content="<?php echo strlen(HtmlTemplate::getDescription())?HtmlTemplate::getDescription():$GLOBALS['HTML_DEFAULT_PAGE_DESCRIPTION'];?>" />

	<!-- Content type information -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Language" content="<?php echo \Core\Init\CoreLanguage::getLangShort();?>" />
	<meta name="Content-Language" content="<?php echo \Core\Init\CoreLanguage::getLangShort();?>" />
	<!-- / Content type information -->

	<!-- Custom Meta Tags -->
<?php echo HtmlTemplate::getMetaTags();?>

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
<?php echo Css::getFiles();?>
	<!-- / CSS Stylesheet -->

	<!-- Javascript -->
<?php echo Javascript::getVars();?>
<?php echo Javascript::getFunctions();?>
<?php echo Javascript::getFiles();?>
	<!-- / Javascript -->

</head>
<body<?php echo Javascript::getOnPageLoadFunction();?>>
<?php echo $layout;?>
</body>
</html>
