document.addEventListener('DOMContentLoaded', () => {
    const hate = document.getElementsByClassName('hateList');
    const love = document.getElementsByClassName('favList');

    for (let i = 0; i<hate.length; i++) {
        hate[i].addEventListener("click", (event)=>{
            event.preventDefault();
            hate[i].classList.toggle('active');
            love[i].classList.remove('active');
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

    for (let i = 0; i<love.length; i++) {
        love[i].addEventListener("click", (event)=>{
            event.preventDefault();
            console.log(love[i])
            love[i].classList.toggle('active');
            hate[i].classList.remove('active');
            const link = love[i].dataset.href;
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
