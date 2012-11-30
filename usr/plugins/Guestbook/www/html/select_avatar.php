<?php
$path = '..'.DIRECTORY_SEPARATOR .'img'.DIRECTORY_SEPARATOR.'avatars'.DIRECTORY_SEPARATOR;

if ($handle = opendir($path))
{
	while (false !== ($file = readdir($handle)))
	{
		if ($file != '.' && $file != '..')
		{
			echo '<a href="#" onClick="window.opener.document.getElementById(\'avatarField\').value=\''.$file.'\'; window.close();">';
			echo 	'<img src="'.$path.$file.' width="64" height="64" style="width:64px;height:64px">';
			echo '</a>';
		}
    }
}