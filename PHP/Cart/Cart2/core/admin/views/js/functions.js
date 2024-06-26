const Ajax = (set) => {
    if (typeof set === 'undefined') set = {};
    if (typeof set.url === 'undefined' || !set.url) {
        set.url = typeof PATH !== 'undefined' ? PATH : '/';
    }
    if (typeof set.type === 'undefined' || !set.type) set.type = 'GET';
    set.type = set.type.toUpperCase();
    if (typeof set.ajax === 'undefined') set.ajax = true;
    let body = '';
    if (typeof set.processData !== 'undefined' && !set.processData) {
        body = set.data
        if (typeof ADMIN_MODE !== 'undefined') {
            body.append('ADMIN_MODE', String(ADMIN_MODE))
        }
    } else {
        if (typeof set.data !== 'undefined' && set.data) {
            for (let i in set.data) {
                if (set.data.hasOwnProperty(i))
                    body += '&' + i + '=' + set.data[i];
            }
            body = body.substring(1);
        }
        if (typeof ADMIN_MODE !== 'undefined') {
            body += body ? '&' : '';
            body += 'ADMIN_MODE=' + ADMIN_MODE;
        }
    }
    if (set.type === 'GET') {
        set.url += '?' + body;
        body = null;
    }
    if (typeof set.res === 'undefined' || !set.res) set.res = 'json';

    return new Promise((resolve, reject) => {
        let xhr = new XMLHttpRequest();
        xhr.open(set.type, set.url, true);
        let contentType = false;
        if (typeof set.headers !== 'undefined' && set.headers) {
            for (let i in set.headers) {
                if (set.headers) {
                    xhr.setRequestHeader(i, set.headers[i]);
                    if (i.toLowerCase() === 'content-type') contentType = true;
                }
            }
        }
        if (!contentType && (typeof set.contentType === 'undefined' || set.contentType)) {
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        }
        if (set.ajax) xhr.setRequestHeader('X-Requested-With', 'AjaxRequest');
        if (set.res) xhr.responseType = set.res

        xhr.onload = function () {
            if (this.status >= 200 && this.status < 300) {
                if (/fatal\s+?error/ui.test(this.response)) {
                    reject(this.response);
                }
                resolve(this.response);
            }
            reject(this.response);
        }
        xhr.onerror = function () {
            reject(this.response);
        }
        xhr.send(body);
    });
}

function isEmpty(arr) {
    for (let i in arr) {
        return false
    }
    return true;
}

function errorAlert() {
    alert('Произошла внутренняя ошибка')
    return false;
}


Element.prototype.slideToggle = function (time, callback) {
    let _time = typeof time === 'number' ? time : 400
    callback = typeof time === 'function' ? time : callback
    // if (!this.style.maxHeight || +this.style.maxHeight.replace(/\D/, '') === 0) {
    if (getComputedStyle(this)['display'] === 'none') {
        this.style.transition = null
        this.style.overflow = 'hidden'
        this.style.maxHeight = 0
        this.style.display = 'block'
        this.style.transition = _time + 'ms'
        this.style.maxHeight = this.scrollHeight + 'px'
        setTimeout(() => {
            callback && callback()
        }, _time)
    } else {
        this.style.transition = _time + 'ms'
        this.style.maxHeight = 0
        setTimeout(() => {
            this.style.transition = null
            this.style.display = 'none'
            callback && callback()
        }, _time)
    }
}
Element.prototype.sortable = (function () {
    let dragEl, nextEl

    function _unDraggable(elements) {
        if (elements && elements.length) {
            for (let i = 0; i < elements.length; i++) {
                if (!elements[i].hasAttribute('draggable')) {
                    elements[i].draggable = false
                    _unDraggable(elements[i].children)
                }
            }
        }
    }

    function _onDragStart(e) {
        e.stopPropagation()
        this.tempTarget = null
        dragEl = e.target
        nextEl = dragEl.nextSibling
        e.dataTransfer.dropEffect = 'move'
        this.addEventListener('dragover', _onDragOver, false)
        this.addEventListener('dragend', _onDragEnd, false)
    }

    function _onDragOver(e) {
        e.preventDefault()
        e.stopPropagation()
        e.dataTransfer.dropEffect = 'move'
        let target
        if (e.target !== this.tempTarget) {
            this.tempTarget = e.target
            target = e.target.closest('[draggable=true]')
        }

        if (target && target !== dragEl && target.parentElement === this) {
            let rect = target.getBoundingClientRect()
            let next = (rect.right - e.clientX) > (e.clientY - rect.top)
            this.insertBefore(dragEl, next && target.nextSibling || target)
        }
    }

    function _onDragEnd(e) {
        e.preventDefault()
        this.removeEventListener('dragover', _onDragOver, false)
        this.removeEventListener('dragend', _onDragEnd, false)
        if (nextEl !== dragEl.nextSibling) {
            this.onDragUpdate && this.onDragUpdate(dragEl)
        }
    }

    return function (options) {
        options = options || {}
        this.onDragUpdate = options.stop || null
        let excludedElements = options.excludedElements && options.excludedElements.split(/,*\s+/) || null;
        [...this.children].forEach(item => {
            let draggable = true
            if (excludedElements) {
                for (let i in excludedElements) {
                    if (excludedElements.hasOwnProperty(i) && item.matches(excludedElements[i])) {
                        draggable = false
                        break;
                    }
                }
            }
            item.draggable = draggable
            _unDraggable(item.children)
        })
        this.removeEventListener('dragstart', _onDragStart, false)
        this.addEventListener('dragstart', _onDragStart, false)
    }
})()