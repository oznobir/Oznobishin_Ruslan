<?php
include 'connectDbPages.php';
/** @var $link * */
$info = editPage($link);
getPage($link, $info);
function getPage($link, $info): void
{
    if (isset($_GET['edit-id'])) {
        $id = $_GET['edit-id'];

        $query = "SELECT * FROM pages WHERE id = '$id'";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        $page = mysqli_fetch_assoc($result);
        if ($page) {
            if (isset($_POST['url']) &&
                isset($_POST['title']) &&
                isset($_POST['description']) &&
                isset($_POST['text']) &&
                isset($_POST['text2'])) {

                $url = $_POST['url'];
                $title = $_POST['title'];
                $desc = $_POST['description'];
                $text = $_POST['text'];
                $text2 = $_POST['text2'];
            } else {

                $url = $page['url'];
                $title = $page['title'];
                $desc = $page['description'];
                $text = $page['text'];
                $text2 = $page['text2'];
            }
            ob_start();
            include 'formPage.php';
            $contentAdm = ob_get_clean();
        } else {
            $contentAdm = "<div class='error'>Page ID not found in database</div>";
        }
    } else {
        $contentAdm = "<div class='error'>Page ID not selected</div>";
    }
    $titleAdm = 'admin edit page';
    $descAdm = 'admin edit page';
    include 'layoutAdm.php';
}

function editPage($link)
{
    if (isset($_POST['url']) &&
        isset($_POST['title']) &&
        isset($_POST['description']) &&
        isset($_POST['text']) &&
        isset($_POST['text2'])) {

        $url = mysqli_real_escape_string($link, $_POST['url']);
        $title = mysqli_real_escape_string($link, $_POST['title']);
        $desc = mysqli_real_escape_string($link, $_POST['description']);
        $text = mysqli_real_escape_string($link, $_POST['text']);
        $text2 = mysqli_real_escape_string($link, $_POST['text2']);

        if (isset ($_GET['edit-id'])) {
            $id = $_GET['edit-id'];
            $query = "SELECT url FROM pages WHERE id = '$id'";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            $page = mysqli_fetch_assoc($result);

            if (mysqli_real_escape_string($link, $page ['url']) !== $url) {
                $query = "SELECT COUNT(*) as count FROM pages WHERE url = '$url'";
                $result = mysqli_query($link, $query) or die(mysqli_error($link));
                $isPage = mysqli_fetch_assoc($result)['count'];

                if ($isPage) {
                    return [
                        'text' => "Page with url \"$url\" exists!",
                        'status' => "error"
                    ];
                }
            }

            $query = "UPDATE pages SET  title ='$title', description ='$desc', 
            url ='$url', text ='$text', text2='$text2' WHERE id ='$id'";
            mysqli_query($link, $query) or die(mysqli_error($link));

            header("Location: index.php?edit-id=$id");
            return [
                "text" => "Page with Id \"$id\" edited successfully!",
                "status" => "success"
            ];
        } else {
            return [
                'text' => 'Page ID not found!',
                'status' => 'error'
            ];
        }
    } else {
        return '';
    }
}