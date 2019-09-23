#!/usr/bin/php

<?php

require('../lib/bestcaptchasolver.php');      // load API library

# for more details check https://bestcaptchasolver.com/captchabypass-api
function test_api() {
    $ACCESS_TOKEN = 'ACCESS_TOKEN_HERE';

    $bcs = new BestCaptchaSolver($ACCESS_TOKEN);      // get token from https://bestcaptchasolver.com/account
    // check account balance
    $balance = $bcs->account_balance();       // get balance
    echo "Balance: $balance";

    echo ' Solving captcha ...';
    $p = array();
    $p['image'] = '../captcha.jpg';
    // $p['is_case'] = FALSE;         // is case sensitive, default: False
    // $p['is_phrase'] = FALSE;       // has at least one space, default: FALSE, optional
    // $p['is_math'] = FALSE;         // math captcha calculation, default: FALSE, optional
    // $p['alphanumeric'] = 1;        // 1 - digits only, 2 - letters only, default: all, optional
    // $p['minlength'] = 2;           // minimum text length, default: any, optional
    // $p['maxlength'] = 3;           // maximum text length, default: any, optional
    // $p['affiliate_id'] = 'affiliate_id';  // get it from /account, optional

    $id = $bcs->submit_image_captcha($p);
    $image_text = NULL;
    while($image_text === NULL) {
        $image_text = $bcs->retrieve($id)['text'];  // get the image text (if completed)
        sleep(2);                  // retry every 2 seconds
    }
    echo " Captcha text: $image_text";

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
