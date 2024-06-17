<?php

declare(strict_types=1);

namespace App\Foundation;

enum ErrorCode: string
{
    /*
     * General error codes
     */
    case APPLICATION_ERROR = 'application_error';
    case BUSINESS_ERROR = 'business_error';
    case INVALID_PARAMETERS = 'invalid_parameters';
    case ENTITY_NOT_FOUND = 'entity_not_found';
    case ENTITY_ALREADY_EXISTS = 'entity_already_exists';
    case INCORRECT_DATE_RANGE = 'incorrect_date_range';
    case DATE_MISMATCH = 'date_mismatch';

    /*
     * Example auth error codes
     */
    case USER_ALREADY_EXIST = 'user_already_exists';
    case INCORRECT_CREDENTIALS = 'incorrect_credentials';
    case AUTHENTICATION_ERROR = 'authentication_error';
    case BAD_REQUEST = 'bad_request';

    public function getMessage(): string
    {
        $descriptions = [
            self::APPLICATION_ERROR->value => 'Unable to process request',
            self::BUSINESS_ERROR->value => 'Unable to process request',
            self::INVALID_PARAMETERS->value => 'Invalid request parameters',
            self::ENTITY_NOT_FOUND->value => 'Target entity not found',
            self::ENTITY_ALREADY_EXISTS->value => 'Entity already exists',
            self::INCORRECT_DATE_RANGE->value => 'Incorrect date range',
            self::INCORRECT_CREDENTIALS->value => 'Incorrect credentials',
            self::AUTHENTICATION_ERROR->value => 'Authentication error',
            self::DATE_MISMATCH->value => 'Date mismatch',
            self::BAD_REQUEST->value => 'Bad request',
        ];

        return $descriptions[$this->value] ?? 'Unknown error';
    }
}
