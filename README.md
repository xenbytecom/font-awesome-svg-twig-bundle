# Font Awesome SVG Twig Bundle for Symfony

This bundle enables the support of [FontAwesome](https://fontawesome.com/) SVG icons as inline output within twig templates.

![Packagist Version](https://img.shields.io/packagist/v/xenbyte/font-awesome-svg-twig-bundle)
[![Donate](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://www.paypal.com/donate/?hosted_button_id=J425R728CYH9N)

## Features
- accessability: adds `aria-hidden="true" role="img"` or a title item with `aria-labeledby` as [recommended by Font Awesome](https://fontawesome.com/docs/web/dig-deeper/accessibility)
- supports Font Awesome composer package (contains only free icons) and manually provided icons (including pro icons)   
- no Font Awesome's css and javascript files necessarry (just css for icon size in your own stylesheet)

## Installation

To install this package, you can just use composer. Open a command console, enter your project directory and execute:

```
composer require xenbyte/font-awesome-svg-twig-bundle
```

If you don't use Symfony Flex, enable the bundle by adding it to the list of registered bundles 
in the `config/bundles.php` file of your project:

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

#### Option 2: Provide the font files manually
Copy the files within `node_modules/@fortawesome/fontawesome-pro/svgs` to e. g. `assets/fontawesome`.

## Configuration
If you need to customize the global bundle configuration, you can create a /config/packages/font_awesome_svg_twig.yaml 
file with your configuration:

```yaml
font_awesome_svg_twig:
  icon_folder: assets/fontawesome
  svg_class: fa-icon
```
 
## Usage examples
```twig
{{ fa("home") }}
{{ fa("fas home") }}
{{ fa("home", {style: 'solid') }}
{{ fa("home", {style: 'regular', color: '#330000', size: '2rem', class: 'icon') }}
{{ fa("home", {style: 'duotone', color: '#333', secondaryColor: '#999', 'title': 'Title', data-foo) }}
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

## Options
* `resource_folder`: Folder with the font awesome icons
* `svg_class`: Class which is added to the svg element

## Limitation
[Stacking items](https://fontawesome.com/docs/web/style/stack) is currently not possible with this extension.
