<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 18/04/2018
 * Time: 10:53
 */

namespace Sufel\App\Repository;

/**
 * Class FileRepository
 * @package Sufel\App\Repository
 */
class FileRepository implements FileReaderInterface, FileWriterInterface
{
    /**
     * @var DocumentRepositoryInterface
     */
    private $repository;
    private $uploadDirectory;

    /**
     * FileRepository constructor.
     * @param $uploadDirectory
     * @param DocumentRepositoryInterface $repository
     */
    public function __construct($uploadDirectory, DocumentRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->uploadDirectory = $uploadDirectory;
    }

    /**
     * @param string|int $id
     * @param string $type Options: xml|pdf|cdr
     *
     * @return string
     */
    public function getFile($id, $type)
    {
        $doc = $this->repository->get($id);

        $name = $doc['filename'];
        $pathZip = $this->uploadDirectory . DIRECTORY_SEPARATOR . $doc['emisor'] . DIRECTORY_SEPARATOR . $name . '.zip';

        $zip = new \ZipArchive();
        $zip->open($pathZip);
        $result = $zip->getFromName($name.'.'.$type);
        $zip->close();

        return $result;
    }

    /**
     * @param string|int $id
     * @param array $files
     */
    public function writeFiles($id, array $files)
    {
        $document = $this->repository->get($id);
        $name = join('-', [
            $document['emisor'],
            $document['tipo'],
            $document['serie'],
            $document['correlativo']
        ]);

        $rootDir = $this->uploadDirectory . DIRECTORY_SEPARATOR . $document['emisor'];
        if (!is_dir($rootDir)) {
            $oldmask = umask(0);
            mkdir($rootDir, 0777, true);
            umask($oldmask);
        }

        $path = $rootDir . DIRECTORY_SEPARATOR . $name . '.zip';
        $zip = new \ZipArchive();
        $zip->open($path, \ZipArchive::CREATE);
        $zip->addFromString($name . '.xml', $files['xml']);
        $zip->addFromString($name . '.pdf', $files['pdf']);
        $zip->close();
    }
}