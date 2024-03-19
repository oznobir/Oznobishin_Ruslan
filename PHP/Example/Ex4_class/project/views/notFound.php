<p>
	Запрошенная вами страница не найдена!
    <button class="main-menu-btn"><a href="/menu/">Содержание</a></button>
</p>
<p>
	Скорее всего дело в том, что для данного URL не прописан route
	в файле с routes, расположенном по адресу <i>/project/config/routes.php</i>.
    Также может посмотреть в htaccess. Также слэши в конце ссылки...
</p>
<p>
    Потом в router сделать redirect на главную...
</p>