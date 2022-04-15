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

    echo ' Submitting geetestv4 ...';
    $p = array();
    $p['domain'] = 'https://example.com';
    $p['captchaid'] = '647f5ed2ed8acb4be36784e01556bb71';
    //$p["affiliate_id"] = "affiliate_id";         // get it from /account

    $id = $bcs->submit_geetest_v4($p);
    echo ' Waiting for solution ...';
    // get response now that we have the ID
    $solution = NULL;
    while($solution === NULL) {
        $solution = $bcs->retrieve($id)['solution'];  // get the image text (if completed)
        sleep(5);                  // retry every 5 seconds
    }

    // completed at this point
    var_dump($solution);
    // $bcs->set_captcha_bad($id);       // set bad captcha for specific id
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
