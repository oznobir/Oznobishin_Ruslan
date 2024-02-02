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

$query = "SELECT id, title, description, url FROM pages";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
for ($data = []; $row = mysqli_fetch_assoc($result); $data [] = $row) ;

$content = "<table><thead><tr>
                <th>Id</th>
                <th>Title</th>
                <th>Description</th>
                <th>Url</th>
                <th>Edit</th>
                <th>Delete</th>
             </tr></thead>
             <tbody>";
foreach ($data as $pageAdm) {
    $content .= "
        <tr><td>{$pageAdm['id']}</td>
            <td>{$pageAdm['title']}</td>
            <td>{$pageAdm['description']}</td>
            <td>{$pageAdm['url']}</td>
            <td><a href='/'>edit</a></td>
            <td><a href='/'>delete</a></td>
        </tr>";
}
$content .= "</tbody></table>";
$title = 'admin page';
$desc = 'admin page';
include 'layoutAdm.php';