siteMap()
createAddFiles()

function siteMap(){
    document.querySelector('.sitemap-button').onclick = (e) => {
        e.preventDefault();
        createSitemap();
    }
    let linksCounter = 0;

    function createSitemap() {
        linksCounter++;
        Ajax({data: {ajax: 'sitemap', linksCounter: linksCounter}})
            .then((res) => {
                console.log('success - ' + res)
            })
            .catch((res) => {
                console.log('error - ' + res)
                createSitemap();
            });
    }
}
function createAddFiles(){
    let files = document.querySelectorAll('input[type=file]');
    let fileStore = [];
    if (files.length) {
        files.forEach(item => {
            item.onchange = function () {
                let multiple = false
                let parentContainer
                let container
                if (item.hasAttribute('multiple')) {
                    multiple = true
                    parentContainer = this.closest('.gallery_container')
                    if (!parentContainer) return false;
                    container = parentContainer.querySelectorAll('.empty_container')
                    if (container.length < this.files.length) {
                        for (let index = 0; index < this.files.length - container.length; index++) {
                            let el = document.createElement('div')
                            el.classList.add('vg-dotted-square', 'vg-center', 'empty_container')
                            parentContainer.append(el)
                        }
                        container = parentContainer.querySelectorAll('.empty_container')
                    }
                }
                let fileName = item.name
                let attributeName = item.name.replace(/[\[\]]/g, '')
                for (let i in this.files) {
                    if (this.files.hasOwnProperty(i)) {
                        if (multiple) {
                            if (typeof fileStore[fileName] === 'undefined') {
                                fileStore[fileName] = []
                            }
                            let elId = fileStore[fileName].push(this.files[i]) - 1
                            container[i].setAttribute(`data-deleteFileId-${attributeName}`, elId)
                            showImage(this.files[i], container[i])
                            deleteNewFiles(elId, fileName, attributeName, container[i])
                        } else {
                            container = this.closest('.img_container').querySelector('.img_show')
                            showImage(this.files[i], container)
                        }
                    }
                }
            }
        })
        let form =document.querySelector('#main-form')
        if(form){
            form.onsubmit = function (e){
                if(!isEmpty(fileStore)){
                    e.preventDefault()
                    let fData = new FormData(this)
                    for (let i in fileStore){
                        if(fileStore.hasOwnProperty(i)){
                            fData.delete(i)
                            let rowName = i.replace(/[\[\]]/g, '')
                            fileStore[i].forEach((item, index) => {
                                fData.append(`${rowName}[${index}]`, item)
                            })
                        }
                    }
                    if (typeof ADMIN_MODE !== 'undefined'){
                        fData.append('ADMIN_MODE', String(ADMIN_MODE))
                    }
                    fData.append('ajax', 'editData')
                    Ajax({
                        url: this.getAttribute('action'),
                        type: 'post',
                        data: fData,
                        processData: false,
                        contentType: false
                    }).then(res=>{
                        console.log(res)
                        })
                }

            }
        }

        function deleteNewFiles(elId, fileName, attributeName, container){
            container.addEventListener('click', function () {
                this.remove()
                delete fileStore[fileName][elId]
            })
        }

        function showImage(item, container) {
            let reader = new FileReader()
            container.innerHTML = ''
            reader.readAsDataURL(item)
            reader.onload = e => {
                container.innerHTML = '<img class="img_item" src="" alt="img">'
                container.querySelector('img').setAttribute('src', e.target.result)
                container.classList.remove('empty_container')
            }
        }
    }
}

