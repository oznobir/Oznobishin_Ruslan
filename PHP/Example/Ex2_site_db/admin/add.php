<?php
include 'connectDbPages.php';
/** @var $link * */

addPage($link);
getPage();

function getPage(): void
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

    ob_start();
    include 'formPage.php';
    $contentAdm = ob_get_clean();
    $titleAdm = 'admin add page';
    $descAdm = 'admin add page';
    include 'layoutAdm.php';
}// end function getPage($info = ''): void

function addPage($link): void
{
    if (isset($_POST['title']) &&
        isset($_POST['description']) &&
        isset($_POST['url']) &&
        isset($_POST['text']) &&
        isset($_POST['text2'])) {

        $title = mysqli_real_escape_string($link, $_POST['title']);
        $desc = mysqli_real_escape_string($link, $_POST['description']);
        $url = mysqli_real_escape_string($link, $_POST['url']);
        $text = mysqli_real_escape_string($link, $_POST['text']);
        $text2 = mysqli_real_escape_string($link, $_POST['text2']);

        $query = "SELECT COUNT(*) as count FROM pages WHERE url = '$url'";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        $isPage = mysqli_fetch_assoc($result)['count'];

        if ($isPage) {
            $_SESSION ['message'] = [
                'text' => "Page with url \"$url\" exists!",
                'status' => "error"
            ];
        } elseif ($url != '') {
            $query = "INSERT INTO pages (title, description, url, text, text2) 
                      VALUES ('$title','$desc','$url','$text','$text2')";
            mysqli_query($link, $query) or die(mysqli_error($link));
            $id = mysqli_insert_id($link);
            $_SESSION ['message'] =  [
                'text' => "Page with id \"$id\" added successfully!",
                'status' => "success"
            ];
            header("Location: index.php");
            die();
        } else {
            $_SESSION ['message'] =  [
                'text' => "Enter url page!",
                'status' => "error"
            ];
        }
    }
}// end function addPage($link)