document.addEventListener('DOMContentLoaded', () => {
    const hate = document.getElementsByClassName('hateList');

    for (let i = 0; i<hate.length; i++) {
        hate[i].addEventListener("click", (event)=>{
            event.preventDefault();
            hate[i].classList.toggle('active');
            const link = hate[i].dataset.href;
            fetch(link)
                .then(function (res) {
                    return res.text()
                })
                .then(function (json) {
                    const response = JSON.parse(json);
                })
        })
    }
})
