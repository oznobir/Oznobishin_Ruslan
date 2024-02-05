<?php
/* @var $data */
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
            <td><a href="edit.php?edit-id=<?= $pageAdm['id'] ?>">edit</a></td>
            <td><a href="?delete-id=<?= $pageAdm['id'] ?>">delete</a></td>
        </tr>
    <?php } ?>
    </tbody>
</table>