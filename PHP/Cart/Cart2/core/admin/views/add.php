<form id="main-form" class="vg-wrap vg-element vg-ninteen-of-twenty" method="post" action="<?=$this->path . $this->action?>"
      enctype="multipart/form-data">
    <div class="vg-wrap vg-element vg-full">
        <div class="vg-wrap vg-element vg-full vg-firm-background-color4 vg-box-shadow">
            <div class="vg-element vg-half vg-left">
                <div class="vg-element vg-padding-in-px">
                    <input type="submit" class="vg-text vg-firm-color1 vg-firm-background-color4 vg-input vg-button"
                           value="Сохранить">
                </div>
                <?php if (!$this->notDelete && $this->data): ?>
                    <div class="vg-element vg-padding-in-px">
                        <a href="<?= $this->path . 'delete/' . $this->table . '/'.$this->data[$this->columns['pri'][0]]?>"
                           class="vg-text vg-firm-color1 vg-firm-background-color4 vg-input vg-button vg-center vg_delete">
                            <span>Удалить</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if ($this->data): ?>
        <input id="tableId" type="hidden" name="<?= $this->columns['pri'][0] ?>"
               value="<?= $this->data[$this->columns['pri'][0]] ?>">
    <?php endif; ?>
    <input type="hidden" name="table" value="<?= $this->table ?>">
    <?php foreach ($this->blocks as $class => $block) {
        if (is_int($class)) $class = 'vg-content';
        echo "<div class=\"vg-wrap vg-element $class\">";
        if ($class != 'vg-content') echo '<div class="vg-full vg-firm-background-color4 vg-box-shadow">';
        foreach ($block as $row) {
            foreach ($this->templateArr as $template => $items) {
                if (in_array($row, $items)) {
                    if (!@include $_SERVER['DOCUMENT_ROOT'] . $this->formTemplates . $template . '.php') {
                        throw new core\base\exceptions\RouteException('Не найден шаблон ' . $template . '\n по пути ' .
                            $_SERVER['DOCUMENT_ROOT'] . $this->formTemplates . $template . '.php');
                    }
                    break;
                }
            }
        }
        if ($class != 'vg-content') echo '</div>';
        echo '</div>';
    } ?>
    <div class="vg-wrap vg-element vg-full">
        <div class="vg-wrap vg-element vg-full vg-firm-background-color4 vg-box-shadow">
            <div class="vg-element vg-half vg-left">
                <div class="vg-element vg-padding-in-px">
                    <input type="submit" class="vg-text vg-firm-color1 vg-firm-background-color4 vg-input vg-button"
                           value="Сохранить">
                </div>
                <div class="vg-element vg-padding-in-px">
                    <a href="/admin/shop/delete/table/shop_products/id_row/id/id/92"
                       class="vg-text vg-firm-color1 vg-firm-background-color4 vg-input vg-button vg-center vg_delete">
                        <span>Удалить</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
