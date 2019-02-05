## Usage example
1. Please configure database connection ./application/config/database.php
	* 'username' => '<DB_USERNAME>',
	* 'password' => '<DB_USER_PASSWORD>',
	* 'database' => '<DB_DATABASE>',
2. Run below command in root directory via terminal
	* `php index.php migrate`

3. Create VHost for opening the project for example **`http://todo.local`**
	```
	<VirtualHost *:80>
       ServerAdmin test@test.loc
       ServerName todo.local
       ServerAlias www.todo.local
       DocumentRoot /var/www/todo
       ErrorLog ${APACHE_LOG_DIR}/todo.local-error.log
       CustomLog ${APACHE_LOG_DIR}/todo.local-access.log combined
    	<Directory "/var/www/todo">
    	    AllowOverride All
    	</Directory>
    </VirtualHost>
	```

4. Add below line inside **`/etc/hosts`** file

	```
		 127.0.0.1 todo.local
		 127.0.0.1 www.todo.local
	```    
5. Restart apache2 service via below command
	```
	sudo systemctl restart apache2
	```
6. Open http://todo.local via browser 	
