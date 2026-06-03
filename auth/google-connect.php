<?php
require_once __DIR__ . '/../includes/google_config.php';
require_once __DIR__ . '/../includes/db_connect.php';

if (isset($_GET['code'])) {
    $token = $googleClient->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token['error'])) {
        $googleClient->setAccessToken($token['access_token']);
        $google_service = new Google_Service_Oauth2($googleClient);
        $data = $google_service->userinfo->get();

        $_SESSION['user_email'] = $data['email'];
        $_SESSION['user_name'] = $data['name'];
        $_SESSION['profile_pic'] = $data['picture'];

        $collection = $db->users;
        $user = $collection->findOne(['email' => $data['email']]);
        if (!$user) {
            $collection->insertOne([
                'email' => $data['email'],
                'name' => $data['name'],
                'picture' => $data['picture'],
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ]);
        }

        header("Location: index.php");
        exit;
    }
}
header("Location: login.php");
exit;
