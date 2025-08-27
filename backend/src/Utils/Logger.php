<?php

namespace App\Utils;

class Logger 
{
    /**
     * Log debug message
     */
    public static function debug($message, array $context = [])
    {
        error_log("[DEBUG] " . self::formatMessage($message, $context));
    }

    /**
     * Log error message
     */
    public static function error($message, array $context = [])
    {
        error_log("[ERROR] " . self::formatMessage($message, $context));
    }

    /**
     * Log info message
     */
    public static function info($message, array $context = [])
    {
        error_log("[INFO] " . self::formatMessage($message, $context));
    }

    /**
     * Format log message with context
     */    private static function formatMessage($message, array $context = []): string
    {
        if (empty($context)) {
            return $message;
        }

        $replace = [];
        foreach ($context as $key => $val) {
            if (is_string($val)) {
                $replace['{' . $key . '}'] = $val;
            } elseif (is_object($val) && method_exists($val, '__toString')) {
                $replace['{' . $key . '}'] = $val->__toString();
            } elseif (is_array($val)) {
                $replace['{' . $key . '}'] = json_encode($val);
            } else {
                $replace['{' . $key . '}'] = var_export($val, true);
            }
        }

        return strtr($message, $replace);
    }
}
