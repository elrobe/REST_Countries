#
# File:     start.sh
# Author:   Eli Berg
# Purpose:  Quick script to start the PHP server using the src/ directory
#           as the document root. 
#

# [-S localhost:80] will run the PHP server on the local machine on port 80
# [-t ./src] will set the src/ directory as the document root
sudo php -S localhost:80 -t src/
