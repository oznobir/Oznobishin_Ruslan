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
async function updateUserData() {
    console.log("js - updateUserData()");
    // let phone = document.getElementById("newPhone").value;
    // let address = document.getElementById("newAddress").value;
    // let pwd1= document.getElementById("newPwd1").value;
    // let pwd2 = document.getElementById("newPwd2").value;
    // let curPwd = document.getElementById("pwd").value;
    // let name = document.getElementById("newName").value;
    // let postData = { phone: phone, address: address, pwd1: pwd1, pwd2: pwd2, curPwd: curPwd, name: name};
    let response = await fetch("/user/update/",{
        method: 'POST',
        body: new FormData(document.querySelector('#userForm'))
        // body: postData
    });
    if (response.ok) {
        let jsData = await response.json();
        alert(jsData['message']);
        // if (jsData['success']) {
        //     document.querySelector('#userLink').innerHTML = jsData.user.displayName;
        // }
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