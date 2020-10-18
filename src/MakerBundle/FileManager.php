<?php

namespace MakerBundle;

use Symfony\Bundle\MakerBundle\FileManager as BaseFileManager;
use Symfony\Bundle\MakerBundle\Util\AutoloaderUtil;
use Symfony\Bundle\MakerBundle\Util\MakerFileLinkFormatter;
use Symfony\Component\Filesystem\Filesystem;

class FileManager extends BaseFileManager
{
    public function __construct(
        Filesystem $fs,
        AutoloaderUtil $autoloaderUtil,
        MakerFileLinkFormatter $makerFileLinkFormatter,
        string $rootDirectory,
        string $twigDefaultPath = null
    ) {
        parent::__construct($fs, $autoloaderUtil, $makerFileLinkFormatter, $rootDirectory, $twigDefaultPath);
    }

}
