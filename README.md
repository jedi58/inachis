Inachis Framework
=======

![alt text](https://travis-ci.org/jedi58/inachis.svg?branch=master "Build status")
[![StyleCI](https://styleci.io/repos/12222371/shield)](https://styleci.io/repos/12222371)
[![Code Climate](https://codeclimate.com/github/jedi58/inachis/badges/gpa.svg)](https://codeclimate.com/github/jedi58/inachis)
[![Test Coverage](https://codeclimate.com/github/jedi58/inachis/badges/coverage.svg)](https://codeclimate.com/github/jedi58/inachis/coverage)

This is currently an experimental framework - more details will be provided as additions are made available.

##Installation using Composer
- Add the `jedi58/inachis` package to the require section of your composer.json file.
```{r, engine='bash', composer_install}
$  composer require jedi58/inachis
```
- Run `composer install`

##Deployment and configuration using Ansible
Environments deployed using Ansible hav etheir configuration stored in the usual YAML files. For the default passwords used by each service (MySQL, RabbitMQ, etc.) please refer to https://github.com/jedi58/inachis/blob/master/dev/ansible/vars/all.yml

For use in a production environment it is recommended that these passwords are NOT used.


##License
This code is released under the MIT License (MIT). Please see [License File](https://github.com/jedi58/inachis/blob/master/LICENSE) for more information. 
