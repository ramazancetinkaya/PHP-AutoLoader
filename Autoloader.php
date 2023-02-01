<?php

/**
 * AutoLoader Class
 *
 * @author Ramazan Çetinkaya
 * @date 2023-02-01
 */
namespace MyApp\Autoloader;

/**
 * A simple autoloader class that implements the PSR-4 standard.
 */
class Autoloader
{
    /**
     * An associative array where the keys are namespace prefixes and the values
     * are directories.
     *
     * @var string[]
     */
    private array $prefixes = [];

    /**
     * Registers this autoloader to the SPL autoloader stack.
     */
    public function register(): void
    {
        spl_autoload_register([$this, 'loadClass'], true, true);
    }

    /**
     * Unregisters this autoloader from the SPL autoloader stack.
     */
    public function unregister(): void
    {
        spl_autoload_unregister([$this, 'loadClass']);
    }

    /**
     * Adds a namespace prefix and its corresponding directory to this autoloader.
     *
     * @param string $prefix The namespace prefix.
     * @param string $baseDirectory The directory corresponding to the namespace prefix.
     * @param bool $prepend If true, prepend the directory to the stack instead of appending it.
     */
    public function addNamespace(string $prefix, string $baseDirectory, bool $prepend = false): void
    {
        // Normalize namespace prefix
        $prefix = trim($prefix, '\\') . '\\';

        // Normalize the base directory with a trailing separator
        $baseDirectory = rtrim($baseDirectory, '/') . '/';
        $baseDirectory = rtrim($baseDirectory, '\\') . '\\';

        // Initialize the namespace prefix array
        if (!isset($this->prefixes[$prefix])) {
            $this->prefixes[$prefix] = [];
        }

        // Retain the base directory for the namespace prefix
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $baseDirectory);
        } else {
            array_push($this->prefixes[$prefix], $baseDirectory);
        }
    }

    /**
     * Loads the class file associated with a given fully qualified class name.
     *
     * @param string $class The fully qualified class name.
     * @return bool True if the class was successfully loaded, false otherwise.
     */
    public function loadClass(string $class): bool
    {
        // The current namespace prefix
        $prefix = $class;

        // Work backwards through the namespace names of the fully qualified class name to find a mapped file name
        while (false !== $pos = strrpos($prefix, '\\')) {
            // Retain the trailing namespace separator in the prefix
            $prefix = substr($class, 0, $pos + 1);

            // The rest is the relative class name
            $relativeClass = substr($class, $pos + 1);

            // Try to load a mapped file for the prefix and relative class
            $mappedFile = $this->loadMappedFile($prefix, $relativeClass);
            if ($mappedFile) {
                return true;
            }

            // Remove the trailing namespace separator for the next iteration
            $prefix = rtrim($prefix, '\\');
        }

        // Not able to load the class
        return false;
    }

    /**
     * Loads the mapped file for a namespace prefix and relative class.
     *
     * @param string $prefix The namespace prefix.
     * @param string $relativeClass The relative class name.
     * @return bool True if the mapped file was successfully loaded, false otherwise.
     */
    private function loadMappedFile(string $prefix, string $relativeClass): bool
    {
        // Are there any base directories for this namespace prefix?
        if (!isset($this->prefixes[$prefix])) {
            return false;
        }

        // Look through base directories for this namespace prefix
        foreach ($this->prefixes[$prefix] as $baseDirectory) {
            // Replace the namespace prefix with the base directory,
            // replace namespace separators with directory separators
            // in the relative class name, append with .php
            $file = $baseDirectory
                . str_replace('\\', '/', $relativeClass)
                . '.php';

            // If the mapped file exists, require it
            if ($this->requireFile($file)) {
                return true;
            }
        }

        // Not able to load the mapped file
        return false;
    }

    /**
     * If a file exists, requires it from the file system.
     *
     * @param string $file The file to require.
     * @return bool True if the file exists, false otherwise.
     */
    private function requireFile(string $file): bool
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}
