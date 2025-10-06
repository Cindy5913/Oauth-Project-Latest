# Oauth-Project-Latest
This is a Website 

A simple Flight Login System built using PHP, MySQL, and Google OAuth 2.0 for secure user authentication. It allows users to register, log in, and authenticate using either an email and password or a Google account. To run the project, install XAMPP (PHP version 7.4 to 8.2), start Apache and MySQL, and place the project folder in C:\xampp\htdocs\flight-booking. Check PHP by typing php -v in Command Prompt, then make sure extensions like openssl, curl, json, mbstring, and pdo_mysql are enabled in php.ini. Set up Google OAuth in Google Cloud Console by creating an OAuth Client ID and using http://localhost/flight-booking/callback.php as the redirect URI, then add your Client ID and Secret in callback.php. Open http://localhost/flight-booking/ in your browser to access the system. The project includes index.php for login, callback.php for Google OAuth, logout.php for session logout, and users.json for encrypted user data. It focuses on authentication only and can be expanded for future flight booking features. Developed by Cindy Bulanhagui, this project may be freely used and modified.

Before running the project, make sure you have the following installed: - [XAMPP Control Panel](https://www.apachefriends.org/index.html) - PHP version 7.4 to 8.2 - A web browser (Chrome, Firefox, Edge, etc.)

To verify it’s working, open Command Prompt and type:
```bash
php -v
