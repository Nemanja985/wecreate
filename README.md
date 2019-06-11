# Repository Description

This repository is for hosting source code of a wordpress application.

## Plugins and Themes Version Control

All plugins and and themes should be installed via composer but not via the admin interface.

composer.lock should be committed because it records the versions of all libraries and packages.  
With composer.lock, we can run below command to get exact same versions of the packages in the server.  

```
composer install
```

If no composer.lock, we must use below command to get the packages but it may also upgrade the packages.  
We should only use this command for upgrading the plugins or wordpress core.  

```
composer update
```

### Install a new plugin or theme

All public plugins can be found on https://wpackagist.org/.  
You can either run below command or modify the composer.json file manually.  

```
composer require <plugin/theme package name>:<version>
```

If the plugin is not public, we can download the source code of the plugin and create a new repository for the plugin.  
A composer.json is needed to be included in the repository as well.  
Here is the guideline - https://salferrarello.com/wordpress-composer-private-plugin/
