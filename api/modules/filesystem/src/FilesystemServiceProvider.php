<?php

declare(strict_types=1);

namespace Juling\Filesystem;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class FilesystemServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('cos', function () {
            return new Filesystem(new CosAdapter(\config('filesystems.disks.cos')));
        });

        Storage::extend('obs', function ($app, $config) {
            $debug = $config['debug'] ?? false;
            $endpoint = $config['endpoint'] ?? '';
            $cdn_domain = $config['cdn_domain'] ?? '';
            $ssl_verify = $config['ssl_verify'] ?? false;

            if ($debug) {
                Log::debug('OBS config:', $config);
            }

            $client = new ObsClient($config);

            $bucket = $config['bucket'] ?? '';

            return new Filesystem(new ObsAdapter($client, $bucket, $endpoint, $cdn_domain, $ssl_verify));
        });

        Storage::extend('oss', function($app, $config) {
            $accessId  = $config['access_id'];
            $accessKey = $config['access_key'];

            $cdnDomain = empty($config['cdnDomain']) ? '' : $config['cdnDomain'];
            $bucket    = $config['bucket'];
            $ssl       = empty($config['ssl']) ? false : $config['ssl'];
            $isCname   = empty($config['isCName']) ? false : $config['isCName'];
            $debug     = empty($config['debug']) ? false : $config['debug'];

            $endPoint  = $config['endpoint']; // 默认作为外部节点
            $epInternal= $isCname?$cdnDomain:(empty($config['endpoint_internal']) ? $endPoint : $config['endpoint_internal']); // 内部节点

            $client  = new OssClient($accessId, $accessKey, $epInternal, $isCname);
            $adapter = new AliOssAdapter($client, $bucket, $endPoint, $ssl, $isCname, $debug, $cdnDomain);

            $filesystem =  new Filesystem($adapter);

            $filesystem->addPlugin(new PutFile());
            $filesystem->addPlugin(new PutRemoteFile());

            return $filesystem;
        });
    }
}
