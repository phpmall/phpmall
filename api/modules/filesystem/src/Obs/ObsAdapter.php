<?php

namespace Juling\Filesystem\Obs;

use Juling\Filesystem\Obs\ObsClient;
use Juling\Filesystem\Obs\ObsException;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Adapter\Polyfill\NotSupportingVisibilityTrait;
use League\Flysystem\Config;

/**
 * Class ObsAdapter
 * @package Obs
 */
class ObsAdapter extends AbstractAdapter
{
    use NotSupportingVisibilityTrait;

    /**
     * @var ObsClient
     */
    protected $client;

    /**
     * @var
     */
    protected $bucket;

    /**
     * @var
     */
    protected $endpoint;

    /**
     * @var
     */
    protected $cdnDomain;

    /**
     * @var
     */
    protected $ssl;

    /**
     * ObsAdapter constructor.
     * @param ObsClient $client
     * @param string $bucket
     * @param string $prefix
     */
    public function __construct(ObsClient $client, string $bucket, string $endpoint, string $cdnDomain, bool $ssl, string $prefix = '')
    {
        $this->client = $client;
        $this->bucket = $bucket;
        $this->endpoint = $endpoint;
        $this->cdnDomain = $cdnDomain;
        $this->ssl = $ssl;

        $this->setPathPrefix($prefix);
    }

    /**
     * @return ObsClient
     */
    public function getClient(): ObsClient
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getBucket(): string
    {
        return $this->bucket;
    }

    /**
     * @param string $path
     * @param string $contents
     * @param Config $config
     * @return array|bool|false
     */
    public function write($path, $contents, Config $config)
    {
        $path = $this->applyPathPrefix($path);

        try {
            $object = $this->client->putObject([
                'Bucket' => $this->getBucket(),
                'Key' => $path,
                'SourceFile' => $contents
            ]);
        } catch (ObsException $e) {
            return false;
        }

        return $this->normalizeResponse($object);
    }

    /**
     * @param string $path
     * @param resource $resource
     * @param Config $config
     * @return array|bool|false
     */
    public function writeStream($path, $resource, Config $config)
    {
        $path = $this->applyPathPrefix($path);

        try {
            $object = $this->client->putObject([
                'Bucket' => $this->getBucket(),
                'Key' => $path,
                'Body' => $resource
            ]);
        } catch (ObsException $e) {
            return false;
        }

        return $this->normalizeResponse($object);
    }

    /**
     * @param string $path
     * @param string $contents
     * @param Config $config
     * @return array|bool|false
     */
    public function update($path, $contents, Config $config)
    {
        return $this->write($path, $contents, $config);
    }

    /**
     * @param string $path
     * @param resource $resource
     * @param Config $config
     * @return array|bool|false
     */
    public function updateStream($path, $resource, Config $config)
    {
        return $this->writeStream($path, $resource, $config);
    }

    /**
     * @param string $path
     * @param string $newpath
     * @return bool
     */
    public function rename($path, $newpath)
    {
        if ($this->copy($path, $newpath) && $this->delete($path)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $path
     * @param string $newpath
     * @return bool
     */
    public function copy($path, $newpath)
    {
        $path = $this->applyPathPrefix($path);
        $newpath = $this->applyPathPrefix($newpath);

        try {
            $object = $this->client->deleteObject([
                'Bucket' => $this->getBucket(),
                'Key' => $newpath,
                'CopySource' => $this->getBucket() . '/' . $path
            ]);
        } catch (ObsException $e) {
            return false;
        }

        return true;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function delete($path)
    {
        $path = $this->applyPathPrefix($path);

        try {
            $object = $this->client->deleteObject([
                'Bucket' => $this->getBucket(),
                'Key' => $path
            ]);
        } catch (ObsException $e) {
            return false;
        }

        return true;
    }

    /**
     * @param string $dirname
     * @return bool
     */
    public function deleteDir($dirname)
    {
        return $this->delete($dirname);
    }

    /**
     * @param string $dirname
     * @param Config $config
     * @return array|bool|false
     */
    public function createDir($dirname, Config $config)
    {
        $path = $this->applyPathPrefix($dirname);

        try {
            $object = $this->client->putObject([
                'Bucket' => $this->getBucket(),
                'Key' => $path
            ]);
        } catch (ObsException $e) {
            return false;
        }

        return $this->normalizeResponse($object);
    }

    /**
     * @param string $path
     * @return array|bool|false|null
     */
    public function has($path)
    {
        return $this->getMetadata($path);
    }

    /**
     * @param string $path
     * @return array|bool|false
     */
    public function read($path)
    {
        $path = $this->applyPathPrefix($path);

        try {
            $object = $this->client->getObject([
                'Bucket' => $this->getBucket(),
                'Key' => $path
            ]);
        } catch (ObsException $e) {
            return false;
        }

        $object['contents'] = (string)$object['Body'];
        unset($object['Body']);

        return $this->normalizeResponse($object);
    }

    /**
     * @param string $path
     * @return array|bool|false
     */
    public function readStream($path)
    {
        $path = $this->applyPathPrefix($path);

        try {
            $object = $this->client->getObject([
                'Bucket' => $this->getBucket(),
                'Key' => $path,
                'SaveAsStream' => true
            ]);
        } catch (ObsException $e) {
            return false;
        }

        $object['stream'] = $object['Body'];
        unset($object['Body']);

        return $this->normalizeResponse($object);
    }

    /**
     * @param string $directory
     * @param bool $recursive
     * @return array|bool
     */
    public function listContents($directory = '', $recursive = false)
    {
        $path = $this->applyPathPrefix($directory);

        try {
            $object = $this->client->listObjects([
                'Bucket' => $this->getBucket(),
                'MaxKeys' => 1000,
                'Prefix' => $directory,
                'Marker' => null
            ]);
        } catch (ObsException $e) {
            return false;
        }

        $contents = $object["Contents"];

        if (!count($contents)) {
            return [];
        }

        return array_map(function ($entry) {
            $path = $this->removePathPrefix($entry['Key']);
            return $this->normalizeResponse($entry, $path);
        }, $contents);
    }

    /**
     * @param string $path
     * @return array|bool|false
     */
    public function getMetadata($path)
    {
        $path = $this->applyPathPrefix($path);

        try {
            $object = $this->client->getObjectMetadata([
                'Bucket' => $this->getBucket(),
                'Key' => $path
            ]);
        } catch (ObsException $e) {
            return false;
        }

        return $this->normalizeResponse($object);
    }

    /**
     * @param string $path
     * @return array|bool|false
     */
    public function getSize($path)
    {
        $object = $this->getMetadata($path);
        $object['size'] = $object['ContentLength'];

        return $object;
    }

    /**
     * @param string $path
     * @return array|bool|false
     */
    public function getMimetype($path)
    {
        $object = $this->getMetadata($path);
        $object['mimetype'] = $object['ContentType'];

        return $object;
    }

    /**
     * @param string $path
     * @return array|bool|false
     */
    public function getTimestamp($path)
    {
        $object = $this->getMetadata($path);

        return $object;
    }

    /**
     * @param $path
     * @return string
     */
    public function getUrl($path)
    {
        return ($this->ssl ? 'https://' : 'http://')
            . ($this->cdnDomain == '' ? $this->getBucket() . '.' . $this->endpoint : $this->cdnDomain)
            . '/' . ltrim($path, '/');
    }

    /**
     * @param $object
     * @return array
     */
    public function normalizeResponse($object): array
    {
        $path = ltrim($this->removePathPrefix($object['Key']), '/');

        $result = ['path' => $path];

        if (isset($object['LastModified'])) {
            $result['timestamp'] = strtotime($object['LastModified']);
        }

        if (isset($object['Size'])) {
            $result['size'] = $object['Size'];
            $result['bytes'] = $object['Size'];
        }

        $type = (substr($result['path'], -1) === '/' ? 'dir' : 'file');

        $result['type'] = $type;

        return $result;
    }
}
