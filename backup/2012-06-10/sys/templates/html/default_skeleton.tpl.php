<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"<?php echo isset($htmltemplate)?$htmltemplate->getXmlNameSpace():'';?> xml:lang="<?php echo $GLOBALS['HTML_DEFAULT_LANG_SHORT'];?>" dir="ltr" lang="<?php echo $GLOBALS['HTML_DEFAULT_LANG_LONG'];?>" <?php echo isset($htmltemplate)?$htmltemplate->getHtmlAttribute():'';?>>
<head<?php echo isset($htmltemplate)?$htmltemplate->getHeadPrefix():'';?>>
	<title><?php echo isset($htmltemplate)?$htmltemplate->getTitle():$GLOBALS['HTML_DEFAULT_PAGE_TITLE'];?></title>
	<meta name="keywords" content="<?php echo isset($htmltemplate)?$htmltemplate->getKeywords():$GLOBALS['HTML_DEFAULT_PAGE_KEYWORDS'];?>" />
	<meta name="description" content="<?php echo isset($htmltemplate)?$htmltemplate->getDescription():$GLOBALS['HTML_DEFAULT_PAGE_DESCRIPTION'];?>" />

	<!-- Content type information -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Language" content="<?php echo $GLOBALS['HTML_DEFAULT_LANG_SHORT'];?>" />
	<meta name="Content-Language" content="<?php echo $GLOBALS['HTML_DEFAULT_LANG_SHORT'];?>" />
	<!-- / Content type information -->

	<!-- Custom Meta Tags -->
<?php echo isset($htmltemplate)?$htmltemplate->getMetaTags():'';?>

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
	<link type="text/css" rel="Stylesheet" href="/css/style.css" />
<?php echo isset($css)?$css->getFiles():'';?>
	<!-- / CSS Stylesheet -->

	<!-- Javascript -->
<?php echo isset($javascript)?$javascript->getVars():'';?>
<?php echo isset($javascript)?$javascript->getFunctions():'';?>
<?php echo isset($javascript)?$javascript->getFiles():'';?>
	<!-- / Javascript -->

</head>
<body<?php echo isset($javascript)?$javascript->getOnPageLoadFunction():'';?>>
<?php include($layout);?>
</body>
</html>