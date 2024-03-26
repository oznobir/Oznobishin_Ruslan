async function addToCart(itemId) {
    console.log("js - addToCart(itemId)");
    let url = "/cart/add/" + itemId + '/';
    let response = await fetch(url);

    if (response.ok) {
        let jsData = await response.json();
        if (jsData['success']) {
            document.getElementById("cartCountItems").textContent = jsData['countItems'];
            document.getElementById("addCart_" + itemId).style.display = "none";
            document.getElementById("removeCart_" + itemId).style.display = "block";
        }
    }
}

async function removeFromCart(itemId) {
    console.log("js - removeFromCart(itemId)");
    let url = "/cart/remove/" + itemId + '/';
    let response = await fetch(url);
    if (response.ok) {
        let jsData = await response.json();
        if (jsData['success']) {
            document.getElementById("cartCountItems").textContent = jsData['countItems'];
            document.getElementById("addCart_" + itemId).style.display = "block";
            document.getElementById("removeCart_" + itemId).style.display = "none";
        }
    }
}

function conversionPrice(itemId) {
    console.log("js - conversionPrice(itemId)");
    let newCount = +document.querySelector('#itemCount_' + itemId).value;
    let price = +document.querySelector('#itemPrice_' + itemId).getAttribute('value');
    document.querySelector("#itemRealPrice_" + itemId).innerText = newCount * price;
}

async function registerNewUser() {
    console.log("js - registerNewUser(itemId)");

    let response = await fetch("/user/register/", {
        method: 'POST',
        body: new FormData(document.forms.registerBoxHidden)
    });
    if (response.ok) {
        let jsData = await response.json();
        alert(jsData['message']);
        if (jsData['success']) {
            document.getElementById("registerBox").style.display = "none";
        }
    }
}