<?php
// Super simple test to verify file inclusion
Route::get('/super-simple-test', function() {
    return 'This route file IS being loaded!';
})->name('super.simple.test');
