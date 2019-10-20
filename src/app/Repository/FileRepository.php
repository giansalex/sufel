<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 18/04/2018
 * Time: 10:53.
 */

namespace Sufel\App\Repository;

use Sufel\App\Models\Document;
use ZipArchive;

/**
 * Class FileRepository.
 */
class FileRepository implements FileReaderInterface, FileWriterInterface
{
    /**
     * @var string
     */
    private $uploadDirectory;

    /**
     * FileRepository constructor.
     *
     * @param string $uploadDirectory
     */
    public function __construct($uploadDirectory)
    {
        $this->uploadDirectory = $uploadDirectory;
    }

    /**
     * @param string $id
     * @param string $type Options: xml|pdf|cdr
     *
     * @return string
     */
    public function read($id, $type)
    {
        $pathZip = $this->getPathZip($id);

        $parts = explode(DIRECTORY_SEPARATOR, $id);

        $zip = new ZipArchive();
        $zip->open($pathZip);
        $result = $zip->getFromName($parts[1] . '.' . $type);
        $zip->close();

        return $result;
    }

    /**
     * @param Document $document
     * @param array $files
     *
     * @return string
     */
    public function save(Document $document, array $files)
    {
        $name = join('-', [
            $document->getEmisor(),
            $document->getTipo(),
            $document->getSerie(),
            $document->getCorrelativo(),
        ]);

        $this->createDirectory($document);

        $id = $document->getEmisor() . DIRECTORY_SEPARATOR . $name;
        $path = $this->getPathZip($id);
        $this->saveCompress($files, $path, $name);

        return $id;
    }

    /**
     * @param Document $document
     */
    private function createDirectory(Document $document)
    {
        $rootDir = $this->uploadDirectory . DIRECTORY_SEPARATOR . $document->getEmisor();
        if (!is_dir($rootDir)) {
            $oldmask = umask(0);
            mkdir($rootDir, 0777, true);
            umask($oldmask);
        }
    }

    /**
     * @param array $files
     * @param string $path
     * @param string $name
     */
    private function saveCompress(array $files, $path, $name)
    {
        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE);

        foreach ($files as $type => $content) {
            $zip->addFromString($name . '.' . $type, $content);
        }

        $zip->close();
    }

    /**
     * @param $id
     *
     * @return string
     */
    private function getPathZip($id)
    {
        return $this->uploadDirectory.DIRECTORY_SEPARATOR.$id.'.zip';
    }
}
