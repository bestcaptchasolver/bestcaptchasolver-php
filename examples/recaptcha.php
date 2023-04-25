#!/usr/bin/php

<?php

require('../lib/bestcaptchasolver.php');      // load API library

# for more details check https://bestcaptchasolver.com/captchabypass-api
function test_api() {
    $ACCESS_TOKEN = 'ACCESS_TOKEN_HERE';
    $PAGE_URL = 'PAGE_URL_HERE';
    $SITE_KEY = 'SITE_KEY_HERE';

    $bcs = new BestCaptchaSolver($ACCESS_TOKEN);      // get token from https://bestcaptchasolver.com/account
    // check account balance
    $balance = $bcs->account_balance();       // get balance
    echo "Balance: $balance";

    echo ' Submitting recaptcha...';
    $p = array();
    $p['page_url'] = $PAGE_URL;
    $p['site_key'] = $SITE_KEY;

    // other parameters
    // ----------------------------------------------------------------------
    // reCAPTCHA type(s) - optional, defaults to 1
    // ---------------------------------------------
    // 1 - v2
    // 2 - invisible
    // 3 - v3
    // 4 - enterprise v2
    // 5 - enterprise v3
    //
    //$p["type"] = "1";
    //
    //$p["v3_action"] = "home";    // action used when solving v3 reCaptcha, optional
    //$p["v3_min_score"] = "0.3";  // min score to target when solving v3, optional
    //$p['domain'] = "www.google.com";      // used in loading reCAPTCHA, in some cases it works better with - recaptcha.net
    //$p["data_s"] = "recaptcha data-s parameter used in loading reCAPTCHA"; // optional
    //$p["cookie_input"] = "a=b;c=d";              // used in solving of reCAPTCHA, optional
    //$p["proxy"] = "user:pass@191.123.43.34:3001";     // proxy with/out authentication, optional
    //$p["affiliate_id"] = "affiliate_id";         // get it from /account, optional

    $id = $bcs->submit_recaptcha($p);
    // get response now that we have the ID
    $gresponse = NULL;
    while($gresponse === NULL) {
        $gresponse = $bcs->retrieve($id)['gresponse'];  // get the image text (if completed)
        sleep(5);                  // retry every 5 seconds
    }

    // completed at this point
    echo " Recaptcha response: $gresponse";
    // $proxy_status = $bcs->retrieve($id)['proxy_status'];
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
