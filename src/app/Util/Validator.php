<?php

namespace KpEsportes\App\Util;

use Exception;
use KpEsportes\App\Http\Request;
use KpEsportes\App\Storage\SqlDatabase;
use stdClass;

class Validator {

    public static function validate(array $validation_fields_and_rules, array|null $validation_fields = []) {
        $request = new Request;
        $fields = $validation_fields;
        $validation_handler = new Validator;
        $errors = [];

        foreach($validation_fields_and_rules as $field_name => $rules) {
            $value = null;

            if (isset($fields[$field_name]))
                $value = $fields[$field_name];
            elseif ($request->getInput($field_name) != null)
                $value = $request->getInput($field_name);

            foreach ($rules as $rule) {
                if ($rule == "nullable") {
                    if ($value == null)
                        break;
                    continue;
                }

                $rule_name = explode(":", $rule)[0];
                $rule = preg_replace("#(.*)\:#", "", $rule);

                $args = [$value, $field_name];  
                $args = array_merge($args, explode(",", $rule));

                try {
                    call_user_func_array([$validation_handler, $rule_name], $args);
                } catch (Exception $error) {
                    $errors[$field_name] = $error->getMessage();
                    break;
                }
            }
        }        

        if (count($errors) > 0) 
            throw new Exception(json_encode($errors), 400);
    }

    protected function required(mixed $value, string $field_name) {
        if ($value == null || empty($value))
            throw new Exception("O campo $field_name é obrigatorio");
    }

    protected function email(mixed $email, string $field_name) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new Exception("O campo $field_name precisa ser um email valido");
    }

    protected function min(mixed $value, string $field_name, int $min_length) {
        if (is_array($value) && count($value) < $min_length)
            throw new Exception("O campo $field_name precisa ter no minimo $min_length itens");
        if (is_string($value) && strlen($value) < $min_length)
            throw new Exception("O campo $field_name precisa ter no minimo $min_length caracters");
        if (is_numeric($value) && $value < $min_length)
            throw new Exception("O campo $field_name precisa ser um numero maior do que $min_length");
    }

    protected function max(mixed $value, string $field_name, int $max_length) {
        if (is_array($value) && count($value) > $max_length)
            throw new Exception("O campo $field_name pode ter no maximo $max_length itens");
        if (is_string($value) && strlen($value) > $max_length)
            throw new Exception("O campo $field_name precisa ter no minimo $max_length caracters");
        if (is_numeric($value) && $value > $max_length)
            throw new Exception("O campo $field_name precisa ser um numero maior do que $max_length");
    }

    protected function unique(mixed $value, string $field_name, string $table, string $column_name, string $exception_column=null, mixed $exception_value=null) {
        $db = new SqlDatabase;
        $db->connect();
        $result = [];

        try {
            $query = "SELECT * FROM $table WHERE $column_name = :value";
            $binds = [
                "value" => $value,
            ];

            if ($exception_column != null && $exception_value != null) {
                $query .= " AND $exception_column != :exception_value";
                $binds["exception_value"] = $exception_value;
            }

            $result = $db->fetch($query, stdClass::class, $binds);

            $db->close();
        } catch (Exception $e) {}

        if (count($result) > 0)
            throw new Exception("Este $field_name ja esta cadastrado");
    }

    protected function url(mixed $value, string $field_name) {
        if (!filter_var($value, FILTER_VALIDATE_URL) && !filter_var($value, FILTER_VALIDATE_IP))
            throw new Exception("O campo $field_name não é uma url valida");
    }

    protected function exist(mixed $value, string $field_name, string $table, string $column_name) {
        $db = new SqlDatabase;
        $db->connect();
        $result = [];

        try {
            if ($field_name == "password") 
                $value = Hash::make($value);

            $result = $db->fetch("SELECT * FROM $table WHERE $column_name = :value", stdClass::class, [
                "value" => $value,
            ]);

            $db->close();
        } catch (Exception $e) {}

        if (count($result) <= 0)
            throw new Exception("Este $field_name não existe, por favor tente novamente");
    }

    protected function numeric(mixed $value, string $field_name) {
        if (!is_numeric($value))
            throw new Exception("O $field_name precisa ser um valor numerico");
    }

    protected function list(mixed $value, string $field_name) {        
        if (is_string($value) && json_decode($value) != null)
            return;
        else if (is_array($value))
            return;
        
        throw new Exception("O campo $field_name precisa ser uma lista valida");
    }

    protected function filled(mixed $value, string $field_name) {
        if (empty($value))
            throw new Exception("O campo $field_name não pode estar vazio");
    }

    protected function file(mixed $value, string $field_name) {
        if (isset($_FILES[$field_name]))
            return;

        throw new Exception("O campo $field_name precisa ser um arquivo");
    }

    protected function types(mixed $value, string $field_name, string ...$types) {
        if (!isset($_FILES[$field_name]))
            throw new Exception("O campo $field_name não é um arquivo valido");

        $file_type = explode(".", $value["name"])[1];

        foreach ($types as $type) {
            if ($file_type == $type)
                return;
        }

        throw new Exception("O campo $field_name precisa ter um dos seguintes tipos: " . implode(",", $types));
    }

}