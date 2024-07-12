<?php

namespace core\site\helpers;

use core\base\controllers\BaseMethods;

trait ValidationHelper
{
    use BaseMethods;

    /**
     * @param array|null $validation
     * @param array $arrAdd
     * @return void
     */
    protected function clearFormFieldsOld(?array $validation, array &$arrAdd = []): void
    {
        //        array_filter()
        if (!$arrAdd) $arrAdd = &$_POST;
        foreach ($arrAdd as $key => $item) {
            if (!empty($validation[$key]['methods'])) {
                $arr['name'] = $this->clearTags($key);
                $arr['count'] = $validation[$key]['count'] ?? 140;
                $arr['translate'] = $validation[$key]['translate'] ?? $arr['name'];
                foreach ($validation[$key]['methods'] as $method) {
                    $arrAdd[$key] = $item = $this->$method($item, $arr);
                }
            }
        }
    }

    /**
     * @param array|null $validation
     * @param string $field
     * @param string $key
     * @param array $res
     * @return string
     */
    protected function clearFormFields(?array $validation, string $field, string $key, array &$res): string
    {
        if ($validation) {
            if (!empty($validation['methods'])) {
                $validation['name'] = $this->clearTags($key);
                $validation['countMin'] = $validation['countMin'] ?? null;
                $validation['count'] = $validation['count'] ?? 140;
                $validation['translate'] = $validation['translate'] ?? $validation['name'];
                $clearField = $field;
                foreach ($validation['methods'] as $method) {
                    if (empty($res[$key])){
                        $clearField = match ($method) {
                            'md5PassField' => $this->md5PassField($clearField),
                            'stringField' => $this->stringField($clearField),
                            default => $this->$method($clearField, $validation, $res),
                        };
                    }
                }
                return $clearField;
            }
        }
        return $this->clearTags($field);
    }

    /**
     * @param string|null $value
     * @return string|null
     */
    protected function md5PassField(?string $value): string|null
    {
        return md5($value);
    }

    /**
     * @param string $value
     * @return string
     */
    protected function stringField(string $value): string
    {
        return $this->clearTags($value);
    }

    /**
     * @param string|null $value
     * @param array $answer
     * @param array|null $res
     * @return string|null
     */
    protected function emptyField(?string $value, array $answer, ?array &$res): string|null
    {
        if (empty($value)) {
            $res[$answer['name']] = $this->sendAnswer('Не заполнено поле ' . $answer['translate']);
        }
        return $value;
    }

    /**
     * @param string|null $value
     * @param array $answer
     * @param array|null $res
     * @return string|null
     */
    protected function countField(?string $value, array $answer, ?array &$res): string|null
    {
        if (mb_strlen(trim($value)) > $answer['count']) {
            $res[$answer['name']] = $this->sendAnswer('Поле ' . $answer['translate'] . ' должно содержать не более ' . $answer['count'] . ' символов');
        }
        return $value;
    }

    /**
     * @param string|null $value
     * @param array $answer
     * @param array|null $res
     * @return string|null
     */
    protected function countMinField(?string $value, array $answer, ?array &$res): string|null
    {
        if ($answer['countMin']) {
            if (mb_strlen(trim($value)) < $answer['countMin']) {
                $res[$answer['name']] = $this->sendAnswer('Поле ' . $answer['translate'] . ' должно содержать не менее ' . $answer['countMin'] . ' символов');
            }
        }
        return $value;
    }


    /**
     * @param string $value
     * @param array $answer
     * @param array|null $res
     * @return float|int
     */
    protected function numericField(string $value, array $answer, ?array &$res): float|int
    {
        $value = $this->num($value);
        if (!is_numeric($value)) {
            $res[$answer['name']] = $this->sendAnswer('Некорректные данные в поле ' . $answer['translate']);
        }
        return $value;
    }

    /**
     * @param string $value
     * @param array $answer
     * @param array|null $res
     * @return string|int
     */
    protected function phone375Field(string $value, array $answer, ?array &$res): string|int
    {
        $value = preg_replace('/\D/', '', $value);
        if (!preg_match('/^375\d{9}$/', $value)) {
            $res[$answer['name']] = $this->sendAnswer('Некорректный формат номера телефона для РБ');
        }
        return $value;
    }

    /**
     * @param string $value
     * @param array $answer
     * @param array|null $res
     * @return string
     */
    protected function emailField(string $value, array $answer, ?array &$res): string
    {
        if (!preg_match('/^[\w\-\.]+@[\w\-]+\.[\w\-]/', $value)) {
            $res[$answer['name']] = $this->sendAnswer('Некорректный формат в поле E-mail');
        }
        return $value;
    }
}