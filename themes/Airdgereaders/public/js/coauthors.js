const coauthorContainer = document.getElementById('coauthorscontainer');
const coauthorsInput = document.querySelector('#coauthorscontainer input');
const coauthors = document.getElementById('coauthors');
let selectedCoauthors = [];

function createAuthor(label) {
    const div = document.createElement('div');
    div.setAttribute('class', 'tag coauthor');
    const span = document.createElement('span');
    span.innerHTML = label;
    const closeIcon = document.createElement('i');
    closeIcon.setAttribute('class', 'fa fa-close text-xs');
    closeIcon.setAttribute('data-item', label);
    div.appendChild(span);
    div.appendChild(closeIcon);
    return div;
}

function clearAuthors() {
    document.querySelectorAll('.coauthor').forEach(coauthor => {
        coauthor.parentElement.removeChild(coauthor);
    });
}


function existingCoauthors() {
    document.querySelectorAll('.coauthor').forEach(coauthor => {
        let coauthorValue = coauthor.firstChild.nextSibling.innerHTML
        // if subfields are existing from db, push to local array
        if ( !(selectedCoauthors.includes(coauthorValue)) && coauthorValue.length > 1 ){
            selectedCoauthors.push(coauthorValue);
        }
    });
}

function addAuthors() {
    existingCoauthors();
    clearAuthors();
    selectedCoauthors.slice().reverse().forEach(coauthor => {
        coauthorContainer.prepend(createAuthor(coauthor));
    });
}
// input.addEventListener('keyup', (e) => {
//     if (e.key === 'Enter') {
//       e.target.value.split(',').forEach(coauthor => {
//         selectedCoauthors.push(coauthor);  
//       });
//       addAuthors();
//       input.value = '';
//     }
// });

coauthorsInput.addEventListener('change', (e) => {
    e.target.value.split(',').forEach(coauthor => {
        if (selectedCoauthors.includes(coauthor)) return;
        selectedCoauthors.push(coauthor);
        // add current selectedCoauthors array to coauthors input field
        coauthors.setAttribute('value', selectedCoauthors)
    });
    addAuthors();
    coauthorsInput.value = '';
});
document.addEventListener('click', (e) => {
    // console.log(e.target.tagName);
    if (e.target.tagName === 'I') {
        const tagLabel = e.target.getAttribute('data-item');
        const index = selectedCoauthors.indexOf(tagLabel);
        selectedCoauthors = [...selectedCoauthors.slice(0, index), ...selectedCoauthors.slice(index + 1)];
        addAuthors();
        // update coauthors hidded input field with selectedCoauthors array values
        coauthors.setAttribute('value', selectedCoauthors)
    }
})
// coauthorsInput.focus();
