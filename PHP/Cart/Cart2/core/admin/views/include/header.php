<div class="vg-main">
    <div class="vg-one-of-twenty vg-firm-background-color2  vg-center">
        <a href="<?= PATH ?>" target="_blank">
            <div class="vg-element vg-full">
                <span class="vg-text2 vg-firm-color1">Site</span>
            </div>
        </a>
    </div>
    <div class="vg-element vg-ninteen-of-twenty vg-firm-background-color4 vg-space-between  vg-box-shadow">
        <div class="vg-element vg-third">
            <div class="vg-element vg-fifth vg-center" id="hideButton">
                <div>
                    <img src="<?= PATH . ADMIN_TEMPLATE ?>img/menu-button.png" alt="">
                </div>
            </div>
            <div class="vg-element vg-wrap-size vg-left vg-search  vg-relative" id="searchButton">
                <div>
                    <img src="<?= PATH . ADMIN_TEMPLATE ?>img/search.png" alt="">
                </div>
                <form method="post" action="<?= PATH . $this->adminAlias ?>/search" autocomplete="off">
                    <input type="text" name="search" class="vg-input vg-text" value="">
                    <div class="vg-element vg-firm-background-color4 vg-box-shadow search_links search_res">
                        <a href="\">link-1</a>
                        <a href="\">link-2</a>
                        <a href="\">link-3</a>
                        <a href="\">link-4</a>
                        <a href="\">link-5</a>
                    </div>
                </form>
            </div>
        </div>
        <!--кнопка-->
        <a href="<?= PATH . $this->adminAlias ?>/sitemap" class="vg-element vg-box-shadow sitemap-button">
            <span class="vg-text vg-firm-color1">Карта сайта</span>
        </a>
        <!--/кнопка-->
        <div class="vg-element vg-fifth">
            <div class="vg-element vg-half vg-right">
                <div class="vg-element vg-text vg-center">
                    <span class="vg-firm-color5">admin</span>
                </div>
            </div>
            <a href="/login/<?= $this->adminAlias ?>/logout/1" class="vg-element vg-half vg-center">
                <div>
                    <img src="<?= PATH . ADMIN_TEMPLATE ?>img/out.png" alt="">
                </div>
            </a>
        </div>
    </div>
</div>