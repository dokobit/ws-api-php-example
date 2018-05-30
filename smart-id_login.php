<?php
    include './lib.php';
    echo "developers.dokobit.com WS API Smart-ID login PHP example\n";
    $url = 'https://developers.dokobit.com';
    $accessToken = ''; //Enter valid developer access token here.
    $country = isset($argv[1])?$argv[1]:'ee';
    $code = isset($argv[2])?$argv[2]:'51001091072';

    if (empty($accessToken)) {
        echo "Access Token is required. Enter at line 5.\n";
        exit;
    }

    echo "Requesting login:\n";
    $prepared = request($url, $accessToken, 'smartid/login', [
        'code' => $code,
        'country_code' => $country
    ]);
    echo "Responded: [".$prepared['status']."]\n";

    if ($prepared['status'] != 'ok') {
        print_r($prepared);
        exit;
    }
    echo "Token: [ " . $prepared['token'] . " ]\n";
    echo "Your phone will receive Smart-ID authentication request with\nVerification code: [ " . $prepared['control_code'] . " ]\n";

    echo "Requesting status:\n";
    $time = 120;
    while ($time > 0) {
        $statusResponse = request($url, $accessToken, 'smartid/login/status/' . $prepared['token'], [], false);
        echo "Status: [".$statusResponse['status']."]\n";
        if ($statusResponse['status'] == 'ok') {
            print_r($statusResponse);
            exit;
        } elseif ($statusResponse['status'] == 'error') {
            var_dump($statusResponse['message']);
            exit;
        }
        sleep(2);
        $time -=2;
    }