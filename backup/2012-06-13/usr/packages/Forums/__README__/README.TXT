########################################################################
###
###     Forums Package
###
###		v0.1 2012-06-05
###
########################################################################


Requirements
===================================================

Requires Users and Profiles to work




Settings
===================================================

In controller/Forums.php you will have to specify
The Profiles->view Controller and Methods
for the links to see a users profile to work.



Enable Package
===================================================


1.) Enable Packe
---------------------
+ in usr/config.php add the Following:

	$USE_PACKAGES = array('Forums');


2.) Add CSS
---------------------
+ copy the content from "./css" into www/css/


3.) Add JS
---------------------
+ copy the content from "./js" into www/js/


4.) Add Images
---------------------
+ copy the content from "./img" into www/img/


5.) Database Table
---------------------
+ Add the database file: tables.sql to
  you database