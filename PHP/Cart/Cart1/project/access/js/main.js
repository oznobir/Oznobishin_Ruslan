async function addToCart(itemId) {
    console.log("js - addToCart(itemId)");
    let itemCount = document.getElementById("itemCart_" + itemId).value;
    let url = "/cart/add/" + itemId + '/' + itemCount + '/';
    let response = await fetch(url);

    if (response.ok) {
        let jsData = await response.json();
        if (jsData['success']) {
            //shopOneProductView.php + shopMenuLeft.php
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
            //shopMenuLeft.php
            document.getElementById("cartCountItems").textContent = jsData['countItems'];
            //shopOneProductView.php + shopMenuLeft.php
            document.getElementById("addCart_" + itemId).style.display = "block";
            document.getElementById("removeCart_" + itemId).style.display = "none";
        }
    }
}

async function addToOrder(itemId) {
    console.log("js - addToOrder(itemId)");
    let itemCount = document.getElementById("itemCart_" + itemId).textContent;
    let url = "/cart/add/" + itemId + '/' + itemCount + '/';
    let response = await fetch(url);

    if (response.ok) {
        let jsData = await response.json();
        if (jsData['success']) {
            //shopCartView.php + shopMenuLeft.php - корзину убрал
            let elItem = document.getElementById("itemCart_" + itemId);
            let itemText = elItem.textContent;
            let priceText = +document.getElementById("itemRealPrice_" + itemId).textContent;
            let elTotal = document.getElementById("totalPrice");
            let totalText = +elTotal.textContent;
            let newTotal = totalText + priceText;
            elItem.outerHTML = "<input name=\"" + itemId + "\" id=\"itemCart_" + itemId + "\" type=\"number\" max=\"99\" " +
                "min=\"1\" size=\"3\" value=\"" + itemText + "\" autocomplete=\"off\" onchange=\"conversionPrice(" + itemId + ");\">";
            elTotal.textContent = String(newTotal);
            // elItem.closest('tr').classList.add('show');
            document.getElementById("addCart_" + itemId).style.display = "none";
            document.getElementById("removeCart_" + itemId).style.display = "block";
            // document.getElementById("cartCountItems").textContent = jsData['countItems']; - корзину убрал
        }
    }
}


async function removeFromOrder(itemId) {
    console.log("js - removeFromOrder(itemId)");
    let url = "/cart/remove/" + itemId + '/';
    let response = await fetch(url);

    if (response.ok) {
        let jsData = await response.json();
        if (jsData['success']) {
            //shopCartView.php + shopMenuLeft.php
            let elItem = document.getElementById("itemCart_" + itemId);
            let itemValue = elItem.value;
            let elTotal = document.getElementById("totalPrice");
            let totalText = +elTotal.textContent;
            let priceText = +document.getElementById("itemRealPrice_" + itemId).textContent;
            elItem.outerHTML = "<span id =\"itemCart_" + itemId + "\">" + itemValue + "</span>";
            elTotal.textContent = String(totalText - priceText);
            // elItem.closest('tr').classList.add('show');
            document.getElementById("addCart_" + itemId).style.display = "block";
            document.getElementById("removeCart_" + itemId).style.display = "none";
            // document.getElementById("cartCountItems").textContent = jsData['countItems']; - корзину убрал
        }
    }
}

async function conversionPrice(itemId) {
    console.log("js - conversionPrice(itemId)");
    let itemCount = +document.querySelector('#itemCart_' + itemId).value;
    let url = "/cart/add/" + itemId + '/' + itemCount + '/';
    let response = await fetch(url);
    if (response.ok) {
        let jsData = await response.json();
        if (jsData['success']) {
            let itemPrice = +document.querySelector('#itemPrice_' + itemId).textContent;
            let elItemRealPrice = document.querySelector('#itemRealPrice_' + itemId);
            let oldItemRealPrice = +elItemRealPrice.textContent;
            let newItemRealPrice = itemPrice * itemCount;
            elItemRealPrice.textContent = String(newItemRealPrice);
            let elTotal = document.getElementById("totalPrice");
            let totalPrice = +elTotal.textContent;
            let newTotal = totalPrice - oldItemRealPrice + newItemRealPrice;
            elTotal.textContent = String(newTotal);
        }
    }
}


async function updateUserData() {
    console.log("js - updateUserData()");
    let response = await fetch("/user/update/", {
        method: 'POST',
        body: new FormData(document.querySelector('#userForm'))
    });
    if (response.ok) {
        let jsData = await response.json();
        alert(jsData['message']);
    }
}

async function registerNewUser() {
    console.log("js - registerNewUser(itemId)");
    let response = await fetch("/user/register/", {
        method: 'POST',
        body: new FormData(document.querySelector('#registerBoxHidden'))
    });
    if (response.ok) {
        let jsData = await response.json();
        alert(jsData['message']);
        if (jsData['success']) {
            document.querySelector("#userBox").style.display = "block"
            document.querySelector('#userLink').innerHTML = jsData.user.displayName;
            document.querySelector("#authBox").style.display = "none";
            document.querySelector("#registerBox").style.display = "none";
        }
    }
}

async function login() {
    console.log("js - login()");
    let data = new FormData(document.querySelector("#loginBox"));
    let response = await fetch("/user/login/", {
        method: 'POST',
        body: data
    });
    if (response.ok) {
        let jsData = await response.json();
        alert(jsData['message']);
        if (jsData['success']) {
            document.querySelector("#userBox").style.display = "block";
            document.querySelector("#authBox").style.display = "none";
            document.querySelector("#registerBox").style.display = "none";
            document.querySelector('#userLink').innerHTML = jsData.user.displayName;
            // document.querySelector("#userBox").classList.remove('hidden');
            // document.querySelector("#registerBox").classList.add('hidden');
            // document.querySelector("#loginBox").classList.add('hidden');
        }
    }
}

function showHiddenRegisterBox() {
    if (document.querySelector("#registerBoxHidden").style.display !== "block") {
        document.querySelector("#registerBoxHidden").style.display = "block";
    } else {
        document.querySelector("#registerBoxHidden").style.display = "none";
    }
}

function showHiddenLoginBox() {
    if (document.querySelector("#loginBox").style.display !== "block") {
        document.querySelector("#loginBox").style.display = "block";
    } else {
        document.querySelector("#loginBox").style.display = "none";
    }
}