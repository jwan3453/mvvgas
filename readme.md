
## Project setup


### 1. Run composer at root directory
$ composer install

### 2. Rename .env.example to .env


### 3.Set mysql connection in .env file( /.env)

DB_CONNECTION=mysql  
DB_HOST=  
DB_PORT=    
DB_DATABASE=  
DB_USERNAME=  
DB_PASSWORD=  



### 4.Set smtp email server  in .env file( /.env)

MAIL_DRIVER=smtp  
MAIL_HOST=  
MAIL_PORT=  
MAIL_USERNAME=  
MAIL_PASSWORD=  
MAIL_ENCRYPTION=  
MAIL_FROM_ADDRESS=  
MAIL_FROM_NAME=  


### 5.Set twilio account info in(/config/site.php)


	
	'twilio_acount_id' => '' // account id
	
	'twilio_token' => '' // account token
	
	'twilio_number' => ''//twilio mobile number
	


### 6.Set web server address in(/config/site.php)

	'server' => '' // http://127.0.0.1(for example),
	

### 7.Create data tables 

$ php artisan migrate


### 8.Create prefilled data

$ php artisan db:seed