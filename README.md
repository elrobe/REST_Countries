# REST_Countries
An example of a full stack development incorporating HTML, CSS, JavaScript (jQuery and AJAX), and PHP.

## Dependencies
The only dependency for this project is PHP 7. You can install it onto your linux machine with:
```
sudo apt-get install php
```

You can verify you have PHP 7 with:
```
php -v
```

You should see something like:
```
PHP 7.0.22-0ubuntu0.16.04.1 (cli) ( NTS )
Copyright (c) 1997-2017 The PHP Group
Zend Engine v3.0.0, Copyright (c) 1998-2017 Zend Technologies
    with Zend OPcache v7.0.22-0ubuntu0.16.04.1, Copyright (c) 1999-2017, by Zend Technologies
```

The most important piece is the major version number directly after "PHP" which should be 7 to run this application.

## Startup
First, clone the repository. Then you can start the program either...
- with the provided script, or
- running the ```php``` command youself with the document root set to the ```src/``` folder.

The start.sh script was written to start the PHP server directly from the directory you clone. Some comments are provided in the file to explain how it uses the php command to start the server on your local machine.

So to start it up, use:
```
sh start.sh
```

Or, if you don't have ```bash```, but you have the ```php``` command accessible from your terminal of choice, you can just use the command in the script:
```
sudo php -S localhost:80 -t src/
```

Both the script and the command assume that it's being run from within the cloned ```REST_Countries``` directory.

Then, open any web browser on your machine and in the url field, enter "localhost/". Since it uses port 80, you don't need to enter a port into the url.

## Structure and Use
The project currently consists of the following pieces:
- ```index.html``` is the main web page and entry point.
- ```css/countries.css``` contains some basic CSS for ```index.html```.
- The ```js/``` folder contains jQuery.
- ```php/countries.php``` provides the back-end processing of the user input.

Once the php server has been started and you access the web page (via 'localhost' in a browser), all you have to do is enter a search query into the basic text field and submit it. The PHP code will process the request and send back data for the web page to display.

There was more of an emphasis on functionality than aesthetics for this project.
