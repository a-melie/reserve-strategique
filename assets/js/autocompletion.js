
const searchInput = document.getElementById('q');
searchInput.addEventListener('keyup', event=>{
    console.log(event.target.value)
    let init = {
        method: 'GET'
    };
    fetch('/product/keyword?keyword=' + event.target.value , init)
        .then(response => response.json())
        .then(data=>buildList(data))
})

function buildList(names) {
    let ul = document.getElementById('autocompletion');
    ul.innerHTML = '';
    for (let i = 0; i < names.length; i++) {
        let textName = names[i]
        let newName = document.createElement('li');
        newName.innerHTML = textName;
        ul.append(newName);
        newName.addEventListener('click', event=>{
            searchInput.value = textName;
        })

    }

}


