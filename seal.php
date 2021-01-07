<?php
    include './lib.php';
    echo "Developers.dokobit.com WS API Seal PHP example\n";
    $url = 'https://developers.dokobit.com';
    $accessToken = ''; //Enter valid developer access token here.
    $file = isset($argv[1])?$argv[1]:'./test.pdf';

    if (empty($accessToken)) {
        echo "Access Token is required. Enter at line 5.\n";
        exit;
    }

    echo "Sealing:\n";
    $prepared = request($url, $accessToken, 'seal', [
        'type' => 'pdf',
        'language' => 'EN',
        'pdf' => [
            'certify' => true,
            'files' => [
                [
                    'name' => substr($file, strrpos($file, "/")+1),
                    'content' => base64_encode(file_get_contents($file)),
                    'digest' => hash_file('sha256', $file)
                ]
            ],
            'reason' => 'Certify',
            'location' => 'Vilnius, Lietuva',
            'contact' => 'DokobitTest'
        ]
    ]);
    echo "Responded: ".$prepared['status']."\n";

   if ($prepared['status'] == 'ok') {
        file_put_contents(
            __DIR__ . '/test_sealed.pdf', 
            base64_decode($prepared['file']['content'])
        );
        echo "File sealed. Check ./test_sealed.pdf\n";
        exit;
    } elseif ($prepared['status'] == 'error') {
        var_dump($prepared['message']);
        exit;
    }