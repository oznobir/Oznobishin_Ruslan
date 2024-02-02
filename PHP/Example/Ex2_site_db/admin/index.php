<?php
//**************
//Потом можно вынести в отдельный файл
error_reporting(E_ALL);
ini_set('display_errors', 'on');

$host = 'localhost';
$user = 'root';
$password = '';
$dbName = 'dbPages';

$link = mysqli_connect($host, $user, $password, $dbName);
mysqli_query($link, "SET NAMES 'utf8'");
//*************
if (deletePage($link)) {
    $id = $_GET['del'];
    $info ="Page $id delete successfully";
} else {
    $info = '';
}
showPageTable($link, $info);
function showPageTable($link, $info): void
{
    $content =  '';
    if ($info){
        $content .=  "<div>Info: $info</div>";
    }
    $query = "SELECT id, title, description, url FROM pages";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data = []; $row = mysqli_fetch_assoc($result);){
        $data [] = $row;
    }

    $content .= "<table><thead><tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Url</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr></thead>
                <tbody>";
    foreach ($data as $pageAdm) {
        $content .=
            "<tr>
                <td>{$pageAdm['id']}</td>
                <td>{$pageAdm['title']}</td>
                <td>{$pageAdm['description']}</td>
                <td>{$pageAdm['url']}</td>
                <td><a href='/'>edit</a></td>
                <td><a href='?del={$pageAdm['id']}'>delete</a></td>
             </tr>";
    }
    $content .= "</tbody></table>";
    $title = 'admin page';
    $desc = 'admin page';
    include 'layoutAdm.php';
} // end function showPageTable($link, $info): void

function deletePage($link)
{
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $query = "DELETE FROM pages WHERE id = '$id'";
        mysqli_query($link, $query) or die(mysqli_error($link));
        return true;
    } else {
        return false;
    }
} // end function deletePage($link)