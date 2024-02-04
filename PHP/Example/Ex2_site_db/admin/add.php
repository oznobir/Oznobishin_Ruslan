<?php
include 'connectDbPages.php';
/** @var $link * */

$info = addPage($link);
getPage($info);

function getPage($info): void
{
    if (isset($_POST['title']) &&
        isset($_POST['description']) &&
        isset($_POST['url']) &&
        isset($_POST['text']) &&
        isset($_POST['text2'])) {
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $url = $_POST['url'];
        $text = $_POST['text'];
        $text2 = $_POST['text2'];
    } else {
        $title = '';
        $desc = '';
        $url = '';
        $text = '';
        $text2 = '';
    }
    $id = '';
    ob_start();
    include 'formPage.php';
    $contentAdm = ob_get_clean();
    $titleAdm = 'admin add page';
    $descAdm = 'admin add page';
    include 'layoutAdm.php';
}// end function getPage($info = ''): void

function addPage($link)
{
    if (isset($_POST['title']) &&
        isset($_POST['description']) &&
        isset($_POST['url']) &&
        isset($_POST['text']) &&
        isset($_POST['text2'])) {
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $url = $_POST['url'];
        $text = $_POST['text'];
        $text2 = $_POST['text2'];
        // проверка - есть ли такой url, лучше использовать SELECT COUNT
        $query = "SELECT COUNT(*) as count FROM pages WHERE url = '$url'";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        $isPage = mysqli_fetch_assoc($result)['count'];
//        var_dump($isPage);

        if ($isPage) {
            return [
                'text' => "Page \"$url\" exists!",
                'status' => "error"
            ];
        } elseif ($url != '') {
            $query = "INSERT INTO pages (title, description, url, text, text2) VALUES ('$title','$desc','$url','$text','$text2')";
            mysqli_query($link, $query) or die(mysqli_error($link));
            header("Location: index.php?add-p=$url");
//          Т.к. теперь редирект на admin page с Get['add-p'], то далее можно возвращать ''.
//            return [
//                'text' => "Page \"$url\" added successfully!",
//                'status' => "success"
//            ];
            return '';
        } else {
            return [
                'text' => "Enter url page!",
                'status' => "error"
            ];
        }
    } else {
        return '';
    }
}// end function addPage($link)