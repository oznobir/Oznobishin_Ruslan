async function addToCart(itemId) {
    console.log("js - addToCart(itemId)");
    let itemCount = document.getElementById("itemCart_" + itemId).value;
    let url = "/cart/add/" + itemId + '/' + itemCount + '/';
    let response = await fetch(url);

    if (response.ok) {
        let jsData = await response.json();
        if (jsData['success']) {
            document.getElementById("cartCountItems").textContent = jsData['countItems'];
            document.getElementById("addCart_" + itemId).classList.toggle("hidden");
            document.getElementById("removeCart_" + itemId).classList.toggle("hidden");
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
            document.getElementById("addCart_" + itemId).classList.toggle("hidden");
            document.getElementById("removeCart_" + itemId).classList.toggle("hidden");
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
            const elItem = document.getElementById("itemCart_" + itemId);
            const elTotal = document.getElementById("totalPrice");
            const elItemRealPrice = document.getElementById("itemRealPrice_" + itemId);

            elItemRealPrice.textContent = elItemRealPrice.getAttribute('value');
            let newTotal = +elTotal.textContent + +elItemRealPrice.getAttribute('value');
            elTotal.textContent = String(newTotal);
            elItem.outerHTML = "<input name=\"products[" + itemId + "]\" id=\"itemCart_" + itemId + "\" type=\"number\" max=\"99\" " +
                "min=\"1\" size=\"3\" value=\"" + itemCount + "\" autocomplete=\"off\" onchange=\"conversionPrice(" + itemId + ");\">";
            // elItem.closest('tr').classList.add('show');
            document.getElementById("addCart_" + itemId).classList.toggle("hidden");
            document.getElementById("removeCart_" + itemId).classList.toggle("hidden");
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
            const elItem = document.getElementById("itemCart_" + itemId);
            const elTotal = document.getElementById("totalPrice");
            const elRealPrice = document.getElementById("itemRealPrice_" + itemId);

            elItem.outerHTML = "<span id =\"itemCart_" + itemId + "\">" + elItem.value + "</span>";
            elTotal.textContent = String(+elTotal.textContent - elRealPrice.textContent);
            elRealPrice.setAttribute('value', elRealPrice.textContent);
            elRealPrice.textContent = '0';
            // elItem.closest('tr').classList.add('show');
            document.getElementById("addCart_" + itemId).classList.toggle("hidden");
            document.getElementById("removeCart_" + itemId).classList.toggle("hidden");
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
            const elTotal = document.getElementById("totalPrice");
            const elItemRealPrice = document.querySelector('#itemRealPrice_' + itemId);

            let itemPrice = +document.querySelector('#itemPrice_' + itemId).textContent;
            let oldItemRealPrice = +elItemRealPrice.textContent;
            let newItemRealPrice = Math.round(itemPrice * itemCount * 100) / 100;
            elItemRealPrice.textContent = String(newItemRealPrice);
            elTotal.textContent = String(Math.round((+elTotal.textContent - oldItemRealPrice + newItemRealPrice)*100)/100);
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

async function registerNewUser(url = null) {
    console.log("js - registerNewUser(itemId)");
    let response = await fetch("/user/register/", {
        method: 'POST',
        body: new FormData(document.querySelector('#registerBoxHidden'))
    });
    if (response.ok) {
        let jsData = await response.json();
        alert(jsData['message']);
        if (jsData['success']) {
            if (url) {
                document.location.href = url;
            } else {
                document.querySelector("#userBox").classList.toggle("hidden");
                document.querySelector('#userLink').innerHTML = jsData.user.displayName;
                document.querySelector("#authBox").classList.toggle("hidden");
                document.querySelector("#registerBox").classList.toggle("hidden");
            }
        }
    }
}

async function login(url = null) {
    console.log("js - login()");
    let data = new FormData(document.querySelector("#loginBoxHidden"));
    let response = await fetch("/user/login/", {
        method: 'POST',
        body: data
    });
    if (response.ok) {
        let jsData = await response.json();
        alert(jsData['message']);
        if (jsData['success']) {
            if (url) {
                document.location.href = url;
            } else {
                document.querySelector("#userBox").classList.toggle("hidden");
                document.querySelector("#authBox").classList.toggle("hidden");
                document.querySelector("#registerBox").classList.toggle("hidden");
                document.querySelector('#userLink').innerHTML = jsData.user.displayName;
            }
        }
    }
}

function showHiddenRegisterBox() {
    if (document.querySelector("#registerBoxHidden").style.display !== "block") {
        document.querySelector("#registerBoxHidden").style.display = "block";
        document.querySelector("#loginBoxHidden").style.display = "none";
    } else {
        document.querySelector("#registerBoxHidden").style.display = "none";
        document.querySelector("#loginBoxHidden").style.display = "block";
    }
}

function showHiddenLoginBox() {
    if (document.querySelector("#loginBoxHidden").style.display !== "block") {
        document.querySelector("#loginBoxHidden").style.display = "block";
        document.querySelector("#registerBoxHidden").style.display = "none";
    } else {
        document.querySelector("#loginBoxHidden").style.display = "none";
        document.querySelector("#registerBoxHidden").style.display = "block";
    }
}