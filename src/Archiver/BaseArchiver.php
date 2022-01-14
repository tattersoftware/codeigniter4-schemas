<?php

namespace Tatter\Schemas\Archiver;

use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\Files\File;
use Tatter\Schemas\BaseHandler;

/**
 * Base Archiver Class
 *
 * Provides common methods for Archiver classes.
 */
abstract class BaseArchiver extends BaseHandler
{
    /**
     * Validate or create a file and write to it.
     *
     * @param string $path The path to the file
     *
     * @throws FileNotFoundException
     *
     * @return bool Success or failure
     */
    protected function putContents($path, string $data): bool
    {
        $file = new File($path);

        if (! $file->isWritable()) {
            if ($this->config->silent) {
                $this->errors[] = lang('Files.fileNotFound', [$path]);

                return false;
            }

            throw FileNotFoundException::forFileNotFound($path);
        }

        $file = $file->openFile('w');

        return (bool) $file->fwrite($data);
    }
}
