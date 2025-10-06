<?php
session_start();
require "config.php";

// Step 1: Exchange authorization code for access token
if (isset($_GET['code'])) {
    $code = $_GET['code'];

// 2. Exchange code for access token
    $data = [
        "code" => $code,
        "client_id" => CLIENT_ID,
        "client_secret" => CLIENT_SECRET,
        "redirect_uri" => REDIRECT_URI,
        "grant_type" => "authorization_code"
    ];

    $ch = curl_init(TOKEN_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $token = json_decode($response, true);

     // 3. Fetch user info with access token
    if (isset($token['access_token'])) {
        $ch = curl_init(USERINFO_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $token['access_token']]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $userinfo = curl_exec($ch);
        curl_close($ch);

        $user = json_decode($userinfo, true);

        // Store user in session (instead of database)
        $_SESSION['user'] = $user;

        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error getting access token.";
    }
} else {
    echo "No code returned.";
}
