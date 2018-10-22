#!/usr/bin/php

<?php

require('lib/bestcaptchasolver.php');      // load API library
// Test method

function test_api() {
    $ACCESS_TOKEN = 'your_access_token';
    $PAGE_URL = 'page_url_here';
    $SITE_KEY = 'site_key_here';

    $bcs = new BestCaptchaSolver($ACCESS_TOKEN);      // get token from https://bestcaptchasolver.com/account
    // check account balance
    $balance = $bcs->account_balance();       // get balance
    echo "Balance: $balance";

    // works
    echo ' Solving captcha ...';
    $p = array();
    $p['image'] = 'captcha.jpg';
    //$p['case_sensitive'] = FALSE;        // TRUE or FALSE
    //$p['affiliate_id'] = 'affiliate_id';  // get it from /account
    $id = $bcs->submit_image_captcha($p);
    $image_text = NULL;
    while($image_text === NULL) {
        $image_text = $bcs->retrieve($id)['text'];  // get the image text (if completed)
        sleep(2);                  // retry every 2 seconds
    }
    echo " Captcha text: $image_text";
    // solve recaptcha
    echo ' Submitting recaptcha...';
    $p = array();
    $p['page_url'] = $PAGE_URL;
    $p['site_key'] = $SITE_KEY;

    // optional parameters
    //$p["type"] = "1";        // 1 - regular, 2 - invisible, 3 - v3, default: 1
    //$p["v3_action"] = "home";    // action used when solving v3 reCaptcha
    //$p["v3_min_score"] = "0.3";  // min score to target when solving v3
    //$p["proxy"] = "user:pass@191.123.43.34";     // proxy with/out authentication
    //$p["affiliate_id"] = "affiliate_id";         // get it from /account

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
