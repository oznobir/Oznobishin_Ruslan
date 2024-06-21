<div class="container">
    <div class="footer__wrapper">
        <div class="footer__top">
            <div class="footer__top_logo">
                <img src="<?= $this->img($this->set['img_logo']); ?>" alt="<?= $this->set['name'] ?>">
            </div>
            <div class="footer__top_menu">
                <ul>

                    <li>
                        <a href="<?= $this->getUrl('catalog'); ?>"><span>Каталог</span></a>
                    </li>
                    <?php if (!empty($this->menu['information'])) : ?>
                        <?php foreach ($this->menu['information'] as $item) : ?>
                            <li>
                                <a href="<?= $this->getUrl(['information' => $item['alias']]); ?>">
                                    <span><?= $item['name'] ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>

                    <?php endif; ?>
                    <li>
                        <a href="<?= $this->getUrl('news'); ?>"><span>Новости</span></a>
                    </li>
                    <li>
                        <a href="<?= $this->getUrl('contacts'); ?>"><span>Контакты</span></a>
                    </li>
                    <li>
                        <a href="/"><span>Карта сайта</span></a>
                    </li>

                </ul>
            </div>
            <div class="footer__top_contacts">
                <div><a href="mailto:<?= $this->set['email'] ?>"><?= $this->set['email'] ?></a></div>
                <div><a href="tel:<?= preg_replace('/[^+\d]/', '', $this->set['phone']); ?>"><?= $this->set['phone'] ?></a></div>
                <div><a class="js-callback">Связаться с нами</a></div>
            </div>
        </div>
        <div class="footer__bottom">
            <div class="footer__bottom_copy">Copyright</div>
        </div>
    </div>
</div>