<?php

namespace core\site\controllers;

use core\site\controllers\BaseSite;

class SvgController extends BaseSite
{
    protected array $svgIcons;
    protected function inputData(): void
    {
        parent::inputData();
        $this->svgIcons = [];
        for ($i = 1; $i < 40; $i++)
            $this->svgIcons[$i] = 'd' . $i;
        $arrIcons = ['view1', 'arrow-right','view2','view3','personality','instagram','vk','facebook','search',
            'arrow','armature','pribor','izolation','kanalization','kotli','luk','hit','hot'];
        foreach ($arrIcons as $icon)
            $this->svgIcons[$icon] = $icon;
    }
}