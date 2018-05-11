#!/usr/bin/php

<?php

require('lib/bestcaptchasolver.php');      // load API library
// Test method

function test_api() {
    $ACCESS_TOKEN = 'your_access_token';
    $PAGE_URL = 'recaptcha_page_url';
    $SITE_KEY = 'recaptcha_site_key';

    $bcs = new BestCaptchaSolver($ACCESS_TOKEN);      // get token from https://bestcaptchasolver.com/account
    // check account balance
    $balance = $bcs->account_balance();       // get balance
    echo "Balance: $balance";

    // works
    echo 'Solving captcha ...';
    $id = $bcs->submit_image_captcha('captcha.jpg');
    $image_text = NULL;
    while($image_text === NULL) {
        $image_text = $bcs->retrieve($id);  // get the image text (if completed)
        sleep(2);                  // retry every 2 seconds
    }
    echo "Captcha text: $image_text";
    // solve recaptcha
    echo 'Submitting recaptcha...';
    $id = $bcs->submit_recaptcha($PAGE_URL, $SITE_KEY);
    // get response now that we have the ID
    $gresponse = NULL;
    while($gresponse === NULL) {
        $gresponse = $bcs->retrieve($id);  // get the image text (if completed)
        sleep(5);                  // retry every 5 seconds
    }

    // completed at this point
    echo "Recaptcha response: $gresponse";

    // $bcs->submit_image_captcha('captcha.jpg', true);     // case sensitive completion of image captcha
    // $bcs->submit_recaptcha($PAGE_URL, $SITE_KEY, '126.34.43.3:123'); // use proxy, works with user:pass@ip:port as well
    // $bcs->set_captcha_bad(50);       // set bad captcha for specific id
}

// Main method
function main() {
    try {
        test_api();             // test API
    } catch (Exception $ex) {
        echo "Error occured: " . $ex->getMessage();     // print error
    }
}

main();         // run main function
?>
