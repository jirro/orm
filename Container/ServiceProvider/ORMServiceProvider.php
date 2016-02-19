<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\ORM\Container\ServiceProvider;

use League\Container\ServiceProvider;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager as ObjectManager;
use Doctrine\ORM\Mapping\Driver\XmlDriver;

class ORMServiceProvider extends ServiceProvider
{
    protected $provides = [
        'object_manager',
    ];

    public function register()
    {
        $this->container['object_manager'] = function () {
            $development = false;
            if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
                $development = true;
            }

            $config = new Configuration();

            if ($development) {
                $cache = new ArrayCache();
            } else {
                $cache = new ApcCache();
            }
            $config->setMetadataCacheImpl($cache);

            $mappings = $this->container->get('config')['orm']['mappings'];
            $config->setMetadataDriverImpl(new XmlDriver($mappings));

            $proxyDir = $this->container->get('config')['orm']['proxy_dir'];
            $config->setProxyDir($proxyDir);

            $config->setQueryCacheImpl($cache);
            $config->setProxyNamespace('Jirro\ORM\Proxies');

            if ($development) {
                $config->setAutoGenerateProxyClasses(true);
            } else {
                $config->setAutoGenerateProxyClasses(false);
            }

            $dbConnection  = $this->container->get('db_connection');
            $objectManager = ObjectManager::create($dbConnection, $config);

            return $objectManager;
        };

        $this
            ->container
            ->inflector('Jirro\Component\ORM\ObjectManagerAwareInterface')
            ->invokeMethod('setObjectManager', ['object_manager'])
        ;
    }
}
