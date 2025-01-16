<?php

namespace KpEsportes\App\Util;

use Exception;
use KpEsportes\App\Http\Request;
use KpEsportes\App\Storage\SqlDatabase;
use stdClass;

class Validator {

    public static function validate(array $validation_fields_and_rules, array|null $validation_fields = null) {
        $fields = $validation_fields;

        if ($fields == null) {
            $request = new Request;
            $fields = $request->getBody();
        }

        $validation_handler = new Validator;
        $errors = [];

        foreach($validation_fields_and_rules as $field_name => $rules) {
            $value = isset($fields[$field_name]) ? $fields[$field_name] : null;

            foreach ($rules as $rule) {
                $rule_name = explode(":", $rule)[0];
                $rule = preg_replace("#(.*)\:#", "", $rule);

                $args = [$value, $field_name];  
                $args = array_merge($args, explode(",", $rule));

                try {
                    call_user_func_array([$validation_handler, $rule_name], $args);
                } catch (Exception $error) {
                    $errors[$field_name] = $error->getMessage();
                    continue;
                }
            }
        }        

        if (count($errors) > 0) 
            throw new Exception(json_encode($errors), 400);
    }

    protected function required(mixed $value, string $field_name) {
        if ($value == null || $value == trim(""))
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

    protected function unique(mixed $value, string $field_name, string $table, string $column_name) {
        $db = new SqlDatabase;
        $db->connect();

        $result = $db->fetch("SELECT * FROM $table WHERE $column_name ~* :value", stdClass::class, [
            "value" => $value,
        ]);

        $db->close();

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

        $result = $db->fetch("SELECT * FROM $table WHERE $column_name ~* :value", stdClass::class, [
            "value" => $value,
        ]);

        $db->close();

        if (!count($result) > 0)
            throw new Exception("Este $field_name não existe, por favor tente novamente");
    }
}