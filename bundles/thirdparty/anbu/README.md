#Anbu

Front end debug and profiling for the Laravel Framework.

##Installation

Install using artisan for Laravel :

	php artisan bundle:install anbu

Now simply add anbu to your `application/bundles.php` with auto start enabled :

	return array('anbu' => array('auto' => true));

Finally, add Anbu to your View master template, or individual views with :

	<?php Anbu::render(); ?>

just after your opening `body` tag!

All done!

##Watching Variables

Simply use :

	Anbu::watch('descriptive name', $variable);

To output a variables current value into the watch tab.

You can use :

	Anbu::spy('descriptive name', $variable);

To pass an object by reference, which will displey the objects value at the time the view was loaded.

##Logging

Use laravels built in logging methods :

	Log::info('Oh hai!');

##SQL Queries

Will be logged automatically as they are executed!

##Config

If you are using jQuery in your template, you may wish to disable Anbu's included jQuery, to do this simply edit the config file at `bundles/anbu/config/display.php` and set the `include_jquery` index to false :

	'include_jquery' 	=>		false,


---

Enjoy using Anbu, and please report any glitches!
