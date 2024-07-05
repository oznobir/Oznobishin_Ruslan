document.addEventListener('DOMContentLoaded', () => {
    let messageWrap = document.querySelector('.message__wrap')
    if (messageWrap) {
        let styles = {
            position: 'fixed',
            top: '8%',
            left: '50%',
            transform: 'translateX(-50%)',
            display: 'block',
            zIndex: '110',
        }
        let successStyles = {
            backgroundColor: '#6fbf36',
            color: 'white',
            marginBottom: '10px',
            padding: '25px 30px',
            borderRadius: '20px'
        }
        let errorStyles = {
            backgroundColor: '#ff7f27',
            color: 'white',
            marginBottom: '10px',
            padding: '25px 30px',
            borderRadius: '20px'
        }
        if (messageWrap.innerHTML.trim()) {
            for (let i in styles) {
                messageWrap.style[i] = styles[i]
            }
            if (!messageWrap.children.length) {
                let classDiv = /error/i.test(messageWrap.innerHTML) ? 'error' : 'success'
                messageWrap.innerHTML = `<div class="${classDiv}">${messageWrap.innerHTML}</div>`
            }
            for (let i in messageWrap.children) {
                if (messageWrap.children.hasOwnProperty(i)) {
                    let typeStyles = /error/i.test(messageWrap.children[i].classList.value) ? errorStyles : successStyles
                    for (let j in typeStyles) {
                        messageWrap.children[i].style[j] = typeStyles[j]
                    }
                }
            }
        }

        function hideMessage() {
            messageWrap.remove();
            ['click', 'scroll'].forEach(event => document.removeEventListener(event, hideMessage))
        }

        ['click', 'scroll'].forEach(event => document.addEventListener(event, hideMessage))
    }
})