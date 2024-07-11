<main class="main-internal">

    <div class="container">
        <h1 class="page-title h1"> SVG icons.svg</h1>
        <section class="catalog-internal">
            <div class="container">
                <div class="catalog-section__wrapper">
                    <?php foreach ($this->svgIcons as $item): ?>
                                <div class="offers__tabs_image">
                                    <svg>
                                        <use xlink:href="<?= PATH . SITE_TEMPLATE ?>assets/img/icons.svg#<?= $item ?>"></use>
                                    </svg>
                                </div>
                                <div class="offers__tabs_name">
                                    <?= $item ?> :
                                </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>

</main>