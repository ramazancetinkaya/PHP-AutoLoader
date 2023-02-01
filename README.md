# Auto loader class

**Note**: In the example above, the **register** method is used to add this autoloader to the SPL autoloader stack, so that it can be used to load classes automatically. The **addNamespace** method is used to map a namespace prefix to a directory in the file system, and the **loadClass** method is used to load the class file for a given fully qualified class name. The **loadMappedFile** and **requireFile** methods are used to load the mapped file for a namespace prefix and relative class, and to require the file from the file system, respectively.

## You can use the AutoLoader class by instantiating an object of this class and registering it with the SPL autoloader stack. 
### Here's an example:

```php
require_once 'path/to/AutoLoader.php';

$loader = new AutoLoader();

// Register the autoloader
$loader->register();

// Add namespace prefixes to base directories
$loader->addNamespace('MyApp', 'path/to/MyApp');
$loader->addNamespace('ThirdParty', 'path/to/ThirdParty');

// Use the classes
use MyApp\Foo;
use ThirdParty\Bar;

$foo = new Foo();
$bar = new Bar();
```

In the example above, we require the **AutoLoader** class file, instantiate an object of the class, and then register the autoloader with the SPL autoloader stack using the **register** method. Next, we use the **addNamespace** method to map the **MyApp** and **ThirdParty** namespace prefixes to the corresponding base directories in the file system. Finally, we use the classes in our code, such as **MyApp\Foo** and **ThirdParty\Bar**. When a new class is instantiated, the SPL autoloader will automatically use the **AutoLoader** class to try and load the class file.

### Authors

**Ramazan Çetinkaya**

- [github/ramazancetinkaya](https://github.com/ramazancetinkaya)

### License

Copyright © 2023, [Ramazan Çetinkaya](https://github.com/ramazancetinkaya).
