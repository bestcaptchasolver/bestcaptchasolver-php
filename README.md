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

You can give it a b64 encoded string or a file path

``` php
$id = $bcs->submit_image_captcha('captcha.jpg');
```
Optionally, you can give it a 2nd argument (boolean), for case sensitivity

**Retrieve image text**

Once you have the captcha ID, you can check for completion of captcha
```php
$image_text = NULL;
while($image_text === NULL) {
    $image_text = $bcs->retrieve($id);  // get the image text (if completed)
    sleep(2);                           // retry every 2 seconds
}
```

**Submit recaptcha details**

For recaptcha submission there are two things that are required.
- page_url
- site_key
``` php
$id = $bcs->submit_recaptcha($PAGE_URL, $SITE_KEY);
```
Optionally, you can give it a 3rd argument (string) for proxy, in the following format:
`12.34.56.78:1234` or `user:password@12.34.56.78:1234` if authentication is required

**Retrieve recaptcha gresponse**

Just as the image captcha, once you have the ID, you can start checking for it's
completion using the same retrieve method. The response (when ready) will be a gresponse code

```php
$gresponse = NULL;
while($image_text === NULL) {
    $gresponse = $bcs->retrieve($id);  // get the image text (if completed)
    sleep(2);                           // retry every 2 seconds
}
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

