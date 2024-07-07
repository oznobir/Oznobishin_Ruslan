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
    protected function clearFormFields(?array $validation, array &$arrAdd = []): void
    {
        //        array_filter()
        if (!$arrAdd) $arrAdd = &$_POST;
        foreach ($arrAdd as $key => $item) {
            if (!empty($validation[$key]['methods'])) {
                $arr['count'] = $validation[$key]['count'] ?? 140;
                $arr['translate'] = $validation[$key]['translate'] ?? $this->clearTags($key);
                foreach ($validation[$key]['methods'] as $method) {
                    $arrAdd[$key] = $item = $this->$method($item, $arr);
                }
            }
        }
    }

    /**
     * @param string|null $value
     * @param array $answer
     * @return string|null
     */
    protected function emptyField(?string $value, array $answer): string|null
    {
        if (empty($value)) {
            $this->sendAnswer('Не заполнено поле "' . $answer['translate'] . '"');
        }
        return $value;
    }

    /**
     * @param string|null $value
     * @param array $answer
     * @return string|null
     */
    protected function countField(?string $value, array $answer): string|null
    {
        if (mb_strlen(trim($value)) > $answer['count']) {
            $this->sendAnswer('В поле "' . $answer['translate'] . '" недопустимое количество символов');
        }
        return $value;
    }


    /**
     * @param string $value
     * @param array $answer
     * @return float|int
     */
    protected function numericField(string $value, array $answer): float|int
    {
        $value = $this->num($value);
        if (!is_numeric($value)) {
            $this->sendAnswer('Некорректные данные в поле "' . $answer['translate'] . '"');
        }
        return $value;
    }

    /**
     * @param string $value
     * @param array $answer
     * @return string
     */
    protected function stringField(string $value, array $answer): string
    {
        unset($answer);
        return $this->clearTags($value);
    }

    /**
     * @param string $value
     * @param array $answer
     * @return int
     */
    protected function phoneField(string $value, array $answer): int
    {
        $value = preg_replace('/\D/', '', $value);
        if (!preg_match('/^375\d{9}$/', $value)) {
            $this->sendAnswer('Некорректный формат номера в поле "' . $answer['translate'] . '"');
        }
        return $value;
    }

    /**
     * @param string $value
     * @param array $answer
     * @return string
     */
    protected function emailField(string $value, array $answer): string
    {
        if (!preg_match('/^[\w\-\.]+@[\w\-]+\.[\w\-]/', $value)) {
            $this->sendAnswer('Некорректный формат данных в поле "' . $answer['translate'] . '"');
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