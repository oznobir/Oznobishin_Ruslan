<?php
/**
 * @var array $categories
 * @var array $products
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
        <div>
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
        </div>
        <h2>Редактирование товара</h2>
        <div>
            <table>
                <thead>
                <tr>
                    <th>№ п/п</th>
                    <th>ID (код)</th>
                    <th>Данные о товаре</th>
                    <th>Описание товара</th>
                    <th>Изображение</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1;
                foreach ($products as $product) : ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $product['id'] ?></td>
                        <td>
                            <label>Название:<br>
                                <input id="title_<?= $product['id'] ?>" data-old='<?= $product['title'] ?>'
                                       type="text" size="40" value="<?= $product['title'] ?>">
                            </label><br>
                            <label>slug:<br>
                                <input id="slug_<?= $product['id'] ?>" data-old='<?= $product['slug'] ?>'
                                       type="text" size="40" value="<?= $product['slug'] ?>">
                            </label><br>
                            <label>Категория:<br>
                                <select id="categoryId_<?= $product['id'] ?>" data-old='<?= $product['category_id'] ?>'>
                                    <option value="0">Главная категория</option>
                                    <?php tpl($categories, '', $product['category_id']); ?>
                                </select>
                            </label>
                            <label>
                                <select id="status_<?= $product['id'] ?>" data-old='<?= $product['status'] ?>'>
                                    <option selected
                                            value="<?= $product['status'] ?>"><?= ($product['status']) ? 'В наличии' : 'Нет в наличии' ?></option>
                                    <option value="<?= ($product['status']) ? 0 : 1 ?>"><?= ($product['status']) ? 'Нет в наличии' : 'В наличии' ?></option>
                                </select>
                            </label><br>
                            <label>Цена:
                                <input id="price_<?= $product['id'] ?>" data-old='<?= $product['price'] ?>'
                                       type="text" size="5" value="<?= $product['price'] ?>">
                            </label>


                        </td>
                        <td>
                            <label>
                                <textarea id="description_<?= $product['id'] ?>" rows="8"
                                          data-old='<?= $product['description'] ?>'><?= $product['description'] ?></textarea>
                            </label>
                        </td>
                        <td>
                            <?php if ($product['image']) : ?>
                                <img src="/project/access/img/<?= $product['image'] ?>" id="image_<?= $product['id'] ?>"
                                     alt="Фото товара" width="100">
                            <?php else: ?>
                            <span>Выберите файл:</span>
                            <?php endif; ?>
                            <form method="POST" action="/admin/upload/" enctype="multipart/form-data">
                                <input type="file" name="filename"><br>
                                <input type="hidden" name="itemId" value="<?=$product['id']?>">
                                <input type="submit" value="Загрузить">
                            </form>
                        </td>

                        <td>
                            <a href="#" onclick="updateProduct(<?= $product['id'] ?>); return false;"
                               alt="Сохранить изменения">
                                Сохранить
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php include 'footerLayout.php' ?>

