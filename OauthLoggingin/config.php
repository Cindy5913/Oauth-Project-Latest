<?php
// Google OAuth Config
define("CLIENT_ID", "85947110596-kjpe9h3l3svu41a7i1cat06tfh12gmfr.apps.googleusercontent.com");// app id
define("CLIENT_SECRET", "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");//app secret
define("REDIRECT_URI", "http://localhost/OauthLoggingin/callback.php");//redirect after log in
 // must match Google Cloud setup
define("AUTH_URL", "https://accounts.google.com/o/oauth2/v2/auth");//login page
define("TOKEN_URL", "https://oauth2.googleapis.com/token");//get access token
define("USERINFO_URL", "https://www.googleapis.com/oauth2/v2/userinfo");// get user info
