<?php


namespace Source\Support;


use Google\Cloud\Storage\StorageClient;
use League\Flysystem\Config;
use League\Flysystem\Filesystem;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

/**
 * Class GoogleStorage
 * @package Source\Support
 */
class GoogleStorage
{
    /**
     * @var Filesystem
     */
    private $file;

    /**
     * GoogleStorage constructor.
     */
    public function __construct()
    {
        $storageClient = new StorageClient([
            'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
            'keyFilePath' => __DIR__ . '/../../key_storage.json',
        ]);
        $bucket = $storageClient->bucket(env('GOOGLE_CLOUD_STORAGE_BUCKET'));
        $adapter = new GoogleStorageAdapter($storageClient, $bucket);
        $this->file = new Filesystem($adapter, new Config([
            'disable_asserts' => true,
        ]));
    }


    public function write($patch, $content)
    {
        if ($this->file->write($patch, $content)) {
            return true;
        }
        return false;
    }

    /**
     * @param $patch
     * @return bool
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function delete($patch)
    {
        if ($this->exists($patch)) {
            if ($this->file->delete($patch)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $patch
     * @return bool
     */
    public function exists($patch)
    {
        if ($this->file->has($patch)) {
            return true;
        }
        return false;
    }

    /**
     * @param $patch
     * @return bool
     */
    public function deleteDir($patch)
    {
        if ($this->file->deleteDir($patch)) {
            return true;
        }
        return false;
    }

    /**
     * @param $patch
     * @param $content
     * @return bool
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function update($patch, $content)
    {
        if ($this->exists($patch)) {
            if ($this->file->update($patch, $content)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $patch
     * @return string|null
     */
    public function url($patch)
    {
        return "https://storage.googleapis.com/" . env('GOOGLE_CLOUD_STORAGE_BUCKET') . $patch;
    }

}