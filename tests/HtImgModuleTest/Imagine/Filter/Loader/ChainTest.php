<?php

namespace HtImgModuleTest\Imagine\Filter\Loader;

use HtImgModule\Imagine\Filter\Loader\Chain;
use HtImgModule\Imagine\Filter\Loader\FilterLoaderPluginManager;
use HtImgModule\Options\ModuleOptions;
use HtImgModule\Imagine\Resolver\ResolverManager;
use Imagine\Gd\Imagine;
use Zend\ServiceManager\ServiceManager;

class ChainTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $filterLoaders = new ServiceManager;
        $filterLoader = $this->createMock('HtImgModule\Imagine\Filter\Loader\LoaderInterface');
        $filterLoader->expects($this->any())
            ->method('load')
            ->will($this->returnValue($this->createMock('Imagine\Filter\FilterInterface')));
        $filterLoaders->setService('thumbnail', $filterLoader);
        $chainLoader = new  Chain($filterLoaders);
        $chainFilter = $chainLoader->load(['filters' => ['thumbnail' => ['options' => []]]]);
        $this->assertInstanceOf('HtImgModule\Imagine\Filter\Chain', $chainFilter);
    }

    public function testGetExceptionWithNoFilter()
    {
        $filterLoaders = new ServiceManager;
        $chainLoader = new  Chain($filterLoaders);
        $this->setExpectedException('HtImgModule\Exception\InvalidArgumentException');
        $chainFilter = $chainLoader->load(['filters' => []]);
    }

    public function testGetExceptionWithInvalidOptionsType()
    {
        $filterLoaders = new ServiceManager;
        $chainLoader = new  Chain($filterLoaders);
        $this->setExpectedException('HtImgModule\Exception\InvalidArgumentException');
        $chainFilter = $chainLoader->load(['filters' => '']);
    }

    public function testLoadResizeByClassName()
    {
        $filterLoaders = new FilterLoaderPluginManager(new ServiceManager());
    	$chainLoader = new Chain($filterLoaders);
        $chainFilter = $chainLoader->load(['filters' => ['resize' => ['height' => 91, 'width' => 91]]]);
        $this->assertInstanceOf('HtImgModule\Imagine\Filter\Chain', $chainFilter);
    }

    public function testLoadAllInOneChain()
    {
        $serviceManager = new ServiceManager();
        $serviceManager->setService('HtImg\ModuleOptions', new ModuleOptions);
        $serviceManager->setService('HtImg\Imagine', new Imagine);

        /*
         * Mock a resolver and have it always return a
         * full path to 'Archos.jpg' we we call its
         * resolve() method.
         */
        $resolver = $this->createMock('Zend\View\Resolver\ResolverInterface');
        $resolver->expects($this->any())
			            ->method('resolve')
			            ->will($this->returnValue(RESOURCES_DIR . '/Archos.jpg'));
        $serviceManager->setService('HtImg\RelativePathResolver', $resolver);



        $filterLoaders = new FilterLoaderPluginManager($serviceManager);
    	$chainLoader = new Chain($filterLoaders);

        $chainFilter = $chainLoader->load(['filters' => [
        										'thumbnail' => ['height' => 91, 'width' => 91, 'mode' => 'outbound', ],
 												'crop' => ['height' => 91, 'width' => 91, 'start' => [100, 100], ],
        										'resize' => ['height' => 91, 'width' => 91],
        										'background' => ['height' => 91, 'width' => 91, 'color' => '#fff', ],
        										'paste' => ['image' => RESOURCES_DIR . '/Archos.jpg', 'x' => 34, 'y' => 45, ],
        										'relative_resize' => ['heighten' => 92, ],   // No pluging found.
        										'watermark' => ['watermark' => RESOURCES_DIR . '/Archos.jpg', 'size' => '90%', 'position' => 'left', ],
        									]]);
        $this->assertInstanceOf('HtImgModule\Imagine\Filter\Chain', $chainFilter);
    }

}
