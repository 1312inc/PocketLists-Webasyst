# Pocket Lists for Webasyst #

To-dos is a Webasyst app for managing to-do lists for teams.
The app is handy on it's own and is about to become a bridge between Webasyst and Pocket Lists.

https://www.pocketlists.com
https://www.webasyst.com

## System Requirements ##

  * Web Server
		* e.g. Apache or IIS

	* PHP 5.6+
		* spl extension
		* mbstring
		* iconv
		* json
		* gd or ImageMagick extension

	* MySQL 5.5+

## Installing Webasyst Framework ##

Install Webasyst Framework via http://github.com/webasyst/webasyst-framework/ or http://www.webasyst.com/framework/

## Installing To-dos app ##

1. Once Webasyst Framework is installed, get To-dos app code into your /PATH_TO_WEBASYST/wa-apps/pocketlists/ folder:

	via GIT:

		cd /PATH_TO_WEBASYST/wa-apps/pocketlists/
		git clone git://github.com/vofka/pl2webasyst.git ./

	via SVN:

		cd /PATH_TO_WEBASYST/wa-apps/pocketlists/
		svn checkout http://svn.github.com/vofka/pl2webasyst.git ./

2. Add the following line into the /wa-config/apps.php file (this file lists all installed apps):

		'pocketlists' => true,

3. Done. Run Webasyst backend in a web browser and click on To-dos app icon in the main app list.

## Updating Webasyst Framework ##

Staying with the latest version of To-dos app is easy: simply update your files from the repository and login into Webasyst, and all required meta updates will be applied to Webasyst and its apps automatically.
