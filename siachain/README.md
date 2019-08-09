# Storij-WebUI
A web interface to StorijOS

## Things to consider
The php and bin directories must be readable only locally through the webserver.
Also, prevent the client from browsing the other directories. These items have not
been put into the .htaccess file to make the webapp more adpated to StorijOS as
they have already been considered in it.

Some other security measures have been taken by StorijOS Apache server which can be
viewed in /etc/apache2/vhosts.d/default_vhost.include in the OS image.
