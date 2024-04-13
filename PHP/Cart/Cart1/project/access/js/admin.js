async function newCategory() {
    console.log("js - newCategory()");
    const slug = document.querySelector('#newCategoryName').value;
    const title = document.querySelector('#newCategorySlug').value;
    if (!slug || !title) {
        alert('Не все данные заполнены');
        return;
    }
    let response = await fetch("/admin/category/add/", {
        method: 'POST',
        body: new FormData(document.querySelector('#formCategories'))
    });
    if (response.ok) {
        let jsData = await response.json();
        alert(jsData['message']);
        if (jsData['success']) {
            window.location.href = '/admin/';
        }
    }
}

async function updateCategory(itemId) {
    console.log("js - updateCategory(itemId)");
    const elSlug = document.querySelector('#slug_' + itemId);
    const elTitle = document.querySelector('#title_' + itemId);
    const elParentId = document.querySelector('#parentId_' + itemId);
    let next = false;
    let data = new FormData();
    if (elSlug.dataset.old !== elSlug.value) {
        data.append('slug', elSlug.value);
        next = true;
    }
    if (elTitle.dataset.old !== elTitle.value) {
        data.append('title', elTitle.value);
        next = true;
    }
    if (elParentId.dataset.old !== elParentId.value) {
        data.append('parentId', elParentId.value);
        next = true;
    }
    if (next) {
        data.append('id', itemId);
    } else {
        alert('Данные не редактировались');
        return;
    }
    let response = await fetch("/admin/category/update/", {
        method: 'POST',
        body: data
    });
    if (response.ok) {
        let jsData = await response.json();
        alert(jsData['message']);
    }
}

async function addProduct() {
    console.log("js - addProduct()");
    const slug = document.querySelector('#newSlug').value;
    const title = document.querySelector('#newTitle').value;
    const price = document.querySelector('#newPrice').value;
    const description = document.querySelector('#newDescription').value;
    const cid = document.querySelector('#newCategoryId').value;

    if (!slug || !title || !price || !description) {
        alert('Не все данные заполнены');
        return;
    }
    let data = new FormData();
    data.append('slug', slug);
    data.append('title', title);
    data.append('price', price);
    data.append('description', description);
    data.append('cid', cid);
    let response = await fetch("/admin/product/add/", {
        method: 'POST',
        body: data
    });
    if (response.ok) {
        let jsData = await response.json();
        alert(jsData['message']);
        if (jsData['success']) {
            window.location.href = '/admin/products/';
        }
    }
}

async function updateProduct(itemId) {
    console.log("js - updateProduct(itemId)");
    const slug = document.querySelector('#slug_' + itemId);
    const title = document.querySelector('#title_' + itemId);
    const categoryId = document.querySelector('#categoryId_' + itemId);
    const status = document.querySelector('#status_' + itemId);
    const price = document.querySelector('#price_' + itemId);
    const description = document.querySelector('#description_' + itemId);
    let next = false;
    let data = new FormData();
    if (slug.dataset.old !== slug.value) {
        data.append('slug', slug.value);
        next = true;
    }
    if (title.dataset.old !== title.value) {
        data.append('title', title.value);
        next = true;
    }
    if (categoryId.dataset.old !== categoryId.value) {
        data.append('categoryId', categoryId.value);
        next = true;
    }
    if (status.dataset.old !== status.value) {
        data.append('status', status.value);
        next = true;
    }
    if (price.dataset.old !== price.value) {
        data.append('price', price.value);
        next = true;
    }
    if (description.dataset.old !== description.value) {
        data.append('description', description.value);
        next = true;
    }
    if (next) {
        data.append('id', itemId);
    } else {
        alert('Данные не редактировались');
        return;
    }
    let response = await fetch("/admin/product/update/", {
        method: 'POST',
        body: data
    });
    if (response.ok) {
        let jsData = await response.json();
        alert(jsData['message']);
    }
}

async function addProductImage(input, itemId) {
    console.log("js - addProductImage(input, itemId)");
    let file = input.files[0];
    let data = new FormData();
    data.append('itemId', itemId);
    data.append('lastModified', file.lastModified);
    data.append('filename', file, file.name);
    let response = await fetch("/admin/product/image/", {
        method: 'POST',
        body: data
    });
    if (response.ok) {
        let jsData = await response.json();
        alert(jsData['message']);
        if (jsData['success']) {
            window.location.href = '/admin/products/';
        }
    }
}
