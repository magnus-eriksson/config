# A small config package in PHP

[![Build Status](https://api.travis-ci.org/magnus-eriksson/config.svg)](https://travis-ci.org/magnus-eriksson/config)

Load config files and set or get values using dot notation for nested arrays.

## Install

Clone this repository or use composer to download the latest version:
```bash
$ composer require maer/config
```

## Usage

### Config files structure
Out of the box, the config files can be either a PHP file, returning an array:

```php
<?php

return [
    'name'    => 'Chuck Norris',
    'skill'   => 'Everything',
    'movies'  => [
        'genres' => [
            'action'
        ],
        'titles' => [
            'Missing in Action',
            'The Delta Force'
        ]
    ],
];
```

or a Json-file _(must have the .json extension)_:

```json
{
    "name": "Chuck Norris",
    "skill": "Everything",
    "movies": {
        "genres": [
            "action"
        ],
        "titles": [
            "Missing in Action",
            "The Delta Force"
        ]
    }
}
```

or an ini-file _(must have the .ini extension)_:

```ini
name = "Chuck Norris"
skill = "Everything"

```


### Instances
Since all loaded config files are stored within the Config instance used to load the files, you need to use the same Config instance throughtout your application.

There are two ways of getting an instance of the Config class:

1. [Create a new instance](#create-a-new-instance)
2. [Use the Factory](#use-the-factory)


### Create a new instance
If you for example have your own factories or are using dependency injection, you might want to create the instance yourself. Here's how

```php
<?php
# Use composers autoloader
require __DIR__ . '/vendor/autoload.php';

$config = new Maer\Config\Config;

# Load a config file (you can also send in an array with multiple config files
# or send the array to the constructor upon instantiation.
$config->load('path-to-your-config-file');

$name = $config->get('name', 'this optional string will be returned if the key does not exist');
# Returnes: Chuck Norris

$config->set('name', 'Jackie Chan');
$name = $config->get('name');
# Returnes: Jackie Chan

# If you haven't loaded any file (or if you want to merge with another array),
# you can pass an array as the first parameter. This uses the array_replace_recursive() strategy.
$config->set(['name' => 'Chuck Norris', 'skill' => 'Something new']);

# Use dot notation for multidimensional arrays
$genres = $config->get('movies.genres'));
# Returnes: ['action']

# Push a new item to an existing array
$config->push('movies.genres', 'Some new genre'));
# If the target isn't an array, an UnexpectedValueException will be thrown.

# Check if a key is exists
if ($config->has('name')) {
    // Do stuff
}

# Check if a config file is loaded
if ($config->isLoaded('path-to-config-file')) {
    // Do stuff
}

```

### Use the Factory
If you just want to get an instance quickly and can't be bothered creating your own factory or using dependency injection, you can use the factory that's included in the package. It will always return the same instance:

```php
<?php
# Use composers autoloader
require __DIR__ . '/vendor/autoload.php';

$config = Maer\Config\Factory::getInstance();

# ...after that, it's all the same as before
```

## Readers

As described above, this library supports php-, json-, and ini-files out of the box. If you want to read files in some other format, you can add your own reader.

### Creating a reader
When you create a reader, it must implement the interface `Maer\Config\Readers\ReaderInterface`.

Example:

```php
class JsonReader implements Maer\Config\Readers\ReaderInterface
{
    public function read($file)
    {
        $content = json_decode(file_get_contents($file), true, 512);
        return is_array($content) ? $content : [];
    }
}
```

### Registering a reader

Before you can use your reader, you need to register it and tell the library for which file extension it should be used.

Let's say we've created a reader for yaml and we want to associate it with the `yml`-file extension:

```php
# Either add the reader to an existing config instance
$config->setReader('yml', new MyYamlReader);

# or you can add readers when you instantiate the config class as a second argument
$options = [
    'readers' => [
        'yml' => new MyYamlReader,
    ],
];

$config = new Config(['/path-to-some-config'], $options);
```

If you want to use the same reader for multiple file extensions (let's say we will allow both `yml` and `yaml`
as a file extension), register both like we did above and pass the same reader.

You can of course override the default readers for php, ini and json by registering your own reader for those file extensions.

> **Important:** If you want to use your own reader, you must register it _before_ you're trying to load the config file or at the same time, through the constructor.

## Note
In case there are conflicts with the names/keys between different config files, the values from the last loaded file will be used.

If you have any questions, suggestions or issues, let me know!

Happy coding!

