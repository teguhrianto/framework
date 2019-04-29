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

namespace O2System\Framework\Http\Controllers;

// ------------------------------------------------------------------------

use O2System\Filesystem\Handlers\Downloader;
use O2System\Framework\Http\Controller;
use O2System\Spl\Info\SplFileInfo;

/**
 * Class Resources
 *
 * @package O2System\Framework\Http\Controllers
 */
class Resources extends Controller
{
    /**
     * Resources::$directoryPath
     *
     * @var string
     */
    public $directoryPath;

    /**
     * Resources::$speedLimit
     *
     * @var int
     */
    public $speedLimit = 1024;

    /**
     * Resources::$resumeable
     *
     * @var bool
     */
    public $resumeable = true;

    // ------------------------------------------------------------------------

    /**
     * Resources::__construct
     */
    public function __construct()
    {
        $this->directoryPath = PATH_RESOURCES;
    }

    // ------------------------------------------------------------------------

    /**
     * Resources::route
     */
    public function route()
    {
        $segments = server_request()->getUri()->getSegments()->getParts();
        array_shift($segments);

        $download = false;
        if (false !== ($key = array_search('download', $segments))) {
            $download = true;
            unset($segments[ $key ]);
            $segments = array_values($segments);
        }

        if (count($segments)) {
            $filePath = $this->directoryPath . implode(DIRECTORY_SEPARATOR, $segments);
            if (is_file($filePath)) {
                if ($download) {
                    $downloader = new Downloader($filePath);
                    $downloader
                        ->speedLimit($this->speedLimit)
                        ->resumeable($this->resumeable)
                        ->download();
                } else {
                    $fileInfo = new SplFileInfo($filePath);
                    header('Content-Disposition: filename=' . $fileInfo->getFilename());
                    header('Content-Transfer-Encoding: binary');
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
                    header('Content-Type: ' . $fileInfo->getMime());
                    echo @readfile($filePath);
                    exit(EXIT_SUCCESS);
                }
            } else {
                redirect_url('error/404');
            }
        } else {
            redirect_url('error/403');
        }
    }
}
