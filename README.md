# Pocket Lists for Webasyst #

Pocket Lists is the ultimate to-do & checklist app for teams and small businesses. Designed for Webasyst.

https://pocketlists.com
https://www.webasyst.com/store/app/pocketlists/

## System Requirements ##

	* Web Server
		* e.g. Apache or IIS

	* PHP 8.2+
		* spl extension
		* mbstring
		* iconv
		* json
		* gd or ImageMagick extension
		* webp extension is recommended for .webp image processing

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
