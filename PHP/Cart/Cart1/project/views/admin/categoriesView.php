<?php
/**
 * @var array $categories
 * @var array $allCategories
 * @var string $title
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
        <h2>Добавление категории</h2>
        <form id="formCategories">
            <label for="newCategoryName">Название:
            <input name="newCategoryName" id="newCategoryName"
                   type="text" value="">
            </label>
            <label for="newCategorySlug">slug:
            <input name="newCategorySlug"
                   id="newCategorySlug"
                   type="text" value="">
            </label><br><br>
            <label>в
                <select name="mainCategoryId">
                    <option value="0">Главная категория</option>
                    <?php tpl($categories, ''); ?>
                </select>
            </label><br><br>
            <input type="button" onclick="newCategory();" value="Добавить"><br><br>
        </form>
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
                            <label>
                                <input id="title_<?= $category['id'] ?>" type="text" data-old="<?= $category['title'] ?>"
                                       value="<?= $category['title'] ?>" autocomplete="off">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input id="slug_<?= $category['id'] ?>" type="text" data-old="<?= $category['slug'] ?>"
                                       value="<?= $category['slug'] ?>" autocomplete="off">
                            </label>
                        </td>
                        <td><label>
                                <select id="parentId_<?= $category['id'] ?>" data-old="<?= $category['parent_id'] ?>">
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

