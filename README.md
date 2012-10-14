Sweany is a performance-orientated, programmer-friendly and self-validating MVC framework for PHP.
The current downloadable version comes with three pre-coded plugins:
A user registration/login plugin, a bb-code forum and a site-contact form.

Requirements:
  * at least PHP 5.4.0 (due to use of
    + namespaces
    + late static binding
    + $this in lambda functions
  )

Documentation:
  * https://github.com/lockdoc/sweany/wiki

Features:
  * Pages, Blocks, Layouts (all Model, View Controller based)
  * Tables (maps the database structure to an ORM model)
  * Self-validating advanced SQL ORM model (array or object based)
  * Fully separated Plugin support (have their own Pages, Blocks, Tables, Configs and www directory)
  * Vendors (third party module inclusion)
  * Helpers (various integrated helper classes, such as BBCode parsers, Mailers, Highlighters, String/Array functionality and many more)
  * Sophisticated HTML Form creation/validation (even automated validation against database entries)
  * Integrated user management
  * Integrated language support (via xml files and/or via t()-function with database backend)
  * Integrated Validator mode (validates a lot of the programmers written code on the fly)
  * Syslog (Internal Debug Mode to monitor performance and engine calls)
  * Fast core (a single file holds everything to reduce disk loading times... needs to be pushed)
  * Integrated CSS Debugger
  * Automated ECSS inclusion (https://github.com/lockdoc/ecss)
  * Fully customizable (can deactivate all core modules if not needed, such as database, users, language, etc)

 
Core Modules
--------------------------
Sweany contains a couple of core modules that can all be completely disabled,
via the config file, to even support and boost performance for static, light-weight web-pages.
The core modules are as follows:
  
  * Database module
    + the whole database structure (and all its relations: one-one, one-many, many-one, many-many)
      is completely mapped in so called table files
    + table file creation is completely self-validatious
      - If you specify a field that does not exist in the database, the framework will complain
      - If you specify a wrong relation, the framework will tell you what is wrong and how it should be
      - and many more
    + supports auto-update fields on insert/update operations of any name and type (datetime, timestamp, unix timestamp)
    + supports overwriting of delete/update function (e.g. to make a delete only set a field to is_deleted = 1)
    + supports auto-deleting related rows in other tables (if defined by relation settings)
    + once the table files have been defined,
      there is no need to write a single sql statement anymore
    + data can the  be fetched into defined entities as arrays or objects
      without belonging relations, with them and even recursively (with relations of relations)
    + If field names are changed in the sql table itself, you can simple add aliases in the table files
      and everything will work as before, without changing other code (even insert/update will make use of aliases)
    + auto-generated sql queries are performance orientated
      - all X-to-one relations (even recursive) are done in a single query via row_numbers (row_number emulation with variables for mysql)
      - all X-to-many relations (also recursive) need to be done by iteration as multiple relations on the same level that want to limit
        the number of rows do not support row_numbers
      - Sweany will also notify you about missing sql field indexes needed to speed up join 
    + the database engine to use can be
	
  * Users module (requires database)
     + user functionality is available in every controller (page, block and layout) via $this->user and can also be parsed to the views
	 + user functionality is also vailable in rules for formhelper, in order to check input against users table (such as already existing email addresses)
	 + page controller can specify public $admin_area = true; which will allow only users of type administrator to access it, all others will see a 404
	 + Sweany also ships with a plugin that handles login, registration, validation and lost password for users (including sending of emails)
	 + user passwords are stored as sha-512 hashes with a unique salt per user
	 
  * Users Online module (requires database, requires users)
     + is able to count the amount of users (logged in and anonymous) on the current page
	 + you can also add X fake anonymous users via the config

  * Visitor Logging module (requires database)
     + detailed database log of all visitors of this site

  * Language module (requires xml files and/or database)
     + multi-languages have been made available through two different concepts (which can also be used together)
	 + XML-Files:
	   - create an xml file for each language
	     - you can then access it in all controller (page, block, layout) via $this->language
		 - Sweany will also automatically self-validate the correctness and availability of all xml files on the fly
	 + t()-function (database approach):
	   - simply put all text to be translated inside the t()-function (in english language)
	   - you can then add translations for all text via the backend
	   - if no translation is found, the original english text will be outputted
	  
  * ECSS integration
      + you can activate ECSS (Extended CSS) via the config file and all included CSS files
        can automatically use the syntax and features of ECSS (no need for special inclusion, just one switch)	  


Helper
--------------------------
Helper are integrated pre-coded static classes that extend the core functionality of sweany itself.
A few examples are shown below:

  * Form Helper
    + lets you build validatable forms
  * HTML/CSS/JS Helper
    + used to add css/js files, set html title, keywords, namespaces etc on-the-fly
  * Mail Helper
    + send emails
	+ store sent emails in database
  * Rules
    + functions used to validate form input against
  * Highlighter
    + php side code highlighter
  * String/Array Helper
    + manipulate strings/arrays (e.g. shorten strings)
  * LogCat
    + project specific file logging functionality
  * BBCode
    + translate BBCode to nice-looking html code
  * and many more


Plugins
--------------------------
Sweany web-applications can be built on a complete module approach with independent plugins, each having their own pages, blocks and layouts.
Every plugin is just like a normal sweany instance and holds the same folder structure as the main project folder.
Plugins require no configuration to work, once they are in the plugin folder, they are ready to go.
As not all Plugin views may suit your needs, each individual view can be wrapped from the main project folder,
to have custom html code around it.

Key features:
  * plugin tables are also validated by the core validator
  * plugin tables can be used by the whole application and other plugins as well
  * plugins can have their own config files and validators to check for availability of other plugins/modules
  * plugins have their own separate www directory for images, css and js files via htaccess routing
  * plugins have their own language files (if desired)
  * each plugin view can be wrapped by the main project separetely
  
Sweany already ships with a couple of plugins that are useful to many sites.
These plugins are also highly configurable via their respecting config files.
  * user management (login, register, validate, lost password)
  * bb-code forum
  * site contact form
  
  
Advanced Features
--------------------------
  * SysLog
    + SysLog is the programmer's best friend.
    + It is a console, that will be appended to the html page itself and
      shows the internal workflow and measures the time taken to execute each step
    + It also integrates the validator messages (see below) and informs you about all errors/warnings
    + The debug level for SysLog can be set in the project config file

  * Validator Mode
    + By far the coolest and most useful thing about sweany!
    + The validator mode (if enabled) will validate the code, the programmer has written on-the-fly

    + Scenario 1:
      You have activated xml-language support in config.php and set 3 languages (english, german and russian),
      but you forgot to add the russian xml file.
      - sweany will complain and tell you to add this file.

      You have added the russian xml file, and you have also added 2 more texts to the russian file, but not
      to the others.
      - sweany will complain and tell you what texts need to be added in the other files

    + Scenario 2:
      You want to add a new hasOne relation for ProfileTable but have no idea how to properly do this.
      You start off by just specifying the name:
      - sweany will complain and tell you what exactly is missing for each step
      You then follow to add everyting sweany tells you. Now you add the foreignKey, but mix up the table it belongs to:
      - sweany automatically checks the tables and tells you, that it does not exist in table X
      You add the new key to table X or change the name and start adding fields to fetch, but not all of them are actually available
      in the database:
      - sweany complains about the fields that do not exist

    + There are various other scenarious where sweany will tell you about misconfigured arrays or missing stuff

  * Fast Core Mode
    + This mode is designed to be used for production.
      Once you have finished your project and everything is working as expected, you can switch to this mode.
      - The fast core mode removes all checking and logging functionality.
      - It also uses a single core file (instead many separated files) without spaces and newlines
        to reduce file size and the most important bottleneck of loading many files from disk
      - The fast core is around 60kb and this is all that is needed to hold your web application

  * Custom Routing
    + Normal url calls are http://Domain.tld/<ControllerName>/<FunctionName>/<param1>/<param2>/<paramX>
    + In order to have more friendly looking url names you can change the names of controller and function
      for the url any time without actually changing the names.
      - E.g.:
        Your conroller is called UserMessagesController and your function is called sendNewMessage
        your url call would be http://Domain.tld/UserMessages/sendNewMessage
        But you can rewrite the url call to whatever you want in config.php as such:
        http://Domain.tld/message/send
      - In order for all internal links to work, you will have to use the HTML helper on links,
        which will consider the routing, so that you can rewrite it any time without having broken links.


And there is much more to discover!