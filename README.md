# REST_Countries
An example of a full stack development incorporating HTML, CSS, JavaScript (jQuery and AJAX, and PHP.

## Dependencies
The only dependency for this project is PHP 7. You can install it onto your linux machine with:
'''
> sudo apt-get install php
'''

You can verify you have PHP 7 with:
'''
> php -v
PHP 7.0.22...
'''

There will be other information after the version number, but just make sure you have PHP 7.

## Startup
The start.sh script was written to start the PHP server directly from the directory you clone. Some comments are provided in the file to explain how it uses the php command to start the server on your local machine.

So to start it up, use:
'''
sh start.sh
'''

Then, open any web browser on your machine and in the url field, enter "localhost". Since it uses port 80, you don't need to enter a port into the url.

## Structure and Use
The project currently consists of the following pieces:
- index.html is the main web page and entry point.
- css/countries.css contains some basic CSS for index.html.
- The js/ folder contains jQuery.
- php/countries.php provides the back-end processing of the user input.

Once the php server has been started and you access the web page (via 'localhost' in a browser), all you have to do is enter a search query into the basic text field and submit it. The PHP code will process the request and send back data for the web page to display.

There was more of an emphasis on functionality than aesthetics for this project.
