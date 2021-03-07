<?php
declare(strict_types=1);

namespace App\Enums;

class ErrorCodes
{
    public const GENERAL_ERROR = 'general_error';
    public const GENERAL_ERROR_TRANSLATION = 'Something wrong happened, please contact support';

    public const RESOURCE_NOT_FOUND = 'resource_not_found';
    public const RESOURCE_NOT_FOUND_TRANSLATION = 'Resource not found.';

    public const CATEGORY_VALIDATION_ERROR = 'category_validation_error';
    public const CATEGORY_VALIDATION_ERROR_TRANSLATION = 'Incorrect category data provided';

    public const CATEGORY_NOT_FOUND = 'category_not_found';
    public const CATEGORY_NOT_FOUND_TRANSLATION = 'Category not found';

    public const CANNOT_ADD_PRODUCT = 'cannot_add_product';
    public const CANNOT_ADD_PRODUCT_TRANSLATION = 'Something wrong happened while adding the product.';

    public const PRODUCT_VALIDATION_ERROR = 'product_validation_error';
    public const PRODUCT_VALIDATION_ERROR_TRANSLATION = 'The provided data is incorrect';

    public const PRODUCT_NOT_FOUND = 'product_not_found';
    public const PRODUCT_NOT_FOUND_TRANSLATION = 'Product not found';

    public const ERRORS = [
        self::GENERAL_ERROR => self::GENERAL_ERROR_TRANSLATION,
        self::RESOURCE_NOT_FOUND => self::RESOURCE_NOT_FOUND_TRANSLATION,
        self::CATEGORY_VALIDATION_ERROR => self::CATEGORY_VALIDATION_ERROR_TRANSLATION,
        self::CATEGORY_NOT_FOUND => self::CATEGORY_NOT_FOUND_TRANSLATION,
        self::CANNOT_ADD_PRODUCT => self::CANNOT_ADD_PRODUCT_TRANSLATION,
        self::PRODUCT_VALIDATION_ERROR => self::PRODUCT_VALIDATION_ERROR_TRANSLATION,
        self::PRODUCT_NOT_FOUND => self::PRODUCT_NOT_FOUND_TRANSLATION,
    ];

    public static function translate(string $errorCode): string
    {
        $keys = array_keys(self::ERRORS);

        if (in_array($errorCode, $keys)) {
            return self::ERRORS[$errorCode];
        }

        return self::ERRORS[self::GENERAL_ERROR];
    }
}
