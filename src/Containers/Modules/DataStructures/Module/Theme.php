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

namespace O2System\Framework\Containers\Modules\DataStructures\Module;

// ------------------------------------------------------------------------

use O2System\Spl\DataStructures\SplArrayObject;
use O2System\Spl\Info\SplDirectoryInfo;
use O2System\Spl\Info\SplFileInfo;

/**
 * Class Theme
 *
 * @package O2System\Framework\Containers\Modules\DataStructures\Module
 */
class Theme extends SplDirectoryInfo
{
    /**
     * Theme Properties
     *
     * @var array
     */
    private $properties = [];

    /**
     * Theme Presets
     *
     * @var array
     */
    private $presets = [];

    /**
     * Theme Layout
     *
     * @var Theme\Layout
     */
    private $layout;

    /**
     * Theme::__construct
     *
     * @param string $dir
     */
    public function __construct($dir)
    {
        parent::__construct($dir);

        // Set Theme Properties
        if (is_file($propFilePath = $dir . 'theme.json')) {
            $properties = json_decode(file_get_contents($propFilePath), true);

            if (json_last_error() === JSON_ERROR_NONE) {
                if (isset($properties[ 'config' ])) {
                    $this->presets = $properties[ 'presets' ];
                    unset($properties[ 'presets' ]);
                }

                $this->properties = $properties;
            }
        }

        // Set Default Theme Layout
        $this->setLayout('theme');
    }

    // ------------------------------------------------------------------------

    /**
     * Theme::isValid
     *
     * @return bool
     */
    public function isValid()
    {
        if (count($this->properties)) {
            return true;
        }

        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Theme::getParameter
     *
     * @return string
     */
    public function getParameter()
    {
        return $this->getDirName();
    }

    // ------------------------------------------------------------------------

    /**
     * Theme::getCode
     *
     * @return string
     */
    public function getCode()
    {
        return strtoupper(substr(md5($this->getDirName()), 2, 7));
    }

    // ------------------------------------------------------------------------

    /**
     * Theme::getChecksum
     *
     * @return string
     */
    public function getChecksum()
    {
        return md5($this->getMTime());
    }

    // ------------------------------------------------------------------------

    /**
     * Theme::getProperties
     *
     * @return \O2System\Spl\DataStructures\SplArrayObject
     */
    public function getProperties()
    {
        return new SplArrayObject($this->properties);
    }

    // ------------------------------------------------------------------------

    /**
     * Theme::getPresets
     *
     * @return \O2System\Spl\DataStructures\SplArrayObject
     */
    public function getPresets()
    {
        return new SplArrayObject($this->presets);
    }

    // ------------------------------------------------------------------------

    /**
     * Theme::getUrl
     *
     * @param string|null $path
     *
     * @return string
     */
    public function getUrl($path = null)
    {
        return path_to_url($this->getRealPath() . $path);
    }

    // ------------------------------------------------------------------------

    /**
     * Theme::load
     *
     * @return static
     */
    public function load()
    {
        if ($this->getPresets()->offsetExists('assets')) {
            presenter()->assets->autoload($this->getPresets()->offsetGet('assets'));
        }

        presenter()->assets->loadCss('theme.css');
        presenter()->assets->loadJs('theme.js');

        // Autoload default theme layout
        $this->loadLayout();

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Theme::setLayout
     *
     * @param string $layout
     *
     * @return static
     */
    public function setLayout($layout)
    {
        $extensions = ['.php', '.phtml', '.html', '.tpl'];

        if (isset($this->presets[ 'extensions' ])) {
            array_unshift($partialsExtensions, $this->presets[ 'extension' ]);
        } elseif (isset($this->presets[ 'extension' ])) {
            array_unshift($extensions, $this->presets[ 'extension' ]);
        }

        foreach ($extensions as $extension) {
            $extension = trim($extension, '.');

            if ($layout === 'theme') {
                $layoutFilePath = $this->getRealPath() . 'theme.' . $extension;
            } else {
                $layoutFilePath = $this->getRealPath() . 'layouts' . DIRECTORY_SEPARATOR . dash($layout) . DIRECTORY_SEPARATOR . 'layout.' . $extension;
            }

            if (is_file($layoutFilePath)) {
                $this->layout = new Theme\Layout($layoutFilePath);
                $this->loadLayout();
                break;
            }
        }

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Theme::getLayout
     *
     * @return Theme\Layout
     */
    public function getLayout()
    {
        return $this->layout;
    }

    // ------------------------------------------------------------------------

    /**
     * Theme::loadLayout
     *
     * @return static
     */
    protected function loadLayout()
    {
        if ($this->layout instanceof Theme\Layout) {
            // add theme layout public directory
            loader()->addPublicDir($this->layout->getPath() . 'assets');

            presenter()->assets->autoload(
                [
                    'css' => ['layout'],
                    'js'  => ['layout'],
                ]
            );

            $partials = $this->layout->getPartials()->getArrayCopy();

            foreach ($partials as $offset => $partial) {
                if ($partial instanceof SplFileInfo) {
                    presenter()->partials->addPartial($offset, $partial->getPathName());
                }
            }
        }

        return $this;
    }
}