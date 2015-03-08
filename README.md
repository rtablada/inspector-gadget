# Inspector Gadget

[![Latest Version](https://img.shields.io/github/release/rtablada/inspector-gadget.svg?style=flat-square)](https://github.com/rtablada/inspector-gadget/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/rtablada/inspector-gadget/master.svg?style=flat-square)](https://travis-ci.org/rtablada/inspector-gadget)
[![Total Downloads](https://img.shields.io/packagist/dt/rtablada/inspector-gadget.svg?style=flat-square)](https://packagist.org/packages/rtablada/inspector-gadget)

Inspector Gadget is a [web-component](https://css-tricks.com/modular-future-web-components/) and [Ember.js](http://emberjs.com) inspired library for improving data-flow, maintainability, and template reusability.

Gadgets, in inspector gadget allow for template partials or even plain strings to be rendered in a smart, explicit fashion while reducing weight and overloaded controllers or domain layers.

## Install

Via Composer

``` bash
$ composer require rtablada/inspector-gadget
```

In the `app.php` config file, add `'Rtablada\InspectorGadget\GadgetServiceProvider',` to the `providers` array.

Then publish the configuration file with `php artisan vendor:publish` to publish the `config/inspector-gadget.php` file.

**Note**

You can optionally install install the `Rtablada\InspectorGadget\Facades\Gadget'` to the facades array as `Gadget` to use gadget facades in your views.

## Usage

### Gadget Classes

Gadgets are just plain PHP objects with a `render` method.
The string returned by the `render` function will be sent back to your views.
Since the gadgets are resolved using the application container, you can dependency inject like any other class in your application.

```php
class ExampleGadget
{
    public function render()
    {
        return 'this string will be returned';
    }
}
```

### Making Gadgets with the GadgetFactory

In you views, you can render gadgets using the `make` function on the `gadgetFactory` variable that is available in your views.
The `make` function accepts a string argument for the gadget class that you want to render in your view.

```php
$gadgetFactory->make('ExampleGadget'); // returns 'this string will be returned'
```

### Passing Arguments to Gadgets

To allow greater flexibility, you can pass arguments to the `render` function in your gadget.

```php
// app/Gadgets/ArgumentGadget.php
class ArgumentGadget
{
    public function render($str)
    {
        return $str . ' from gadget';
    }
}

// view.php
$gadgetFactory->make('ArgumentGadget', 'test'); // returns 'test from gadget'
```

### Shortcuts

If you have registered the `Gadget` facade, then you can have the following in your views:

```php
Gadget::make('ExampleGadget');
```

If you're using blade templates, there is a `@gadget` helper that calls `$gadgetFactory->make()`

```php
@gadget('ExampleGadget')
```

### Better Data Flow

Consider the following controller action:

``` php
public function show($id)
{
    $post = $this->post->find($id);
    $relevantPosts = $this->suggestionEngine->relevantPosts($post);
    $comments = $this->comment->allForPost($post);

    $user = $this->auth->user();

    $userHistory = $this->historyCache->historyForUser($user);
    // etc.

    return view('post.show', compact('post', 'relevantPosts', 'comments', 'user', 'userHistory', '...'));
}
```

And the accompanying view:

```php
<div class="sidebar">
    <div class="user-history">
        <h4>History</h4>
        <?php foreach ($userHistory->posts as $historyPost) ?>
            <!-- Markup for $historyPost-->
        <?php endforeach ?>
    </div>

    <div class="suggested-articles">
        <h4>Things You Might Like</h4>
        <?php foreach ($relevantPosts as $relevantPost) ?>
            <!-- Markup for $relevantPost-->
        <?php endforeach ?>
    </div>

    <!-- etc. -->
</div>
```

This can be cleaned up using gadgets:

```php
// Controller
public function show($id)
{
    $post = $this->post->find($id);
    $user = $this->auth->user();

    return view('post.show', compact('post', 'user'));
}
```

```php
// View
<div class="sidebar">
    <div class="user-history">
        <h4>History</h4>
        @gadget('UserPostHistory', $user)
    </div>

    <div class="suggested-articles">
        <h4>Things You Might Like</h4>
        @gadget('RelevantPosts', $post)
    </div>

    <!-- etc. -->
</div>
```

## Configuring

### Default Namespace

To shorten the need for full class names in your `Gadget::make` calls, Inspector Gadget has a `namespace` configuration option in the `config/inspector-gadget.php` file.
This is used as a default namespace to look up gadgets.
If a class is not found in your configured default namespace, then Inspector Gadget will attempt to load from the full class name.

### Aliases

To further shorthand and ease, you can register aliases in the `aliases` array in the `config/inspector-gadget.php` file.
This allows for gadgets to be aliased without poluting the app container.

## Testing

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email ryan@embergrep.com instead of using the issue tracker.

## Credits

- [Ryan Tablada](https://github.com/rtablada)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
