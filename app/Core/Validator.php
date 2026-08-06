<?php
declare(strict_types=1);

namespace ThemeHub\Core;

/**
 * Validator Class
 * Validates request data against rules
 */
final class Validator
{
    private array $data;
    private array $rules;
    private array $errors = [];
    private array $messages = [];

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    public function validate(): array
    {
        foreach ($this->rules as $field => $rules) {
            $rules = is_string($rules) ? explode('|', $rules) : $rules;
            
            foreach ($rules as $rule) {
                $this->applyRule($field, $rule);
            }
        }
        
        return [
            'valid' => empty($this->errors),
            'errors' => $this->errors,
        ];
    }

    private function applyRule(string $field, string $rule): void
    {
        $value = $this->data[$field] ?? null;
        $ruleName = $rule;
        $ruleParam = null;
        
        if (str_contains($rule, ':')) {
            [$ruleName, $ruleParam] = explode(':', $rule, 2);
        }
        
        $method = 'validate' . ucfirst($ruleName);
        
        if (method_exists($this, $method)) {
            $result = $this->$method($value, $ruleParam);
            if ($result !== true) {
                $this->errors[$field][] = $this->getMessage($field, $ruleName, $ruleParam);
            }
        }
    }

    private function validateRequired(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return false;
        }
        
        if (is_array($value) && empty($value)) {
            return false;
        }
        
        return true;
    }

    private function validateEmail(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function validateMin(mixed $value, ?string $param): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        
        $length = (int)$param;
        
        if (is_numeric($value)) {
            return $value >= $length;
        }
        
        return mb_strlen((string)$value) >= $length;
    }

    private function validateMax(mixed $value, ?string $param): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        
        $length = (int)$param;
        
        if (is_numeric($value)) {
            return $value <= $length;
        }
        
        return mb_strlen((string)$value) <= $length;
    }

    private function validateUrl(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    private function validateAlpha(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        return ctype_alpha((string)$value);
    }

    private function validateAlphaNum(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        return ctype_alnum((string)$value);
    }

    private function validateNumeric(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        return is_numeric($value);
    }

    private function validateInteger(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    private function validateBool(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        return filter_var($value, FILTER_VALIDATE_BOOLEAN) !== false;
    }

    private function validateIn(mixed $value, ?string $param): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        
        $allowed = explode(',', (string)$param);
        return in_array($value, $allowed, true);
    }

    private function validateConfirmed(mixed $value): bool
    {
        $field = $this->field . '_confirmation';
        $confirmation = $this->data[$field] ?? null;
        return $value === $confirmation;
    }

    private function validateUnique(mixed $value, ?string $param): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        
        [$table, $column] = explode(',', (string)$param) + [1 => $this->field];
        
        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([$value]);
        
        return (int)$stmt->fetch()['count'] === 0;
    }

    private function validateExists(mixed $value, ?string $param): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        
        [$table, $column] = explode(',', (string)$param) + [1 => 'id'];
        
        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([$value]);
        
        return (int)$stmt->fetch()['count'] > 0;
    }

    private function validateRegex(mixed $value, ?string $param): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        return preg_match((string)$param, (string)$value);
    }

    private function validateDate(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        return strtotime((string)$value) !== false;
    }

    private function validateAfter(mixed $value, ?string $param): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        return strtotime((string)$value) > strtotime((string)$param);
    }

    private function validateBefore(mixed $value, ?string $param): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        return strtotime((string)$value) < strtotime((string)$param);
    }

    private function validateImage(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        return in_array($value, $allowed, true);
    }

    private function validateFile(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        return is_uploaded_file($value);
    }

    private function validateMimes(mixed $value, ?string $param): bool
    {
        if ($value === null || $value === '') {
            return true;
        }
        
        $allowed = explode(',', (string)$param);
        return in_array($value, $allowed, true);
    }

    private function getMessage(string $field, string $rule, ?string $param = null): string
    {
        $fieldName = str_replace('_', ' ', $field);
        
        $messages = [
            'required' => "The {$fieldName} field is required.",
            'email' => "The {$fieldName} must be a valid email address.",
            'min' => "The {$fieldName} must be at least {$param} characters.",
            'max' => "The {$fieldName} may not be greater than {$param} characters.",
            'url' => "The {$fieldName} must be a valid URL.",
            'alpha' => "The {$fieldName} must contain only letters.",
            'alphanum' => "The {$fieldName} must contain only letters and numbers.",
            'numeric' => "The {$fieldName} must be a number.",
            'integer' => "The {$fieldName} must be an integer.",
            'in' => "The {$fieldName} must be one of: {$param}.",
            'unique' => "The {$fieldName} has already been taken.",
            'exists' => "The selected {$fieldName} is invalid.",
            'regex' => "The {$fieldName} format is invalid.",
            'date' => "The {$fieldName} must be a valid date.",
            'confirmed' => "The {$fieldName} confirmation does not match.",
        ];
        
        return $messages[$rule] ?? "The {$fieldName} is invalid.";
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): ?string
    {
        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0] ?? null;
        }
        return null;
    }
}
