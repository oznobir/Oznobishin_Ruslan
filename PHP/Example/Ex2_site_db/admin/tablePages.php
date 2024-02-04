<?php
/* @var $data
 * @var $info */
if ($info) {
    echo "<div class='{$info['status']}'>{$info['text']}</div>";
}
?>
<table>
    <thead>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Description</th>
        <th>Url</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($data as $pageAdm) { ?>
        <tr>
            <td><?= $pageAdm['id'] ?></td>
            <td><?= $pageAdm['title'] ?></td>
            <td><?= $pageAdm['description'] ?></td>
            <td><?= $pageAdm['url'] ?></td>
            <td><a href="edit.php?edit-p=<?= $pageAdm['url'] ?>">edit</a></td>
            <td><a href="?delete-p=<?= $pageAdm['url'] ?>">delete</a></td>
        </tr>
    <?php } ?>
    </tbody>
</table>