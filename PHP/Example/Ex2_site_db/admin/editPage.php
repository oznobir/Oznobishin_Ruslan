<?php
include 'connectDbPages.php';
/** @var $link * */
$info = editPage($link);
getPage($link, $info);
function getPage($link, $info): void
{
    if (isset($_GET['edit-p'])) {
        $url = $_GET['edit-p'];
        $query = "SELECT * FROM pages WHERE url = '$url'";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        $page = mysqli_fetch_assoc($result);
        if ($page) {
            if (isset($_POST['id']) &&
                isset($_POST['url']) &&
                isset($_POST['title']) &&
                isset($_POST['description']) &&
                isset($_POST['text']) &&
                isset($_POST['text2'])) {

                $id = $_POST['id'];
                $url = $_POST['url'];
                $title = $_POST['title'];
                $desc = $_POST['description'];
                $text = $_POST['text'];
                $text2 = $_POST['text2'];
            } else {
                $id = $page['id'];
                $url = $page['url'];
                $title = $page['title'];
                $desc = $page['description'];
                $text = $page['text'];
                $text2 = $page['text2'];
            }
            $contentAdm =  '';
            if ($info) {
                $contentAdm .=  "<div class='{$info['status']}'>{$info['text']}</div>";
            }
            $contentAdm .= "<form method='POST'>
                                <label for='id'>url:</label><br>
                                <input name='id' value='$id' readonly><br>
                                <label for='url'>url:</label><br>
                                <input name='url' placeholder='url' value='$url'><br>
                                <label for='title'>title:</label><br>
                                <input name='title' placeholder='title' value='$title'><br>
                                <label for='description'>description:</label><br>
                                <input name='description' placeholder='description' value='$desc'><br>
                                text<br><textarea name='text' placeholder='text'>$text</textarea><br>
                                text2<br><textarea name='text2' placeholder='text2'>$text2</textarea><br>
                                <input type='submit' value='Edit page'><br><br>
                            </form>";
        } else {
            $contentAdm = "<div class='error'>Page not found in database</div>";
        }
    } else {
        $contentAdm = "<div class='error'>Page not selected</div>";
    }
    $titleAdm = 'admin edit page';
    $descAdm = 'admin edit page';
    include 'layoutAdm.php';
}

function editPage($link)
{
    if (isset($_POST['id']) &&
        isset($_POST['url']) &&
        isset($_POST['title']) &&
        isset($_POST['description']) &&
        isset($_POST['text']) &&
        isset($_POST['text2'])) {

        $id = $_POST['id'];
        $url = $_POST['url'];
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $text = $_POST['text'];
        $text2 = $_POST['text2'];

        if (isset ($_GET['edit-p'])) {
            if ($_GET['edit-p'] !== $url) {
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

            $query = "UPDATE pages SET  title ='$title', description ='$desc', url ='$url', text ='$text', text2='$text2' WHERE id ='$id'";
            mysqli_query($link, $query) or die(mysqli_error($link));

            header("Location: index.php?edit-p=$url");
            return [
                'text' => "Page \"$url\" edited successfully!",
                'status' => "success"
            ];
        } else {
            return [
                'text' => "Page not found!",
                'status' => "error"
            ];
        }
    } else {
        return '';
    }
}