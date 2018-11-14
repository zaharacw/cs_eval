Deployment Instructions
===

#### Requirements:
* **Ubuntu** (14.04)
* **Apache** (2.4.7)
* **MySQL** (5.5.43)
* **PHP** (5.5.9)
* **Mcrypt**, **cURL**, **phpMyAdmin**

### OS & Apache
1. Prepare a Ubuntu machine. This has been tested with **Ubuntu 14.04.2 LTS**.
2. Install the **apache2** package.
	* `sudo apt-get install apache2`
3. Configure folder permissions:
	* `sudo chown -R $USER:$USER /var/www`
	* `sudo chmod -R 755 /var/www`

### URL Rewriting
* Open the following file: **/etc/apache2/sites-available/000-default.conf**
* Replace `DocumentRoot /var/www/html` with the following lines:
```
DocumentRoot /var/www/html

<Directory /var/www/html/>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride all
    Order allow,deny
    allow from all
</Directory>
```
* Enable mod_rewrite with this command: `sudo a2enmod rewrite`
* Restart Apache: `sudo service apache2 restart`

### Additional Tools
1.  Install **MySQL**.
	* `sudo apt-get install mysql-server libapache2-mod-auth-mysql php5-mysql`
	* `sudo mysql_install_db`
	* `sudo /usr/bin/mysql_secure_installation`
2. Install **PHP**.
	* `sudo apt-get install php5 libapache2-mod-php5 php5-mcrypt`
3. Enable **Mcrypt**.
	* `sudo nano /etc/php5/apache2/php.ini`
	* **Append this to the file:** *extension=mcrypt.so*
4. Install **cURL**.
	* `sudo apt-get install php5-curl`
5. Install **phpMyAdmin**.
	* `sudo apt-get install phpmyadmin apache2-utils`
	* `sudo nano /etc/apache2/apache2.conf`
	* **Append this to the file:** *Include /etc/phpmyadmin/apache.conf*
	* `sudo service apache2 restart`
6. Add this line to **/etc/hosts**: `127.0.0.1 cs-course-evals.ewu.edu`

### Files
* Clone the repository (or simply copy the files) into **/var/www/html**
* The directory structure should look like this: **../html/application/models/..**

### Database
1. Login to http://cs-course-evals.ewu.edu/phpmyadmin
2. Create a new database. We'll call it *evals*, but it could be anything.
3. Using phpMyAdmin, import the previous database into *evals*.
4. Open **application/config/database.php**
	* Set `$active_group = 'remote_dev';`
	* Modify the *remote_dev* variables to match the local DB configuration. Specifically: *hostname*, *username*, *password*, *database*.

### Miscellaneous
* Open the root **index.html** and change `define('ENVIRONMENT', 'development')` to `define('ENVIRONMENT', 'production')`
