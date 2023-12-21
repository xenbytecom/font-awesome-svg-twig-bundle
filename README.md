# Font Awesome SVG Twig Bundle for Symfony

This bundle enables the support of [FontAwesome](https://fontawesome.com/) SVG icons as inline output within twig templates. 


## Installation

To install this package, you can just use composer:

```
$ composer require xenbyte/font-awesome-svg-twig-bundle
```

If you don't use Symfony Flex, register the bundle manually:

// in config/bundles.php
```
return [
// ...
Xenbyte\FontAwesomeSvgTwigBundle\FontAwesomeSvgTwigBundle::class => ['all' => true],
];
```

This will also require the [fortawesome/font-awesome](https://packagist.org/packages/fortawesome/font-awesome) svg files.
Copy the files within `node_modules/@fortawesome/fontawesome-pro/svgs` to `assets/fontawesome`.

Or `composer require fortawesome/font-awesome`, but this package contains only the free icons.

## Features
- ...
- no css, javascript for font files necessarry (just css for icon size in your own stylesheet)
- Accessability (adds `aria-hidden="true" role="img"` to the svg by default)

## Limitation
- no support for stacks nor animations
- no support 
 
## Usage examples
```
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

It is recommended, to add some default CSS.
All icons in html output, will have got the class `fa-icon` set:
```
.fa-icon {
  display: inline-block;
  height: 1em;
  overflow: visible;
  vertical-align: -0.125em;
}
```
When you provide options, like size or color, inline styles will overwrite the default CSS.


## Support

If you like this Symfony bundle, you are invited to [donate some funds](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2DCCULSKFRZFU)
to support further development. Thank you!

For help please visit the issue section on Github.
