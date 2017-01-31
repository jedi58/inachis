Inachis Framework
=======

[![TravisCI](https://travis-ci.org/jedi58/inachis.svg?branch=master)](https://travis-ci.org/jedi58/inachis)
[![StyleCI](https://styleci.io/repos/12222371/shield)](https://styleci.io/repos/12222371)
[![Code Climate](https://codeclimate.com/github/jedi58/inachis/badges/gpa.svg)](https://codeclimate.com/github/jedi58/inachis)
[![Coverage Status](https://coveralls.io/repos/github/jedi58/inachis/badge.svg?branch=master)](https://coveralls.io/github/jedi58/inachis?branch=master)

This is currently an experimental framework - more details will be provided as additions are made available (see the [CHANGELOG](CHANGELOG.md)).

##Installation using Composer
- Add the `jedi58/inachis` package to the require section of your composer.json file.
```{r, engine='bash', composer_install}
$  composer require jedi58/inachis
```
- Run `composer install`
 
If you would like to use the default templates then you will need `npm` and `gulp` so that you can then run `npm install` followed by `gulp` to build prepare the assets.


##Deployment and configuration using Ansible
Environments deployed using Ansible hav etheir configuration stored in the usual YAML files. For the default passwords used by each service (MySQL, RabbitMQ, etc.) please refer to https://github.com/jedi58/inachis/blob/master/dev/ansible/vars/all.yml

For use in a production environment it is recommended that these passwords are NOT used.


##Testing
Whilst this framework is still in progress the majority of it's testing is done using PHPUnit. To run these tests, from the root of your checkout you can run:

```{r, engine='bash'}
$  phpunit
```

You may however notice the warnings for the timezone (depending on your local PHP set-up). To avoid this it is recommended that you instead use the following:

```{r, engine='bash'}
$  ./build/run_phpunit.sh
```

There is also a `./check_psr2.sh` script to check that the code adhere's to the PSR-2 standard. To use this you will first need to ensure PHP CodeSniffer and Beautifer are available and in your `PATH`. If you haven't got these, they can be quickly installed using:

```{r, engine='bash'}
$  ./build/install_phpcs.sh
```

Once the interface is added the test suite will be expanded to include Behat.


##License
This code is released under the MIT License (MIT). Please see [License File](https://github.com/jedi58/inachis/blob/master/LICENSE) for more information. 
