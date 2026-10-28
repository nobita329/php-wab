<?php

namespace App\Core;

class Request
{
    private static ?array $postData = null;
    private static ?array $getData = null;

    public static function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public static function isPost(): bool
    {
        return self::getMethod() === 'POST';
    }

    public static function isGet(): bool
    {
        return self::getMethod() === 'GET';
    }

    public static function isAjax(): bool
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
               (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json'));
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public static function post(string $key, mixed $default = null): mixed
    {
        $val = $_POST[$key] ?? $default;
        if (is_string($val)) {
            return trim($val);
        }
        return $val;
    }

    public static function all(): array
    {
        return array_merge($_GET, $_POST);
    }

    public static function ip(): string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP) ?: '127.0.0.1';
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return filter_var(trim($ips[0]), FILTER_VALIDATE_IP) ?: '127.0.0.1';
        }
        return filter_var($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', FILTER_VALIDATE_IP) ?: '127.0.0.1';
    }

    public static function userAgent(): string
    {
        return substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 255);
    }

    public static function checkCsrf(): bool
    {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        return Session::validateCsrfToken($token);
    }

    public static function validate(array $rules): array
    {
        $errors = [];
        $validated = [];

        foreach ($rules as $field => $fieldRules) {
            $value = self::post($field);
            $ruleList = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);

            foreach ($ruleList as $rule) {
                $param = null;
                if (str_contains($rule, ':')) {
                    [$rule, $param] = explode(':', $rule, 2);
                }

                switch ($rule) {
                    case 'required':
                        if ($value === null || $value === '') {
                            $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
                        }
                        break;

                    case 'email':
                        if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field] = 'Please provide a valid email address.';
                        }
                        break;

                    case 'min':
                        if ($value !== null && strlen((string)$value) < (int)$param) {
                            $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " must be at least {$param} characters.";
                        }
                        break;

                    case 'max':
                        if ($value !== null && strlen((string)$value) > (int)$param) {
                            $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " must not exceed {$param} characters.";
                        }
                        break;

                    case 'numeric':
                        if ($value !== null && $value !== '' && !is_numeric($value)) {
                            $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' must be a valid number.';
                        }
                        break;

                    case 'in':
                        $allowed = explode(',', (string)$param);
                        if ($value !== null && $value !== '' && !in_array($value, $allowed, true)) {
                            $errors[$field] = 'Selected value is invalid.';
                        }
                        break;
                }

                if (isset($errors[$field])) {
                    break; // Stop at first error for this field
                }
            }

            if (!isset($errors[$field])) {
                $validated[$field] = $value;
            }
        }

        return [$validated, $errors];
    }
}

