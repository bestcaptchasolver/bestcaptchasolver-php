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

    echo ' Submitting geetest ...';
    $p = array();
    $p['domain'] = 'DOMAIN_HERE';
    $p['gt'] = 'GT_HERE';
    $p['challenge'] = 'CHALLENGE_HERE';
    //$p['api_server'] = 'GT_DOMAIN_HERE';  // optional
    //$p['user_agent'] = 'user agent here';        // optional
    //$p['proxy'] = 'user:pass@123.45.67.89:3031'; // optional
    //$p["affiliate_id"] = "affiliate_id";         // get it from /account

    $id = $bcs->submit_geetest($p);
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
        echo "Error occurred: " . $ex->getMessage();     // print error
    }
}

main();         // run main function
?>
