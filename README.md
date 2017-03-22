Installation
============

Step 1: Download and install the Bundle
---------------------------

Get access to the bundle's git repository

Enable an additional composer repository by adding the next section to the `composer.json` file in the target project

```json
"repositories" : [{
        "type" : "vcs",
        "url" : "https://github.com/appfoundations/AppFoundationsCommunicationsBundle.git"
    }],
```

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require appfoundations/communications-bundle "dev-master"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new \AppFoundations\CommunicationsBundle\LogiccFoundationsCommunicationsBundle()
        );

        // ...
    }

    // ...
}
```

Step 3: Provide a valid SendGrid api key parameter
--------------------------------------------------
Add a `app_foundations_communications.sendgrid.key` parameter to the global application parameters, containing a valid SenGrid api key.  

Step 4: Call the Hermes service
-------------------------------
Consume the `app_foundations_communications.hermes_email_service` in your application to send email messages.