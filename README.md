# Pocket Lists for Webasyst #

Pocket Lists for Webasyst is a server-side web app that is aimed to provide your private cloud syncing hub for Pocket Lists client apps. The app is developed on the LAMP stack and offers the web UI to manage your lists and to-dos.

https://pocketlists.com
https://www.webasyst.com/store/app/pocketlists/
https://www.webasyst.com/store/plugin/pocketlists/pro/

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

## Installing Webasyst Framework (required first!) ##

Install Webasyst Framework via https://github.com/webasyst/webasyst-framework/ or https://www.webasyst.com/platform/

## Installing Pocket Lists app (once Webasyst is installed) ##

1. Once Webasyst Framework is installed, get Pocket Lists app code into your /PATH_TO_WEBASYST/**wa-apps/pocketlists/** folder:

	via GIT:

		cd /PATH_TO_WEBASYST/wa-apps/
		mkdir pocketlists
		git clone git://github.com/vofka/pl2webasyst.git ./

	via SVN:

		cd /PATH_TO_WEBASYST/wa-apps/
		mkdir pocketlists
		svn checkout http://svn.github.com/vofka/pl2webasyst.git ./

2. Add the following line into the /wa-config/apps.php file (this file lists all installed apps):

		'pocketlists' => true,

3. Done! Run Webasyst backend in a web browser and click on Pocket Lists app icon in the main app list.

## Updating Webasyst Framework ##

Staying with the latest version of Pocket Lists app is easy: simply update your files from the repository and login into Webasyst, and all required meta updates will be applied to Webasyst and its apps automatically.
