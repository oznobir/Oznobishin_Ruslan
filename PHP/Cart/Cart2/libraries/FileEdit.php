<?php

namespace libraries;

class FileEdit
{
    protected array $imgArr = [];
    protected string $directory;
    protected bool $uniqueFile = true;

    /**
     * @param string|null $directory
     * @return array
     */
    public function addFile(?string $directory = ''): array
    {
        $directory = trim($directory, ' /') . '/';
        $this->setDirectory($directory);
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
                        if ($res_name) $this->imgArr[$key][$i] = $directory . $res_name;

                    }
                }
            } else {
                if ($file['name']) {
                    $res_name = $this->createNameFile($file);
                    if ($res_name) $this->imgArr[$key] = $directory . $res_name;
                }
            }
        }
        return $this->getFiles();
    }

    /**
     * @return array
     */

    public function getFiles(): array
    {
        return $this->imgArr;
    }

    /**
     * @uses setUniqueFile
     * @param $value
     * @return void
     */
    public function setUniqueFile($value): void
    {
        $this->uniqueFile = (bool)$value;
    }

    /**
     * @param $directory
     * @return void
     */
    public function setDirectory($directory): void
    {
        $this->directory = $_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . $directory;
        if (!file_exists($this->directory)) mkdir($this->directory, 0755, true);
    }

    /**
     * @param array $file
     * @return false|string
     */
    protected function createNameFile(array $file): false|string
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

    /**
     * @param $tempPath
     * @param $filePath
     * @return bool
     */
    protected function uploadFile($tempPath, $filePath): bool
    {
        // потом добавить
        if (move_uploaded_file($tempPath, $filePath)) return true;
        return false;
    }

    /**
     * @param string $fileName
     * @param string $ext
     * @param string $fileLastName
     * @return string
     */
    protected function checkNameFile(string $fileName, string $ext, string $fileLastName = ''): string
    {
        if (!file_exists($this->directory . $fileName . $fileLastName . '.' . $ext) || !$this->uniqueFile)
            return $fileName . $fileLastName . '.' . $ext;

        else return $this->checkNameFile($fileName, $ext, '_' . hash('crc32', time() . mt_rand(1, 1000)));
    }
}