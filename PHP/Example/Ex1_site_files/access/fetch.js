async function sendRequest() {
    let my_form = new FormData(document.forms.form);
    const lists = document.querySelectorAll('.text2-panel.php');
    let blob = [];
    let path = [];
    lists.forEach(function(elem, ind) {
        blob[ind] = new Blob([elem.innerText.replace(/ /gi, ' ')], {type: 'text/html'});
        path[ind] = elem.children[0].value;
        my_form.append("php" + ind, blob[ind], path[ind]);
    });
    let response = await fetch('template/response.php', {
        method: 'POST', body: my_form
    });
    if (response.ok) {
        document.getElementById("result").innerHTML = await response.text();
    }
}