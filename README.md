# Font Awesome SVG Twig Bundle for Symfony

This bundle enables the support of [FontAwesome](https://fontawesome.com/) SVG icons as inline output within twig templates.


## Installation

To install this package, you can just use composer:

```
composer require xenbyte/font-awesome-svg-twig-bundle
```

If you don't use Symfony Flex, register the bundle manually:

```php
// config/bundles.php
return [
    // ...
    Xenbyte\FontAwesomeSvgTwigBundle\FontAwesomeSvgTwigBundle::class => ['all' => true],
];
```

### Set up Font Awesome

#### Option 1: Using the [fortawesome/font-awesome](https://packagist.org/packages/fortawesome/font-awesome) package
```
composer require fortawesome/font-awesome
```

The composer package contains only the free icons.

### Option 2: Provide the font files manually
Copy the files within `node_modules/@fortawesome/fontawesome-pro/svgs` to `assets/fontawesome`.

## Features
- accessability: adds `aria-hidden="true" role="img"` or a title item with `aria-labeledby` as [recommended by Font Awesome](https://fontawesome.com/docs/web/dig-deeper/accessibility)
- no Font Awesome's css and javascript files necessarry (just css for icon size in your own stylesheet

## Usage examples
```twig
{{ fa("home") }}
{{ fa("fas home") }}
{{ fa("home", {style: solid) }}
{{ fa("home", {style: 'regular', color: '#330000', size: '2rem', class: 'icon') }}
{{ fa("home", {style: 'duotone', color: '#333', secondaryColor: '#999', 'title': 'Title') }}
```

Default style is "solid". `{{ fa("home") }}` and `{{ fa("home", {style: solid) }}` will produce the same output.

As an alternative for adding the styles in the options, you can also add a short prefix for some styles, e.g.
`{{ fa("fat home") }}` for `{{ fa("home", {style: thin) }}`.

The following prefixes are supported;:

* fas = solid
* far = regular
* fad = duotone
* fat = thin
* fal = light
* fab = brands

It is recommended to add some default css. All icons gets the class `fa-icon`:
```css
.fa-icon {
  display: inline-block;
  height: 1em;
  overflow: visible;
  vertical-align: -0.125em;
}
```

## Limitation
[Stacking items](https://fontawesome.com/docs/web/style/stack) is currently not possible with this extension.
