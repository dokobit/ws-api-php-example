<?php
    include './lib.php';
    echo "developers.dokobit.com WS API Mobile ID login PHP example\n";
    $url = 'https://developers.dokobit.com';
    $accessToken = ''; //Enter valid developer access token here.
    $phone = isset($argv[1])?$argv[1]:'+37060000666';
    $code = isset($argv[1])?$argv[1]:'50001018865';

    if (empty($accessToken)) {
        echo "Access Token is required. Enter at line 5.\n";
        exit;
    }

    echo "Requesting login:\n";
    $prepared = request($url, $accessToken, 'v2/mobile/login', [
        'code' => $code,
        'phone' => $phone
    ]);
    echo "Responded: [".$prepared['status']."]\n";

    if ($prepared['status'] != 'ok') {
        print_r($prepared);
        exit;
    }
    echo "Token: [ " . $prepared['token'] . " ]\n";
    echo "Your phone will receive Mobile ID authentication request with\nVerification code: [ " . $prepared['control_code'] . " ]\n";

    echo "Requesting status:\n";
    $time = 120;
    while ($time > 0) {
        $statusResponse = request($url, $accessToken, 'v2/mobile/login/status/' . $prepared['token'], [], false);
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
