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

async function addToOrder(itemId) {
    console.log("js - addToOrder(itemId)");
    let itemCount = document.getElementById("itemCart_" + itemId).textContent;
    let url = "/cart/add/" + itemId + '/' + itemCount + '/';
    let response = await fetch(url);

    if (response.ok) {
        let jsData = await response.json();
        if (jsData['success']) {
            //shopCartView.php + shopMenuLeft.php
            let el = document.getElementById("itemCart_" + itemId);
            let elText = el.textContent;
            el.outerHTML = "<input name=\""+ itemId +"\" id=\"itemCart_" + itemId + "\" type=\"number\" max=\"99\" " +
            "min=\"1\" size=\"3\" value=\""+elText+"\" autocomplete=\"off\" onchange=\"conversionPrice(" + itemId + ");\">";
            document.getElementById("addCart_" + itemId).style.display = "none";
            document.getElementById("removeCart_" + itemId).style.display = "block";
            document.getElementById("cartCountItems").textContent = jsData['countItems'];
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
            let el = document.getElementById("itemCart_" + itemId);
            let elValue = el.value;
            el.outerHTML = "<span id =\"itemCart_" + itemId + "\">" + elValue + "</span>";
            document.getElementById("addCart_" + itemId).style.display = "block";
            document.getElementById("removeCart_" + itemId).style.display = "none";
            document.getElementById("cartCountItems").textContent = jsData['countItems'];

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

async function conversionPrice(itemId) {
    console.log("js - conversionPrice(itemId)");
    let newCount = +document.querySelector('#itemCart_' + itemId).value;
    let price = +document.querySelector('#itemPrice_' + itemId).getAttribute('value');
    let url = "/cart/count/" + itemId + '/' + newCount + '/';
    let response = await fetch(url);
    if (response.ok) {
        let jsData = await response.json();
        if (jsData['success']) {
            document.querySelector("#itemRealPrice_" + itemId).innerText = newCount * price;
        }
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