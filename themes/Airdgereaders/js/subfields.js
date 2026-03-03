// Sub-fields tag management
$(document).ready(function() {
    const subfieldsContainer = $('#subfieldscontainer');
    const subfieldsInput = subfieldsContainer.find('input');
    const subfieldsHiddenInput = $('#subfields');
    const subfieldslist = $('#subfieldslist');
    
    let selectedSubfields = [];
    
    // Initialize existing subfields from old input value
    if (subfieldsHiddenInput.val()) {
        selectedSubfields = subfieldsHiddenInput.val().split(',').map(s => s.trim()).filter(s => s);
        selectedSubfields.forEach(subfield => {
            addSubfieldTag(subfield);
        });
    }
    
    // Handle input for adding new subfields
    subfieldsInput.on('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            const value = $(this).val().trim();
            if (value) {
                addSubfieldToList(value);
                $(this).val('');
            }
        }
    });
    
    // Handle removing subfield tags
    subfieldsContainer.on('click', '.remove-tag', function(e) {
        e.preventDefault();
        const tagText = $(this).siblings('.tag-text').text();
        removeSubfieldFromList(tagText);
        $(this).parent().remove();
        updateSubfieldsHiddenInput();
    });
    
    function addSubfieldToList(subfield) {
        if (!selectedSubfields.includes(subfield)) {
            selectedSubfields.push(subfield);
            addSubfieldTag(subfield);
            updateSubfieldsHiddenInput();
        }
    }
    
    function removeSubfieldFromList(subfield) {
        const index = selectedSubfields.indexOf(subfield);
        if (index > -1) {
            selectedSubfields.splice(index, 1);
        }
    }
    
    function addSubfieldTag(subfield) {
        const tagHtml = `
            <span class="tag subfield-tag">
                <span class="tag-text">${subfield}</span>
                <a href="#" class="remove-tag" title="Remove subfield">
                    <i class="fa fa-times"></i>
                </a>
            </span>
        `;
        subfieldsContainer.prepend(tagHtml);
    }
    
    function updateSubfieldsHiddenInput() {
        subfieldsHiddenInput.val(selectedSubfields.join(', '));
    }
    
    // Handle datalist selection
    subfieldsInput.on('change', function() {
        const value = $(this).val().trim();
        if (value) {
            addSubfieldToList(value);
            $(this).val('');
        }
    });
});