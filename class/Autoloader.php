<?php

namespace XoopsModules\Tdmdownloads;

/**
 * @author  Andrei Alexandru Romila
 */
class Autoloader
{
    /**
     * PHP default file extension
     *
     * @var string
     */
    const PHP_EXTENSION = '.php';

    /**
     * Stores all namespaces and directories
     *
     * @var array
     */
    protected $prefixes = [];

    /**
     * Stores all the files
     *
     * @var array
     */
    protected $files = [];

    /**
     * @param bool $register
     */
    public function __construct($register = false)
    {
        if (true === $register) {
            $this->registerAutoloader();
        }
    }

    /**
     * Registers a new autoloader funtion
     *
     * @return bool Returns true on success or false on failure.
     */
    public function registerAutoloader()
    {
        return spl_autoload_register([$this, 'loadClass'], false, true);
    }

    /**
     * Register a new file for a classname.
     *
     * @param string $class    The name of the class
     * @param string $filename The filename
     * @param bool   $overwrite
     * @internal param bool $replace If the class is already register, replace the filename
     */
    public function registerFile($class, $filename, $overwrite = false)
    {
        if (false === is_file($filename) || false === is_readable($filename)) {
            echo 'The file: ' . $filename . ' does\'t exist or is not readable!<br>';

            return;
        }

        // Remove the '\' if the class is \ClassName
        $class = rtrim($class, '\\');

        // Get real path of the file and normalize directory separator
        $filename = realpath($filename);
        $filename = str_replace('\\', '/', $filename);

        // If is not set yet, create it
        if (false === isset($this->files[$class])) {
            $this->files[$class] = $filename;
        } elseif (true === $overwrite) {
            // Overwrite the last filename
            $this->files[$class] = $filename;
        }
    }

    /**
     * Registers a new namespace in the loader
     *
     * @param string $namespace The namespace
     * @param string $directory Path to the namespace
     * @param bool   $prepend
     */
    public function registerNamespace($namespace, $directory, $prepend = false)
    {
        if (false === is_dir($directory) || false === is_readable($directory)) {
            echo 'The directory: ' . $directory . ' does\'t exist or is not readable!<br>';

            return;
        }

        $namespace = trim($namespace, '\\') . '\\';
        $directory = realpath($directory);
        $directory = str_replace('\\', '/', $directory) . '/';

        // If doesnt exist create a new array for the namespace.
        if (false === isset($this->prefixes[$namespace])) {
            $this->prefixes[$namespace] = [];
        } elseif (in_array($directory, $this->prefixes[$namespace])) {
            // Already added ...
            return;
        }

        if (true === $prepend) {
            // Prepend this namespace
            $this->prefixes[$namespace] = array_unshift($this->prefixes[$namespace], $directory);
        } else {
            // A bit faster than array_push($array, $value1)
            $this->prefixes[$namespace][] = $directory;
        }
    }

    /**
     * PSR4 loadClass function with some enhancements.
     *
     * @param  string $class
     * @return bool|mixed
     */
    public function loadClass($class)
    {
        // Check for direct file registration - a bit faster than searching in a sub folder
        if (isset($this->files[$class]) && $this->requireFile($this->files[$class])) {
            return $this->files[$class];
        }

        // the current namespace prefix
        $prefix = $class;

        // work backwards through the namespace names of the fully-qualified
        // class name to find a mapped file name
        while (false !== ($position = mb_strrpos($prefix, '\\'))) {
            // retain the trailing namespace separator in the prefix
            $prefix = mb_substr($class, 0, $position + 1);

            // the rest is the relative class name
            $relative = mb_substr($class, $position + 1);

            // try to load a mapped file for the prefix and relative class
            $filename = $this->loadFile($prefix, $relative);
            if ($filename) {
                return $filename;
            }

            // remove the trailing namespace separator for the next iteration
            // of strrpos()
            $prefix = rtrim($prefix, '\\');
        }

        // never found a mapped file
        return false;
    }

    /**
     * Load the mapped file for a namespace prefix and relative class.
     *
     * @param  string $prefix       The namespace prefix.
     * @param  string $relativePath The relative class name.
     * @return mixed  Boolean false if no mapped file can be loaded, or the
     *                              name of the mapped file that was loaded.
     */
    protected function loadFile($prefix, $relativePath)
    {
        // are there any base directories for this namespace prefix?
        if (false === isset($this->prefixes[$prefix])) {
            return false;
        }

        // look through base directories for this namespace prefix
        foreach ($this->prefixes[$prefix] as $directory) {
            $filename = $directory . str_replace('\\', '/', $relativePath) . self::PHP_EXTENSION;

            // if the mapped file exists, require_once it
            if ($this->requireFile($filename)) {
                return $filename;
            }
        }

        // never found it
        return false;
    }

    /**
     * If a file exists, require_once it from the file system.
     *
     * @param $filename
     * @return bool True if the file exists, false if not.
     * @internal param string $file The file to require.
     */
    protected function requireFile($filename)
    {
        if (is_readable($filename)) {
            require_once $filename;

            return true;
        }

        return false;
    }
}
