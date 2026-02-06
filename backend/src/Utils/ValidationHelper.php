<?php

declare(strict_types=1);

namespace App\Utils;

use InvalidArgumentException;

class ValidationHelper
{
    /**
     * Validate that a value is a non-empty string
     *
     * @param mixed $value
     * @param string $fieldName
     * @throws InvalidArgumentException
     */
    public static function validateString($value, string $fieldName): string
    {
        if (!is_string($value) || empty(trim($value))) {
            throw new InvalidArgumentException("{$fieldName} must be a non-empty string");
        }
        return trim($value);
    }

    /**
     * Validate that a value is a positive integer
     *
     * @param mixed $value
     * @param string $fieldName
     * @throws InvalidArgumentException
     */
    public static function validatePositiveInt($value, string $fieldName): int
    {
        if (!is_numeric($value) || (int)$value <= 0) {
            throw new InvalidArgumentException("{$fieldName} must be a positive integer");
        }
        return (int)$value;
    }

    /**
     * Validate that a value is one of the allowed options
     *
     * @param mixed $value
     * @param array $allowed
     * @param string $fieldName
     * @throws InvalidArgumentException
     */
    public static function validateEnum($value, array $allowed, string $fieldName): string
    {
        if (!in_array($value, $allowed, true)) {
            throw new InvalidArgumentException("{$fieldName} must be one of: " . implode(', ', $allowed));
        }
        return (string)$value;
    }

    /**
     * Validate that a value is a valid ID string
     *
     * @param mixed $value
     * @param string $fieldName
     * @throws InvalidArgumentException
     */
    public static function validateId($value, string $fieldName = 'ID'): string
    {
        // IDs are typically strings, non-empty
        return self::validateString($value, $fieldName);
    }

    /**
     * Validate required fields in an array
     *
     * @param array $data
     * @param array $requiredFields
     * @throws InvalidArgumentException
     */
    public static function validateRequiredFields(array $data, array $requiredFields): void
    {
        $missing = [];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            throw new InvalidArgumentException("Missing required fields: " . implode(', ', $missing));
        }
    }
}
