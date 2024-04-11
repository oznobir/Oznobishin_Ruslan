<?php
/**
 * @var array $categories
 * @var array $allCategories
 */
?>
<?php function tpl($categories, $str, $idParent = null): void
{
    $str = '- ' . $str;
    foreach ($categories as $id => $category) { ?>
        <option value="<?= $id ?>" <?= (($idParent) && $id == $idParent) ? 'selected' : '' ?>><?= $str . $category['title'] ?></option>
        <?php if (isset($category['children'])) tpl($category['children'], $str, $idParent);
    }
} ?>
<?php include 'headerLayout.php' ?>
<main>
    <nav>
        <div class="left-column">
            <?php include 'menu.php' ?>
        </div>
    </nav>
    <div class="center-column">
        <h2>Добавление товара</h2>


        <label>Название
            <input type="text" id="newTitle" value="" autocomplete="off">
        </label>
        <label>slug
            <input type="text" id="newSlug" value="" autocomplete="off">
        </label>
        <label>Цена
            <input type="text" id="newPrice" value="" autocomplete="off">
        </label><br><br>
        <label>Описание
            <textarea id="newDescription" cols="69"></textarea><br>
        </label><br>
        <label>Категория
            <select id="newCategoryId">
                <option value="0">Главная категория</option>
                <?php tpl($categories, ''); ?>
            </select>
        </label>
        <input type="button" onclick="addProduct();" alt="Добавить новый товар" value="Добавить"><br><br>

        <h2>Редактирование категорий</h2>
        <form id="formEditCategories">
            <table>
                <thead>
                <tr>
                    <th>№ п/п</th>
                    <th>ID</th>
                    <th>Название</th>
                    <th>slug</th>
                    <th>Родительская категория</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1;
                foreach ($allCategories as $category) : ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $category['id'] ?></td>
                        <td>
                            <label for="title_<?= $category['id'] ?>">
                                <input name="title_<?= $category['id'] ?>" id="title_<?= $category['id'] ?>" type="text"
                                       value="<?= $category['title'] ?>" autocomplete="off">
                            </label>
                        </td>
                        <td>
                            <label for="slug_<?= $category['id'] ?>">
                                <input name="slug_<?= $category['id'] ?>" id="slug_<?= $category['id'] ?>" type="text"
                                       value="<?= $category['slug'] ?>" autocomplete="off">
                            </label>
                        </td>
                        <td><label>
                                <select name="parentId_<?= $category['id'] ?>" id="parentId_<?= $category['id'] ?>">
                                    <option value="0">Главная категория</option>
                                    <?php tpl($categories, '', $category['parent_id']); ?>
                                </select>
                            </label>
                        </td>
                        <td>
                            <a href="#" onclick="updateCategory(<?= $category['id'] ?>); return false;"
                               alt="Сохранить изменения">
                                Сохранить
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
</main>
<?php include 'footerLayout.php' ?>

