BestCaptchaSolver.com php API wrapper
=========================================

bestcaptchasolver-php is a super easy to use bypass captcha php API wrapper for bestcaptchasolver.com captcha service

## Installation

    composer require bestcaptchasolver/bestcaptchasolver

or
    
    git clone https://github.com/bestcaptchasolver/bestcaptchasolver-php

## How to use?

Simply require the module, set the auth details and start using the captcha service:

``` php
require('lib/bestcaptchasolver.php'); 
```

Initialize library with access token

Get token from [https://bestcaptchasolver.com/account](https://bestcaptchasolver.com/account)
``` php
$bcs = new BestCaptchaSolver($ACCESS_TOKEN);   
```

Once you've set your authentication details, you can start using the API

**Get balance**

Returns balance in USD
``` php
$balance = $bcs->account_balance();
```

**Submit image captcha**

You can give it a b64 encoded string or a file path as `image` parameter

``` php
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
```

**Retrieve image text**

Once you have the captcha ID, you can check for completion of captcha
```php
$image_text = NULL;
while($image_text === NULL) {
    $image_text = $bcs->retrieve($id)['text'];  // get the image text (if completed)
    sleep(2);                           // retry every 2 seconds
}
```

**Submit recaptcha details**

For recaptcha submission there are two parameters that are required an others that are optional
- page_url
- site_key
- type (optional, defaults to 1 if not given)
    - `1` - v2
    - `2` - invisible
    - `3` - v3
    - `4` - enterprise v2
    - `5` - enterprise v3
- v3_action (optional)
- v3_min_score (optional)
- data_s (optional)
- cookie_input (optional)
- user_agent (optional)
- affiliate_id (optional)
- proxy (optional)

Check the [/api](https://bestcaptchasolver.com/api) page for more about this parameters

``` php
$p = array();
$p['page_url'] = $PAGE_URL;
$p['site_key'] = $SITE_KEY;
$id = $bcs->submit_recaptcha($p);
```

**Retrieve recaptcha gresponse**

Just as the image captcha, once you have the ID, you can start checking for it's
completion using the same retrieve method. The response (when ready) will be a gresponse code

```php
$gresponse = NULL;
while($gresponse === NULL) {
    $gresponse = $bcs->retrieve($id)['gresponse'];  // get the image text (if completed)
    sleep(2);                           // retry every 2 seconds
}
```

**If submitted with proxy, get proxy status**
```
$proxy_status = $bcs->retrieve($id)['proxy_status']
```

**Geetest**
- domain
- gt
- challenge
- api_server (optional)

```php
$p = array();
$p['domain'] = 'DOMAIN_HERE';
$p['gt'] = 'GT_HERE';
$p['challenge'] = 'CHALLENGE_HERE';
//$p['api_server'] = 'GT_DOMAIN_HERE';  // optional
//$p["affiliate_id"] = "affiliate_id";
$id = $bcs->submit_geetest($p);
// get solution
$solution = $bcs->retrieve($id)['solution'];  // get the image text (if completed)
```


**GeetestV4**
- domain
- captchaid

**Important:** This is not the captchaid that's in our system that you receive while submitting a captcha. Gather this from HTML source of page with geetestv4 captcha, inside the `<script>` tag you'll find a link that looks like this: https://i.imgur.com/XcZd47y.png

```php
$p = array();
$p['domain'] = 'https://example.com';
$p['captchaid'] = '647f5ed2ed8acb4be36784e01556bb71';
//$p["affiliate_id"] = "affiliate_id";
$id = $bcs->submit_geetest_v4($p);
// get solution
$solution = $bcs->retrieve($id)['solution'];  // get the image text (if completed)
```

**Capy**
- page_url
- site_key

```php
$p = array();
$p['page_url'] = 'PAGE_URL_HERE';
$p['site_key'] = 'SITE_KEY_HERE';
//$p["affiliate_id"] = "affiliate_id";
$id = $bcs->submit_capy($p);
// get solution
$solution = $bcs->retrieve($id)['solution'];
```

**hCaptcha**
- page_url
- site_key
- invisible (optional)
- payload (optional)
- user_agent (optional)
- proxy (optional)

```php
$p = array();
$p['page_url'] = 'PAGE_URL_HERE';
$p['site_key'] = 'SITE_KEY_HERE';
// $p['invisible'] = true;
// $p['payload'] = array(
//     "rqdata" => "taken from web requests"
// );
// $p["affiliate_id"] = "affiliate_id";         // get it from /account
// $p['user_agent'] = 'user agent here';
// $p['proxy'] = '123.234.241.123:1234';

$id = $bcs->submit_hcaptcha($p);
// get solution
$solution = $bcs->retrieve($id)['solution'];
```

**FunCaptcha (Arkose Labs)**
- page_url
- s_url
- site_key

```php
$p = array();
$p['page_url'] = 'https://abc.com';
$p['s_url'] = 'https://api.arkoselabs.com';
$p['site_key'] = '11111111-1111-1111-1111-111111111111';
//$p['data'] = '{"x":"y"}';                    // optional
//$p["affiliate_id"] = "affiliate_id";         // get it from /account
$id = $bcs->submit_funcaptcha($p);
$solution = $bcs->retrieve($id)['solution'];
```

**Task**
- template_name
- page_url
- variables
- user_agent (optional)
- proxy (optional)
- affiliate_id (optional)

```php
$p = array();
$p['page_url'] = 'https://bestcaptchasolver.com/automation/login';
$p['template_name'] = 'Login test page';
$p['variables'] = array(
  "username" => "roger", "password" => "mypass"
);
// $p["affiliate_id"] = "affiliate_id";         // get it from /account
// $p['user_agent'] = 'user agent here';
// $p['proxy'] = '123.234.241.123:1234';
$id = $bcs->submit_task($p);
```

#### Task pushVariables
Update task variables while it is being solved by the worker. Useful when dealing with data / variables, of which
value you don't know, only after a certain step or action of the task. For example, in websites that require 2 factor
authentication code.

When the task (while running on workers machine) is getting to an action defined in the template, that requires a variable, but variable was not
set with the task submission, it will wait until the variable is updated through push.

The `bcs.task_push_variables(captcha_id, push_variables)` method can be used as many times as it is needed.

```python
$bcs->task_push_variables($id, array(
   "tfa_code" => "1693"
));
```

**Set captcha bad**

When a captcha was solved wrong by our workers, you can notify the server with it's ID,
so we know something went wrong.

``` php
$bcs->set_captcha_bad(50); 
```

## Examples
Check example.php

## License
API library is licensed under the MIT License

## More information
More details about the server-side API can be found [here](https://bestcaptchasolver.com/api)


<sup><sub>captcha, bypasscaptcha, decaptcher, decaptcha, 2captcha, deathbycaptcha, anticaptcha, 
bypassrecaptchav2, bypassnocaptcharecaptcha, bypassinvisiblerecaptcha, captchaservicesforrecaptchav2, 
recaptchav2captchasolver, googlerecaptchasolver, recaptchasolverpython, recaptchabypassscript, bestcaptchasolver</sup></sub>

