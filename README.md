# What is ZendFramework?

As of this writing ZendFramework has moved to Laminas. Watch out for a follow up.

## In this article:

* Installation of ZendFramework
* Localhost host configuration
* Apache VirtualHost server configuration
* Verifying your application

## 1. Installation of ZendFramework

### Navigate to the web root:

cd /Applications/MAMP/htdocs/

### Clone the repo

git clone https://github.com/colin-humphrey/zend2-localised

⚠ For convenience ZendFramework is included in the repo. If you get stuck drop a comment below. Also checkout the Laminas project as this article was written a whiles back.

## 2. Localhost host configuration

### Open and edit the hosts file to add a local development domain

sudo vi /private/etc/hosts

### Append the comment and host entry to the file

#zend2localised local server
127.0.0.1 zend2-localised

### Test the new entry. The command below should not timeout.

ping -c 4 zend2-localised

## 3. Apache VirtualHost server configuration

### Add a VirtualHost configuration entry for the phalconbasic site

⚠ You may need to enable virtual hosts if its a fresh install if so uncomment: Include /Applications/MAMP/conf/apache/extra/httpd-vhosts.conf in your httpd.conf then change Listen 8888 -> Listen 80. Stop and Start apache.

#zend2-localised
<VirtualHost *:80>
    ServerAdmin admin@zend2-localised
    DocumentRoot "/Applications/MAMP/htdocs/zend2-localised/public"
    ServerName zend2-localised
    ErrorLog "/Applications/MAMP/logs/zend2-localised-error"
    CustomLog "/Applications/MAMP/logs/zend2-localised-access" common
    <Directory "/Applications/MAMP/htdocs/zend2-localised/public">
        DirectoryIndex index.php
    </Directory>
</VirtualHost>

### Test the paths and verify nothing broken, the command should output. Syntax OK

/Applications/MAMP/bin/apache2/bin/httpd -t


## 4. Verifying your application

### Navigate to

http://zend2-localised/ 🎉