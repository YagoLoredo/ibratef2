<?php

namespace Core\Library;

class Validator 
{
    public static function make(array $data, array $rules)
    {
        $errors = [];

        foreach ($rules as $ruleKey => $ruleValue) {

            $itensRule = explode("|", $ruleValue['rules']);

            // verifica se campo existe
            $value = $data[$ruleKey] ?? null;

            // 🔥 verifica nullable
            if (in_array('nullable', $itensRule) && ($value === null || $value === '')) {
                continue;
            }

            foreach ($itensRule as $itemKey) {

                $items = explode(":", $itemKey);

                switch ($items[0]) {

                    case 'required':
                        if ($value === null || $value === '') {
                            $errors[$ruleKey] = "O campo <b>{$ruleValue['label']}</b> deve ser preenchido.";
                        }
                        break;

                    case 'email':
                        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$ruleKey] = "O campo <b>{$ruleValue['label']}</b> não é válido.";
                        }
                        break;

                    case 'float':
                        if ($value && !filter_var($value, FILTER_VALIDATE_FLOAT)) {
                            $errors[$ruleKey] = "O campo <b>{$ruleValue['label']}</b> deve conter número decimal.";
                        }
                        break;

                    case 'int':
                        if ($value && !filter_var($value, FILTER_VALIDATE_INT)) {
                            $errors[$ruleKey] = "O campo <b>{$ruleValue['label']}</b> deve conter número inteiro.";
                        }
                        break;

                    case 'min':
                        if ($value !== null && strlen(strip_tags((string)$value)) < $items[1]) {
                            $errors[$ruleKey] = "O campo <b>{$ruleValue['label']}</b> deve conter no mínimo {$items[1]} caracteres.";
                        }
                        break;

                    case 'max':
                        if ($value !== null && strlen(strip_tags((string)$value)) > $items[1]) {
                            $errors[$ruleKey] = "O campo <b>{$ruleValue['label']}</b> deve conter no máximo {$items[1]} caracteres.";
                        }
                        break;

                    case 'nullable':
                        // já tratado acima
                        break;

                    default:
                        break;
                }
            }
        }

        if (!empty($errors)) {
            Session::set('errors', $errors);
            Session::set('inputs', $data);
            return true;
        } else {
            Session::destroy('errors');
            Session::destroy('inputs');
            return false;
        }
    }
}