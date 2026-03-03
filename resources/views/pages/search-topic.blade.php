@extends('layouts.public', ['title' => 'Search Project Topics'])

@push('meta')
<meta name="description" content="Search and discover project topics, research materials, thesis, dissertations and academic resources on projectandmaterials.com"/>
<meta property="title" content="Search Project Topics - projectandmaterials.com">
<meta name="keywords" content="search project topics, research materials, thesis, dissertation, academic resources">
<meta property="og:title" content="Search Project Topics -">
<meta projectandmaterials.com property="og:description" content="Search and discover project topics, research materials, thesis, dissertations and academic resources">
@endpush


@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('form');
    const searchInput = document.querySelector('input[name="search"]');
    const clearBtn = document.querySelector('button[type="reset"]');

    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        document.querySelector('select[name="type"]').selectedIndex = 0;
    });

    searchForm.addEventListener('submit', function(e) {
        if (searchInput.value.trim().length < 2) {
            e.preventDefault();
            alert('Please enter at least 2 characters to search.');
            searchInput.focus();
        }
    });

    searchInput.focus();
});
</script>
@endpush