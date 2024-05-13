document.querySelector('.sitemap-button').onclick = (e) => {
    e.preventDefault();
    createSitemap();
}
let linksCounter = 0;
function createSitemap(){
    linksCounter++;
    Ajax({data: {ajax:'sitemap', linksCounter:linksCounter}})
        .then((res) => {
            console.log('success - ' + res)
        })
        .catch((res) => {
            console.log('error - ' + res)
            createSitemap();
        });
}