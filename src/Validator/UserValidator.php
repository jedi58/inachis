<?php

namespace App\Validator;

use Symfony\Component\Console\Exception\InvalidArgumentException;

/**
 * This class is used to provide an example of integrating simple classes as
 * services into a Symfony application.
 *
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class UserValidator
{
    public function validateUsername(?string $username): string
    {
        if (empty($username)) {
            throw new InvalidArgumentException('The username can not be empty.');
        }

        if (1 !== preg_match('/^[a-z0-9_]+$/i', $username)) {
            throw new InvalidArgumentException('The username must contain only alphanumeric characters and underscores.');
        }

        return $username;
    }

    public function validatePassword(?string $plainPassword): string
    {
        if (empty($plainPassword)) {
            throw new InvalidArgumentException('The password can not be empty.');
        }

        if (mb_strlen(trim($plainPassword)) < 6) {
            throw new InvalidArgumentException('The password must be at least 6 characters long.');
        }

        return $plainPassword;
    }

    public function validateEmail(?string $email): string
    {
        if (empty($email)) {
            throw new InvalidArgumentException('The email can not be empty.');
        }

        if (false === mb_strpos($email, '@')) {
            throw new InvalidArgumentException('The email should look like a real email.');
        }

        return $email;
    }

    public function validateDisplayName(?string $displayName): string
    {
        if (empty($displayName)) {
            throw new InvalidArgumentException('The full name can not be empty.');
        }

        return $displayName;
    }
}
