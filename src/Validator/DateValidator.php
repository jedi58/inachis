<?php

namespace App\Validator;

use App\Exception\InvalidTimezoneException;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

/**
 * Class DateValidator
 * @package App\Validator
 */
class DateValidator
{
    public function validateTimezone(?string $timezone): string
    {
        if (empty($timezone)) {
            throw new InvalidArgumentException('The timezone can not be empty.');
        }

        if (!in_array($timezone, \DateTimeZone::listIdentifiers())) {
            throw new InvalidTimezoneException(sprintf(
                '"%s" is not a valid timezone',
                $timezone
            ));
        }

        return $timezone;
    }
}
