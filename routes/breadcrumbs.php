<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('pages.home'));
});

// Home > Resources
Breadcrumbs::for('resources', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Resources', route('resources.index'));
});


// single resource :: Home > Resources > [resource] 
Breadcrumbs::for('resource', function (BreadcrumbTrail $trail, $resource) {
    $trail->parent('resources');
    $trail->push(mb_strimwidth($resource->title, 0, 40, ' ...'), route('resources.show', $resource));
});


// Home > Resources > Fields
Breadcrumbs::for('fields', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Resources', route('resource.index'));
    $trail->push('Fields', route('resources.fields'));
});

// Home > Resources > [field]
Breadcrumbs::for('field', function (BreadcrumbTrail $trail, $field) {
    $trail->parent('resources');
    $trail->push($field->title, route('resources.fields.single', $field->slug));
});
