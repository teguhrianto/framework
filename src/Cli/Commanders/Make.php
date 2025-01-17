<?php
/**
 * This file is part of the O2System Framework package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author         Steeve Andrian Salim
 * @copyright      Copyright (c) Steeve Andrian Salim
 */

// ------------------------------------------------------------------------

namespace O2System\Framework\Cli\Commanders;

// ------------------------------------------------------------------------

use O2System\Framework\Cli\Commander;

/**
 * Class Make
 *
 * @package O2System\Framework\Cli\Commanders
 */
class Make extends Commander
{
    /**
     * Make::$commandVersion
     *
     * Command version.
     *
     * @var string
     */
    protected $commandVersion = '1.0.0';

    /**
     * Make::$commandDescription
     *
     * Command description.
     *
     * @var string
     */
    protected $commandDescription = 'CLI_MAKE_DESC';

    /**
     * Make::$commandOptions
     *
     * Command options.
     *
     * @var array
     */
    protected $commandOptions = [
        'name'      => [
            'description' => 'CLI_MAKE_NAME_DESC',
            'required'    => true,
        ],
        'path'      => [
            'description' => 'CLI_MAKE_PATH_DESC',
            'required'    => false,
        ],
        'filename'  => [
            'description' => 'CLI_MAKE_FILENAME_DESC',
            'required'    => true,
        ],
        'namespace' => [
            'description' => 'CLI_MAKE_NAMESPACE_DESC',
            'shortcut'    => 'ns',
            'required'    => false,
        ],
    ];

    /**
     * Make::$path
     *
     * Make path.
     *
     * @var string
     */
    protected $optionPath;

    /**
     * Make::$filename
     *
     * Make filename.
     *
     * @var string
     */
    protected $optionFilename;

    // ------------------------------------------------------------------------

    /**
     * Make::optionPath
     *
     * @param string $path
     *
     * @return static
     */
    public function optionPath($path)
    {
        $path = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
        $path = PATH_ROOT . str_replace(PATH_ROOT, '', $path);

        if (pathinfo($path, PATHINFO_EXTENSION)) {
            $this->optionFilename(pathinfo($path, PATHINFO_FILENAME));
            $path = dirname($path);
        }

        $this->optionPath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Make::optionFilename
     *
     * @param string $name
     */
    public function optionFilename($name)
    {
        $name = str_replace('.php', '', $name);
        $this->optionFilename = prepare_filename($name) . '.php';

        $this->optionPath = empty($this->optionPath) ? modules()->top()->getRealPath() : $this->optionPath;
    }

    // ------------------------------------------------------------------------

    /**
     * Make::optionName
     *
     * @param string $name
     */
    public function optionName($name)
    {
        $this->optionFilename($name);
    }

    // ------------------------------------------------------------------------

    /**
     * Make::optionNamespace
     *
     * @param string $namespace
     */
    public function optionNamespace($namespace)
    {
        $this->namespace = $namespace;
    }
}