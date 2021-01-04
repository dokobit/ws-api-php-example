<?php
    include './lib.php';
    echo "developers.dokobit.com WS API Smart-ID signing PHP example\n";
    $url = 'https://developers.dokobit.com';
    $accessToken = ''; //Enter valid developer access token here.
    $country = isset($argv[1])?$argv[1]:'lt';
    $code = isset($argv[2])?$argv[2]:'10101010005';
    /**
    * Smart-ID test data can be found at https://support.dokobit.com/article/667-mobile-id-and-smart-id-test-data
    */
    $file = isset($argv[3])?$argv[3]:'./test.pdf';

    if (empty($accessToken)) {
        echo "Access Token is required. Enter at line 5.\n";
        exit;
    }

    echo "Requesting prepare:\n";
    $prepared = request($url, $accessToken, 'smartid/sign', [
        'type' => 'pdf',
        'code' => $code,
        'country' => $country,
        'language' => 'EN',
        'timestamp' => true,
        'pdf' => [
            'files' => [
                [
                    'name' => substr($file, strrpos($file, "/")+1),
                    'content' => base64_encode(file_get_contents($file)),
                    'digest' => sha256_file($file)
                ]
            ],
            'reason' => 'Agreement',
            'location' => 'Vilnius, Lietuva',
            'contact' => 'Seventh Testnumber'
        ]
    ]);
    echo "Responded: [".$prepared['status']."]\n";

    if ($prepared['status'] != 'ok') {
        print_r($prepared);
        exit;
    }
    echo "Signing token: [ " . $prepared['token'] . " ]\n";
    echo "Your phone will receive Smart-ID signing request with\nVerification code: [ " . $prepared['control_code'] . " ]\n";

    echo "Requesting status:\n";

    $time = 120;
    while ($time > 0) {
        $statusResponse = request($url, $accessToken, 'smartid/sign/status/' . $prepared['token'], [], false);
        echo "Status: [".$statusResponse['status']."]\n";
        if ($statusResponse['status'] == 'ok') {
            file_put_contents(
                __DIR__ . '/test_signed.pdf', 
                base64_decode($statusResponse['file']['content'])
            );
            echo "File signed. Check ./test_signed.pdf\n";
            exit;
        } elseif ($statusResponse['status'] == 'error') {
            var_dump($statusResponse['message']);
            exit;
        }
        sleep(2);
        $time -=2;
    }
