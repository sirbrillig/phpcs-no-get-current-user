# NoGetCurrentUser

A [phpcs](https://github.com/squizlabs/PHP_CodeSniffer) sniff to disallow using `get_current_user()`.

Particularly when using a PHP framework like WordPress, it's very unlikely that most code will want to use the PHP function `get_current_user()`. It can also easily be confused with other framework functions like WordPress's `wp_get_current_user()`. This sniff makes using the function an error, although legitimate uses can still be made if the sniff is disabled for specific lines.

## Installation

To use these rules in a project which is set up using [composer](https://href.li/?https://getcomposer.org/), we recommend using the [phpcodesniffer-composer-installer library](https://href.li/?https://github.com/DealerDirect/phpcodesniffer-composer-installer) which will automatically use all installed standards in the current project with the composer type `phpcodesniffer-standard` when you run phpcs.

```
composer require --dev squizlabs/php_codesniffer dealerdirect/phpcodesniffer-composer-installer
composer require --dev sirbrillig/phpcs-no-get-current-user
```

## Configuration

When installing sniff standards in a project, you edit a `phpcs.xml` file with the `rule` tag inside the `ruleset` tag. The `ref` attribute of that tag should specify a standard, category, sniff, or error code to enable. It’s also possible to use these tags to disable or modify certain rules. The [official annotated file](https://href.li/?https://github.com/squizlabs/PHP_CodeSniffer/wiki/Annotated-ruleset.xml) explains how to do this.

```xml
<?xml version="1.0"?>
<ruleset name="MyStandard">
 <description>My library.</description>
 <rule ref="NoGetCurrentUser"/>
</ruleset>
```

## Sniff Codes

There is one sniff code that is reported by this sniff.

- `NoGetCurrentUser.Disallowed.NoGetCurrentUser.Found`

In any given file, you can use phpcs comments to disable these sniffs. For example:

```php
$php_user = get_current_user(); // phpcs:ignore NoGetCurrentUser.Disallowed.NoGetCurrentUser.Found
```

For a whole file, you can ignore a sniff like this:

```php
<?php
// phpcs:disable NoGetCurrentUser.Disallowed.NoGetCurrentUser.Found
```

## Usage

Most editors have a phpcs plugin available, but you can also run phpcs manually. To run phpcs on a file in your project, just use the command-line as follows (the `-s` causes the sniff code to be shown, which is very important for learning about an error).

```
vendor/bin/phpcs -s src/MyProject/MyClass.php
```

## See Also

- [VariableAnalysis](https://github.com/sirbrillig/phpcs-variable-analysis): Find undefined and unused variables.
- [ImportDetection](https://github.com/sirbrillig/phpcs-import-detection): A set of phpcs sniffs to look for unused or unimported symbols.
