<?php
namespace HtImgModule\Imagine\Filter\Loader;

use Zend\ServiceManager\AbstractPluginManager;
use HtImgModule\Exception;
use Zend\ServiceManager\Exception\InvalidServiceException;
use Zend\ServiceManager\Factory\InvokableFactory;

class FilterLoaderPluginManager extends AbstractPluginManager
{
    protected $instanceOf = LoaderInterface::class;

    protected $aliases  = [
        'Crop' => Crop::class,
        'crop' => Crop::class,
        'RelativeResize' => RelativeResize::class,
        'relativeResize' => RelativeResize::class,
        'relativeresize' => RelativeResize::class,
    	'relative_resize' => RelativeResize::class,
        'Resize' => Resize::class,
        'resize' => Resize::class,
        'Thumbnail' => Thumbnail::class,
        'thumbnail' => Thumbnail::class,
    ];

    protected $factories = [
        'chain' => 'HtImgModule\Imagine\Filter\Loader\Factory\ChainFactory',
        'paste' => 'HtImgModule\Imagine\Filter\Loader\Factory\PasteFactory',
        'watermark' => 'HtImgModule\Imagine\Filter\Loader\Factory\WatermarkFactory',
        'background' => 'HtImgModule\Imagine\Filter\Loader\Factory\BackgroundFactory',
        Crop::class => InvokableFactory::class,
        RelativeResize::class => InvokableFactory::class,
        Resize::class => InvokableFactory::class,
        Thumbnail::class => InvokableFactory::class,

		// Zf2 canonicalized name format
//    	'htimgmoduleimaginefilterloaderrelativeresize' => InvokableFactory::class,
    ];

    public function validate($instance)
    {
        if (!$instance instanceof $this->instanceOf) {
            throw new InvalidServiceException(sprintf(
                'Invalid plugin "%s" created; not an instance of %s',
                get_class($instance),
                $this->instanceOf
            ));
        }
    }

    public function validatePlugin($instance)
    {
        try {
            $this->validate($instance);
        } catch (InvalidServiceException $e) {
            throw new Exception\InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
