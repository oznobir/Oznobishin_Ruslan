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
