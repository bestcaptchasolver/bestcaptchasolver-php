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

    echo ' Submitting hCaptcha ...';
    $p = array();
    $p['page_url'] = 'PAGE_URL_HERE';
    $p['site_key'] = 'SITE_KEY_HERE';
    // $p['invisible'] = true;
    // $p['payload'] = array(
    //     "rqdata" => "taken from web requests"
    // );
    // $p['domain'] = 'hcaptcha.com';
    // $p["affiliate_id"] = "affiliate_id";         // get it from /account
    // $p['user_agent'] = 'user agent here';
    // $p['proxy'] = '123.234.241.123:1234';

    $id = $bcs->submit_hcaptcha($p);
    // get response now that we have the ID
    $solution = NULL;
    while($solution === NULL) {
        $solution = $bcs->retrieve($id)['solution'];  // get the image text (if completed)
        sleep(5);                  // retry every 5 seconds
    }

    // completed at this point
    echo "Response: $solution";
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
