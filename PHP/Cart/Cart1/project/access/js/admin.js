async function newCategory() {
    console.log("js - newCategory()");
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
    let data = new FormData();
    data.append('id', itemId);
    data.append('slug', document.querySelector('#slug_'+itemId).value);
    data.append('title', document.querySelector('#title_'+itemId).value);
    data.append('parentId', document.querySelector('#parentId_'+itemId).value);
    let response = await fetch("/admin/category/update/", {
        method: 'POST',
        body: data
    });
    if (response.ok) {
        let jsData = await response.json();
        alert(jsData['message']);
        if (jsData['success']) {
            window.location.href = '/admin/';
        }
    }
}
