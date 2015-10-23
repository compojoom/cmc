# CMC - Mailchimp® for Joomla!™ [![Build Status](http://62.75.223.61/api/badge/github.com/compojoom/cmc/status.svg?branch=master)](http://62.75.223.61/github.com/compojoom/cmc)

CMC is an extension for Joomla 2.5 and 3.x that integrates with the Mailchimp API. It currently supports:

* double way synchronisation of lists & users
* adding users to Mailchimp through the joomla backend
* newsletter signup module

The Joomla zip package can be found here: https://compojoom.com/downloads/cmc
Support is offered through the forum here: https://compojoom.com/forum/cmc-mailchimp-for-joomla

# Tests
To prepare the system tests (Selenium) to be run in your local machine rename the file `tests/acceptance.suite.dist.yml` to `tests/acceptance.suite.yml`. Afterwards, please edit the file according to your system needs.

```bash
$ composer install
$ vendor/bin/robo
$ vendor/bin/robo run:tests
```

