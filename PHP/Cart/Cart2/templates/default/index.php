<main class="main-internal">
    <?php if (!empty($this->sales)) : ?>
        <section class="slider">
            <div class="slider__container swiper-container">
                <div class="slider__wrapper swiper-wrapper">
                    <?php foreach ($this->sales as $item): ?>
                        <div class="slider__item swiper-slide">
                            <div class="slider__item-description">
                                <a href="<?= $this->getUrl($item['external_url']); ?>" style="text-decoration: none">
                                    <div class="slider__item-prev-text"><?= $item['sub_title'] ?></div>
                                    <div class="slider__item-header">
                                        <?php foreach ($this->spaceArr($item['name']) as $value): ?>
                                            <span><?= $value ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="slider__item-text">
                                        <?= $this->clearTags($item['short_content'], false); ?>
                                    </div>
                                </a>
                                <div class="slider__item-logos">
                                    <?php if (!empty($this->set['img_years']) && !empty($this->set['number_years'])): ?>
                                        <div class="slider__item-15yrs">
                                            <img src="<?= $this->img($this->set['img_years']) ?>"
                                                 alt="<?= $this->wordsCounter($this->set['number_years']) ?>">
                                            <p>
                                                <span><?= $this->wordsCounter($this->set['number_years']) ?></span>на
                                                рынке
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <a class="slider__item-image" href="<?= $this->getUrl($item['external_url']); ?>"
                               style="text-decoration: none">
                                <img src="<?= $this->img($item['img']) ?>" alt="<?= $item['name'] ?>">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="slider__pagination swiper-pagination"></div>
                <div class="slider__controls controls _prev swiper-button-prev">
                    <svg>
                        <use href="<?= PATH . SITE_TEMPLATE ?>assets/img/icons.svg#arrow"></use>
                    </svg>
                </div>
                <div class="slider__controls controls _next swiper-button-next">
                    <svg>
                        <use href="<?= PATH . SITE_TEMPLATE ?>assets/img/icons.svg#arrow"></use>
                    </svg>

                </div>
            </div>
        </section>
    <?php endif; ?>
    <?php if (!empty($this->menu['catalog'])): ?>
        <section class="catalog">
            <div class="division-internal__items">
                <?php foreach ($this->menu['catalog'] as $item): ?>
                    <a href="<?= $this->getUrl(['catalog' => $item['alias']]) ?>" class="division-internal-item">
                    <span class="division-internal-item__title">
                         <?= $item['name'] ?>
                    </span>
                    <span class="division-internal-item__arrow-stat">
                        <svg>
                            <use xlink:href="<?= PATH . SITE_TEMPLATE ?>assets/img/icons.svg#arrow-right"></use>
                        </svg>
                    </span>
                    <span class="division-internal-item__arrow">
                        <img src="<?= PATH . SITE_TEMPLATE ?>assets/img/divisions/devision-arrow.png" alt="Далее">
                    </span>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
    <?php if (!empty($this->marketing['goods']) && !empty($this->marketing['all'])): ?>
        <section class="offers">
            <div class="offers__tabs">
                <ul class="offers__tabs_header">
                    <?php $i = -1; ?>
                    <?php foreach ($this->marketing['all'] as $key => $item): ?>
                        <?php if (!empty($this->marketing['goods'][$key])): ?>
                            <li<?= !++$i ? ' class="active"' : '' ?>>
                                <div class="icon-offer">
                                    <?= $item['icon'] ?>
                                </div>
                                <?= $item['name'] ?>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <?php $i = -1; ?>
                <?php foreach ($this->marketing['all'] as $key => $value): ?>
                    <?php if (!empty($this->marketing['goods'][$key])): ?>
                        <div class="offers__tabs_content<?= !++$i ? ' active' : '' ?>">
                            <div class="offers__tabs_subheader subheader">
                                <?= $value['name'] ?>
                            </div>
                            <div class="offers__tabs_container swiper-container">
                                <div class="offers__tabs_wrapper swiper-wrapper">
                                    <?php foreach ($this->marketing['goods'][$key] as $item) {
                                        $this->showOneItems($item, ['icon' => $value['icon']]);
                                    } ?>
                                </div>
                            </div>
                            <a href="<?= $this->getUrl('catalog') ?>" class="offers__readmore readmore">Смотреть
                                каталог</a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <div class="offers__controls controls _prev">
                    <svg>
                        <use xlink:href="<?= PATH . SITE_TEMPLATE ?>assets/img/icons.svg#arrow"></use>
                    </svg>
                </div>
                <div class="offers__controls controls _next">
                    <svg>
                        <use xlink:href="<?= PATH . SITE_TEMPLATE ?>assets/img/icons.svg#arrow"></use>
                    </svg>
                </div>
            </div>
        </section>
    <?php endif; ?>


    <div class="horizontal">
        <div class="horizontal__wrapper">
            <section class="about">
                <div class="about__description">
                    <div class="about__description_name subheader"><?= $this->set['name'] ?></div>
                    <div class="about__description_text">
                        <?= $this->set['short_content'] ?>
                    </div>
                    <a href="<?= $this->getUrl('about') ?>" class="about__description_readmore readmore">
                        Читать подробнее
                    </a>
                </div>
                <div class="about__image">
                    <img src="<?= $this->img($this->set['promo_img']) ?>" alt="<?= $this->set['name'] ?>">
                </div>
            </section>
            <?php if (!empty($this->advantages)): ?>
                <section class="advantages">
                    <div class="advantages__name subheader">
                        Наши преимущества
                    </div>
                    <div class="advantages__wrapper">
                        <div class="advantages__row advantages__row_left">
                            <?php $i = 0; ?>
                            <?php foreach ($this->advantages as $item): ?>
                            <div class="advantages__item">
                                <div class="advantages__item_header">
                                    <?= $item['name'] ?>
                                </div>
                                <img src="<?= $this->img($item['img']) ?>" class="advantages__item_image"
                                     alt="advantages">
                            </div>
                            <?php if (++$i == 3) : ?>
                        </div>
                        <div class="advantages__row advantages__row_right">
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>

    <section class="feedback ">
        <div class="feedback__name subheader ">Остались вопросы</div>
        <form action="index.html" class="feedback__form">
            <div class="feedback__form_left">
                <label>
                    <input type="text" class="input-text feedback__input" placeholder="Ваше имя">
                </label>
                <label>
                    <input type="email" class="input-text feedback__input" placeholder="E-mail">
                </label>
                <label>
                    <input type="text" class="input-text feedback__input js-mask-phone" placeholder="Телефон">
                </label>
            </div>
            <div class="feedback__form_right">
                <label>
                    <textarea class="input-textarea feedback__textarea" placeholder="Ваш вопрос"></textarea>
                </label>
            </div>
            <div class="feedback__privacy">
                <label class="checkbox">
                    <input type="checkbox"/>
                    <div class="checkbox__text">Соглашаюсь с правилами обработки персональных данных</div>
                </label>
            </div>
            <button type="submit" class="form-submit feedback__submit">Отправить</button>
        </form>
    </section>
    <?php if (!empty($this->news)): ?>
        <section class="news">
            <div class="news__name subheader">
                Новости
            </div>
            <div class="news__wrapper">
                <?php foreach ($this->news as $item) {
                    $this->showOneItems($item, [], 'cardOneNews');
                } ?>
            </div>
            <a href="<?= $this->getUrl('news') ?>" class="news__reasdmore readmore">Смотреть все</a>
        </section>
    <?php endif; ?>
    <div class="search ">
        <button>
            <svg class="inline-svg-icon svg-search">
                <use xlink:href="<?= PATH . SITE_TEMPLATE ?>assets/img/icons.svg#search"></use>
            </svg>
        </button>
            <input type="search" placeholder="Поиск по каталогу">
    </div>
</main>