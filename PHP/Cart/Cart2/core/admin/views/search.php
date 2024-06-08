<div class="vg-wrap vg-element vg-ninteen-of-twenty">
    <div class="vg-wrap vg-element vg-ninteen-of-twenty vg-center">
        <h2>Результаты поиска</h2>
    </div>
    <?php if ($this->data): ?>
        <?php foreach ($this->data as $data) : ?>
            <div class="vg-element vg-fourth">
                <a href="<?= $data['path_edit'] ?>"
                   class="vg-wrap vg-element vg-full vg-firm-background-color4 vg-box-shadow show_element">
                    <div class="vg-element vg-half vg-center">
                        <span class="vg-text vg-firm-color1">"<?= $data['table_alias'] ?>"</span>
                    </div>
                    <div class="vg-element vg-half vg-center">
                        <span class="vg-text vg-firm-color1"><?= $data['name'] ?></span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

