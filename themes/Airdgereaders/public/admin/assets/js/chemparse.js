function parseFormular(formular) {
    // `HOCH2CH2`; // Cu(NO3)2 + H2O - H12"
    var singleton = formular.match(/(?:[A-Z][a-z]*|\d+|[()])/g); //example (8) ['H', 'O', 'C', 'H', '2', 'C', 'H', '2']
    let composition = formular.match(/([A-Z][a-z]*)(\d*)/g); // example (6) ['H', 'O', 'C', 'H2', 'C', 'H2']
    console.log(singleton)
    console.log(composition)
    return composition
}


function parseFormularC(formular) {
   const regex = /([A-Z][a-z]*)(\d*)/g;
   console.log(formular.match(regex))
   
}
