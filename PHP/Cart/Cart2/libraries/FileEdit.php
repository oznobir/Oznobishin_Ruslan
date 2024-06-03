<?php

namespace libraries;

class FileEdit
{
    protected array $imgArr = [];
    protected string $directory;

    public function addFile($directory = false): array
    {
        if (!$directory) $this->directory = $_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR;
        else $this->directory = $directory;
        $file_arr = [];
        foreach ($_FILES as $key => $file) {
            if (is_array($file['name'])) {
                foreach ($file['name'] as $i => $value) {
                    if (!empty($file['name'][$i])) {
                        $file_arr['name'] = $file['name'][$i];
                        $file_arr['type'] = $file['type'][$i];
                        $file_arr['tmp_name'] = $file['tmp_name'][$i];
                        $file_arr['error'] = $file['error'][$i];
                        $file_arr['size'] = $file['size'][$i];

                        $res_name = $this->createNameFile($file_arr);
                        if ($res_name) $this->imgArr[$key][] = $res_name;

                    }
                }
            } else {
                if ($file['name']) {
                    $res_name = $this->createNameFile($file);
                    if ($res_name) $this->imgArr[$key] = $res_name;
                }
            }
        }
        return $this->getFiles();
    }

    public function getFiles(): array
    {
        return $this->imgArr;
    }

    protected function createNameFile($file): false|string
    {
//        $pathInfo =pathinfo($file['name']);
//        $ext = $pathInfo['extension'];
//        $fileName =  $pathInfo['filename'];
        $fileNameArr = explode('.', $file['name']);
        $ext = $fileNameArr[count($fileNameArr) - 1];
        unset($fileNameArr[count($fileNameArr) - 1]);
        $fileName = implode('.', $fileNameArr);
        $fileName = (new TextModify())->translit($fileName);
        $fileName = $this->checkNameFile($fileName, $ext);
        $fileFullName = $this->directory . $fileName;
        if ($this->uploadFile($file['tmp_name'], $fileFullName))
            return $fileName;
        return false;
    }

    protected function uploadFile($tempPath, $filePath): bool
    {
        // потом добавить
        if (move_uploaded_file($tempPath, $filePath)) return true;
        return false;
    }

    protected function checkNameFile($fileName, $ext, $fileLastName = ''): string
    {
        if (!file_exists($this->directory . $fileName . $fileLastName . '.' . $ext))
            return $fileName . $fileLastName . '.' . $ext;

        else return $this->checkNameFile($fileName, $ext, '_' . hash('crc32', time() . mt_rand(1, 1000)));
    }
}