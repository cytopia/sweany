<?php/*This file must be here for the fallback 404 error pageBut you can edit it of courseTODO: for the validator, I need to check if this file and the error message exists*/?><h1><?php echo $headline; ?></h1><br/><p>The desired page <strong><?php echo $url; ?></strong> could not be found.</p><p>Try to search for it:</p><form name="form_search_page" method="get" action="http://www.google.com/search">	<input type="hidden" name="sitesearch" value="<?php echo $_SERVER['HTTP_HOST']; ?>" />	<input type="text" name="q" value="<?php echo $url; ?>" maxlength="255" />	<input type="submit" name="search" value="search" /></form>