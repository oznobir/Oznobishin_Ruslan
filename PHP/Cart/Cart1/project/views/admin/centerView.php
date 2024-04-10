<?php
/**
 * @var array $categories
 * @var string $title
 */ ?>
<?php include 'headerLayout.php' ?>
<main>
    <nav>
        <div class="left-column">
            <?php include 'menu.php' ?>
        </div>
    </nav>
    <div class="center-column">
        <h2>Добавление категории</h2>
        <form id="formCategories" class="">
            <label for="newCategoryName">Название категории:</label>
            <input name="newCategoryName" id="newCategoryName"
                   type="text" value=""><br><br>
            <label for="newCategorySlug"> slug (по-английски): </label>
            <input name="newCategorySlug"
                   id="newCategorySlug"
                   type="text" value=""><br><br>
            <label>Добавить в
                <select name="mainCategoryId">
                    <option value="0">Главная категория</option>
                    <?php tpl($categories, ''); ?>
                    <?php function tpl($menu, $str): void
                    {
                        $str = '- ' . $str;
                        foreach ($menu as $key => $item) { ?>
                            <option value="<?= $key ?>"><?= $str . $item['title'] ?></option>
                            <?php if (isset($item['children'])) tpl($item['children'], $str);
                        }
                    } ?>
                </select>
            </label><br><br>
            <input type="button" onclick="newCategory();" value="Добавить"><br><br>
        </form>
    </div>
</main>
<?php include 'footerLayout.php' ?>

