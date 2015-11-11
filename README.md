# CMC - Mailchimp® for Joomla!™
[![Build Status](http://test01.compojoom.com/api/badges/compojoom/cmc/status.svg)](http://test01.compojoom.com/compojoom/cmc)

CMC is an extension for Joomla 2.5 and 3.x that integrates with the Mailchimp API. It currently supports:

* Double way synchronisation of lists & users
* Adding users to Mailchimp through the joomla backend
* Joomla / CB / JomSocial registration form integration
* Newsletter signup module with different templates

The Joomla zip package can be downloaded here: https://compojoom.com/downloads/cmc
Support is offered through the forum here: https://compojoom.com/forum/cmc-mailchimp-for-joomla

# Build
To build an installable CMC package just run the following commands (create jbuild.ini before):

```bash
$ composer install
$ vendor/bin/robo build
```

After that you find an installable zip file in the dist folder.

# Tests
To prepare the system tests (Selenium) to be run in your local machine rename the file `tests/acceptance.suite.dist.yml` to `tests/acceptance.suite.yml`. Afterwards, please edit the file according to your system needs.

```bash
$ composer install
$ vendor/bin/robo run:tests
```



