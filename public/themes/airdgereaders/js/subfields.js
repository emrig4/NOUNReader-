const subfieldsContainer = document.getElementById('subfieldscontainer');
const subfieldInput = document.querySelector('#subfieldscontainer input');
const subfields = document.getElementById('subfields');
let selectedSubfields = [];

function createSubfield(label) {
    const div = document.createElement('div');
    div.setAttribute('class', 'tag subfield');
    const span = document.createElement('span');
    span.innerHTML = label;
    const closeIcon = document.createElement('i');
    closeIcon.setAttribute('class', 'fa fa-close text-xs');
    closeIcon.setAttribute('data-item', label);
    div.appendChild(span);
    div.appendChild(closeIcon);
    return div;
}

function clearSubfield() {
    document.querySelectorAll('.subfield').forEach(subfield => {
        subfield.parentElement.removeChild(subfield);
    });
}

function existingSubfield() {
    document.querySelectorAll('.subfield').forEach(subfield => {
        let subfieldValue = subfield.firstChild.nextSibling.innerHTML
        // if subfields are existing from db, push to local array
        if ( !(selectedSubfields.includes(subfieldValue)) && subfieldValue.length > 1 ){
            selectedSubfields.push(subfieldValue);
        }
    });
}

function addSubfield() {
    existingSubfield();
    clearSubfield();
    selectedSubfields.slice().reverse().forEach(subfield => {
        subfieldsContainer.prepend(createSubfield(subfield));
    });
}
// input.addEventListener('keyup', (e) => {
//     if (e.key === 'Enter') {
//       e.target.value.split(',').forEach(subfield => {
//         subfields.push(subfield);  
//       });
//       addSubfield();
//       input.value = '';
//     }
// });
subfieldInput.addEventListener('change', (e) => {
    e.target.value.split(',').forEach(subfield => {
        if (selectedSubfields.includes(subfield)) return;
        selectedSubfields.push(subfield);
        // add current subfields array to coauthors input field
        subfields.setAttribute('value', selectedSubfields)
    });
    addSubfield();
    subfieldInput.value = '';
});
document.addEventListener('click', (e) => {
    // console.log(e.target.tagName);
    if (e.target.tagName === 'I') {
        const tagLabel = e.target.getAttribute('data-item');
        const index = selectedSubfields.indexOf(tagLabel);
        selectedSubfields = [...selectedSubfields.slice(0, index), ...selectedSubfields.slice(index + 1)];
        addSubfield();
        // update coauthors hidded input field with subfields array values
        subfields.setAttribute('value', selectedSubfields)
    }
})
// input.focus();
