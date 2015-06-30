# A small config package in PHP

[![Build Status](https://api.travis-ci.org/magnus-eriksson/config.svg)](https://travis-ci.org/magnus-eriksson/config)


Load config files and set or get values using dot notation for nested arrays.

## Install

Clone this repository or use composer to download the library with the following command:
```
composer require maer/config 1.*
```

## Usage

### Config files structure
The only requirements for your config files is that they must return an array. Example:

```
<?php

return [
    'name'    => 'Chuck Norris',
    'skill'   => 'Everything',
    'movies'  => [
        'genres' => [
            'action
        ],
        'titles' => [
            'Missing in Action',
            'The Delta Force'
        ]
    ],
];
```


### Instances
Since all loaded config files are stored within the Config instance used to load the files, you need to use the same Config instance throughtout your application.

There are two ways of getting an instance of the Config class:

1. [Create a new instance](#create-a-new-instance)
2. [Use the Factory](#use-the-factory)


### Create a new instance
If you for example have your own factories or are using dependency injection, you might want to create the instance yourself. Here's how

```
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

# Nested arrays uses the dot notation for simplicity
$genres = $config->get('movies.genres'));
# Returnes: ['action']

# Check if a key is set/exists}
if ($config->exists('name')) {
    // Do stuff
}

# Check if a config file is loaded
if ($config->isLoaded('path-to-config-file')) {
    // Do stuff
}

```

### Use the Factory
If you just want to get an instance quickly and can't be bothered creating your own factory or using dependency injection, you can use the factory that's included in the pachage. It will always return the same instance:

```
<?php
# Use composers autoloader
require __DIR__ . '/vendor/autoload.php';

$config = Maer\Config\Factory::getInstance();

# ...after that, it's all the same as before
```

## Note
In case there are conflicts with the names/keys between different config files, the values from the last loaded file will be used.

If you have any questions, suggestions or issues, let me know!

Happy coding!


