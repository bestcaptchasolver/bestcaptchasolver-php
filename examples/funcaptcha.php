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

    echo ' Submitting funcaptcha ...';
    $p = array();
    $p['page_url'] = 'https://abc.com';
    $p['s_url'] = 'https://api.arkoselabs.com';
    $p['site_key'] = '11111111-1111-1111-1111-111111111111';
    //$p['data'] = '{"x":"y"}';                    // optional
    //$p["affiliate_id"] = "affiliate_id";         // get it from /account

    $id = $bcs->submit_funcaptcha($p);
    // get response now that we have the ID
    $solution = NULL;
    while($solution === NULL) {
        $solution = $bcs->retrieve($id)['solution'];  // get the image text (if completed)
        sleep(5);                  // retry every 5 seconds
    }

    // completed at this point
    echo "Solution: $solution";
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
