<?php
    include './lib.php';
    echo "iSign.io API PHP example\n";
    $url = 'https://developers.isign.io';
    $accessToken = ''; //Enter valid developer access token here.
    $file = isset($argv[1])?$argv[1]:'./test.pdf';
    $phone = isset($argv[2])?$argv[2]:'+37060000007';
    $code = isset($argv[3])?$argv[3]:'51001091072';

    if (empty($accessToken)) {
        echo "Access Token is required. Enter at line 5.\n";
        exit;
    }

    echo "Requesting prepare:\n";
    $prepared = request($url, $accessToken, 'mobile/sign', [
        'type' => 'pdf',
        'phone' => $phone,
        'code' => $code,
        'message' => 'Please sign my pdf.',
        'language' => 'EN',
        'timestamp' => true,
        'pdf' => [
            'files' => [
                [
                    'name' => substr($file, strrpos($file, "/")+1),
                    'content' => base64_encode(file_get_contents($file)),
                    'digest' => sha1_file($file)
                ]
            ],
            'reason' => 'Sutartis',
            'location' => 'Vilnius, Lietuva',
            'contact' => 'Seventh Testnumber'
        ]
    ]);
    echo "Responded: ".$prepared['status']."\n";

    if ($prepared['status'] != 'ok') {
        var_dump($prepared['message']);
        exit;
    }

    echo "[OK]\n";
    echo "Signing token: [ " . $prepared['token'] . " ]\n";
    echo "Your phone will receive sign request with\nVerification code: [ " . $prepared['control_code'] . " ]\n";

    echo "Requesting sign status:\n";

    for ($i=0;$i<60; $i+=5) {
        $statusResponse = request($url, $accessToken, 'mobile/sign/status/' . $prepared['token'], [], false);
        echo $statusResponse['status']."\n";

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
        sleep(5);
    }