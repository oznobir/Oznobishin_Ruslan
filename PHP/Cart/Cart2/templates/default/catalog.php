<main class="main-internal">
    <div class="container">
        <?= $this->breadcrumbs ?>
        <h1 class="page-title h1"><?= $this->data['name'] ?></h1>
    </div>

    <section class="catalog-internal">
        <div class="container">
            <div class="catalog-internal-wrap">
                <?php if (empty($this->goods)): ?>
                    <h2>Товары не найдены</h2>
                <?php else: ?>
                    <?php if (!empty($this->sFilters) || !empty($this->sPrices)): ?>
                        <aside class="catalog-aside">
                            <div class="catalog-aside__wrap">
                                <div class="catalog-aside-block">
                                    <div class="catalog-aside-block__top">
                                        <div class="catalog-aside-block__title h2">
                                            Фильтры
                                        </div>
                                        <div class="catalog-aside-sort-mobile">
                                            <div class="catalog-aside-sort-mobile__button h2">
                                                сортировка
                                            </div>
                                        </div>
                                        <button class="catalog-filter-wrap__remove"
                                                onclick="location.href=location.pathname">Очистить все
                                        </button>
                                    </div>
                                    <div class="catalog-aside-block__content catalog-aside-block__drop">
                                        <div class="catalog-aside-block__drop-close">
                                            <svg viewBox="0 0 27.33 27.01" width="100%" height="100%">
                                                <path d="M26.69.32a1.08 1.08 0 0 0-1.54 0L.32 25.15a1.08 1.08 0 0 0 0 1.54 1.09 1.09 0 0 0 1.54 0L26.69 1.86a1.08 1.08 0 0 0 0-1.54z"></path>
                                                <path d="M27 25.15L1.88.32a1.1 1.1 0 0 0-1.56 0 1.08 1.08 0 0 0 0 1.54l25.12 24.83a1.13 1.13 0 0 0 .78.32 1.11 1.11 0 0 0 .78-.32 1.08 1.08 0 0 0 0-1.54z"></path>
                                            </svg>
                                        </div>
                                        <form action="<?= $this->getUrl(['catalog' => $this->parameters['alias'] ?? '']) ?>"
                                              class="catalog-filter">
                                            <?php if (!empty($this->sPrices)): ?>
                                                <div class="catalog-filter-item catalog-filter-item_open">
                                                    <div class="catalog-filter-item__title">
                                                        Цена
                                                        <span class="catalog-filter-item__toggle"></span>
                                                    </div>
                                                    <div class="catalog-filter-item__content">
                                                        <div class="catalog-range-slider" style="margin-bottom: 60px">
                                                            <div class="catalog-filter-range__inputs">
                                                                <div class="catalog-filter-range__limit">
                                                                    <div class="catalog-filter-range__text">
                                                                        От
                                                                    </div>
                                                                    <label>
                                                                        <input type="text" name="min_price"
                                                                               value="<?= $this->sPrices['min_price'] ?>"
                                                                               class="catalog-filter-range__input js-rangeSliderMinimal">
                                                                    </label>
                                                                </div>
                                                                <div class="catalog-filter-range__limit">
                                                                    <div class="catalog-filter-range__text">
                                                                        До
                                                                    </div>
                                                                    <label>
                                                                        <input value="<?= $this->sPrices['max_price'] ?>"
                                                                               name="max_price" type="text"
                                                                               class="catalog-filter-range__input js-rangeSliderMaximal">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($this->sFilters)): ?>
                                                <?php foreach ($this->sFilters as $item): ?>
                                                    <div class="catalog-filter-item">
                                                        <div class="catalog-filter-item__title">
                                                            <?= $item['filters_name'] ?>
                                                            <span class="catalog-filter-item__toggle"></span>
                                                        </div>
                                                        <div class="catalog-filter-item__content">
                                                            <ul class="catalog-filter-item__list">
                                                                <?php foreach ($item['values'] as $value): ?>
                                                                    <li class="catalog-filter-item__unit">
                                                                        <label class="catalog-filter-item__check checkbox">
                                                                            <input type="checkbox" name="filters[]"
                                                                                   value="<?= $value['id'] ?>"
                                                                                <?= !empty($_GET['filters']) && in_array($value['id'], $_GET['filters']) ? ' checked ' : '' ?>
                                                                                   class="checkbox__box visually-hidden">
                                                                            <span class="checkbox__span">
                                                                            </span>
                                                                            <span class="checkbox__text">
                                                                                  <?= $value['filters_name'] ?>
                                                                            </span>
                                                                            <span class="checkbox__type">
                                                                                  (<?= $value['count'] ?? 0 ?>)
                                                                             </span>
                                                                        </label>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <button class="card-item__btn">Применить</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    <?php endif; ?>
                    <section class="catalog-section catalog-section__four">
                        <div class="catalog-section-top">
                            <div class="catalog-section-top-items">
                                <?php if (!empty($this->sOrders)): ?>
                                    <div class="catalog-section-top-items__title catalog-section-top-items__unit">
                                        Сортировать по:
                                    </div>
                                    <?php foreach ($this->sOrders as $name => $item): ?>
                                        <a href="<?= $this->getUrl(['catalog' => $this->parameters['alias'] ?? ''], array_merge($_GET ?? [], ['order' => $item])); ?>"
                                           class="catalog-section-top-items__unit catalog-section-top-items__toggle <?= str_ends_with($item, '_desc') ? 'order-desc' : '' ?>">
                                            <?= $name ?>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php if (!empty($this->sQuantities)): ?>
                                    <div class="catalog-section-top-items__unit catalog-section-top-items__toggle"
                                         onclick="this.querySelector('.qtyItems').classList.toggle('opened')">
                                        Показывать по: <span><?= $_SESSION['quantities'] ?? '' ?></span>
                                        <div class="qtyItems">
                                            <?php foreach ($this->sQuantities as $item): ?>
                                                <a href="#"><?= $item ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="catalog-section__wrapper">
                            <div class="catalog-section-items">
                                <div class="catalog-section-items__wrapper">
                                    <?php foreach ($this->goods as $item) {
                                        $this->showOneItems($item, [
                                            'mainClass' => 'card-item card-item__internal',
                                            'prefixAddClass' => 'card-item']);
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($this->sPagination)): ?>
                            <div class="catalog-section-pagination">
                                <?= $this->pagination($this->sPagination) ?>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>