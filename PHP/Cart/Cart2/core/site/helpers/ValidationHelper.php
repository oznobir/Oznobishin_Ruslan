<?php

namespace core\site\helpers;

use core\base\controllers\BaseMethods;

trait ValidationHelper
{
    use BaseMethods;

    /**
     * @param string|null $value
     * @param string $answer
     * @return string|null
     */
    protected function emptyField(?string $value, string $answer): string|null
    {
        $value = $this->clearTags($value);
        if (empty($value)) {
            $this->sendAnswer('Не заполнено поле "' . $answer . '"');
        }
        return $value;
    }

    /**
     * @param string $value
     * @param string $answer
     * @return bool|int
     */
    protected function numericField(string $value, string $answer): bool|int
    {
        $value = preg_replace('/\D/', '', $value);
        if (!is_numeric($value)) {
            $this->sendAnswer('Некорректные данные в поле "' . $answer . '"');
            return false;
        }
        return $value;
    }

    /**
     * @param string $value
     * @param string $answer
     * @return bool|int
     */
    protected function phoneField(string $value, string $answer = ''): bool|int
    {
        $value = preg_replace('/\D/', '', $value);
        if (!preg_match('/^375\d{9}$/', $value)) {
            $this->sendAnswer('Некорректный формат номера в поле "' . $answer . '"');
            return false;
        }
        return $value;
    }

    /**
     * @param string $value
     * @param string $answer
     * @return bool|int
     */
    protected function emailField(string $value, string $answer): bool|int
    {
        $value = $this->clearTags($value);
        if (!preg_match('/^[\w\-\.]+@[\w\-]+\.[\w\-]/', $value)) {
            $this->sendAnswer('Некорректный формат email в поле "' . $answer . '"');
            return false;
        }
        return $value;
    }

//    /**
//     * @param string $value
//     * @param string $field
//     * @param string $answer
//     * @return mixed
//     */
//    protected function valuesField(string $value,  string $field, string $answer = ''): mixed
//    {
//        switch ($field) {
//            case 'email':
//                $value = $this->clearTags($value);
//                if (!preg_match('/^[\w\-\.]+@[\w\-]+\.[\w\-]/', $value)) {
//                    $this->sendAnswer('Некорректный формат e-mail');
//                }
//                break;
//            case 'phone':
//                $value = preg_replace('/\D/', '', $value);
//                if (!preg_match('/^375\d{9}$/', $value)) {
//                    $this->sendAnswer('Некорректный формат номера телефона');
//                }
//                break;
//            case 'numeric':
//                $value = preg_replace('/\D/', '', $value);
//                if (!is_numeric($value)) {
//                    $this->sendAnswer('Не числовые данные в поле ' . $answer);
//                }
//                break;
//            case 'empty':
//                $value = $this->clearTags($value);
//                if (empty($value)) {
//                    $this->sendAnswer('Не заполнено поле ' . $answer);
//                }
//                break;
//            default:
//                $value = $this->clearTags($value);
//                if (!is_string($value)) {
//                    $this->sendAnswer('Некорректные данные в поле ' . $answer);
//                }
//                break;
//        }
//        return $value;
//    }
}