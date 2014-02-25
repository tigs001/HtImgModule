<?php
namespace HtImgModule;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

class Module implements 
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface,
    ViewHelperProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'HtImg\ModuleOptions' => 'HtImgModule\Factory\ModuleOptionsFactory',
                'HtImg\Imagine' => 'HtImgModule\Factory\ImagineFactory',
                'HtImg\RelativePathResolver' => 'HtImgModule\Factory\RelativePathResolverFactory',
                'HtImgModule\Service\ImageService' => 'HtImgModule\Factory\ImageServiceFactory',
                'HtImgModule\View\Strategy\ImageStrategy' => 'HtImgModule\Factory\ImageStrategyFactory',
                'HtImgModule\Imagine\Filter\Loader\FilterLoaderPluginManager' => 'HtImgModule\Factory\FilterLoaderPluginManagerFactory',
                'HtImgModule\Imagine\Filter\FilterManager' => 'HtImgModule\Factory\FilterManagerFactory',
                'HtImgModule\Service\CacheManager' => 'HtImgModule\Factory\CacheManagerFactory',
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getViewHelperConfig()
    {
        return [
            'factories' => [
                'HtImgModule\View\Helper\ImgUrl' => 'HtImgModule\View\Helper\Factory\ImgUrlFactory',
            ],
            'invokables' => [
                'HtImgModule\View\Helper\DisplayImage' => 'HtImgModule\View\Helper\DisplayImage',
            ],
            'aliases' => [
                'htDisplayImage' => 'HtImgModule\View\Helper\DisplayImage',
                'htImgUrl' => 'HtImgModule\View\Helper\ImgUrl',
            ]
        ];
    }
}
