<?php include 'shopHeaderLayout.php'?>
<p>
	Запрошенная вами страница не найдена!
</p>
<p>
	Скорее всего дело в том, что для данного URL не прописан route
	в файле с routes, расположенном по адресу <i>/project/config/routes.php</i>.
    Также может посмотреть в htaccess. Также слэши в конце ссылки...
</p>
<p>
    Потом в router сделать redirect на главную...
</p>
<p><a href="/">Каталог товаров</a></p>
<?php include 'shopFooterLayout.php'?>