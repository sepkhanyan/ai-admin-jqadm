<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2020
 */


namespace Aimeos\Admin\JQAdm\Catalog\Media\Property;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelperJqadm::getView();
		$this->context = \TestHelperJqadm::getContext();

		$this->object = new \Aimeos\Admin\JQAdm\Catalog\Media\Property\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testCreate()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'catalog' );

		$this->view->item = $manager->create();
		$result = $this->object->create();

		$this->assertStringContainsString( 'Media properties', $result );
		$this->assertEmpty( $this->view->get( 'errors' ) );
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'catalog' );

		$this->view->item = $manager->find( 'cafe', ['media'] );
		$result = $this->object->copy();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( 'item-media-property', $result );
	}


	public function testDelete()
	{
		$result = $this->object->delete();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertEmpty( $result );
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'catalog' );

		$this->view->item = $manager->find( 'cafe', ['media'] );
		$result = $this->object->get();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( 'item-media-property', $result );
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'catalog' );
		$typeManager = \Aimeos\MShop::create( $this->context, 'media/property/type' );

		$item = $manager->find( 'cafe', ['media'] );
		$item->setCode( 'jqadm-test-media-property' );
		$item->setId( null );

		$this->view->item = $manager->insertItem( $item );

		$param = array(
			'site' => 'unittest',
			'media' => array(
				0 => array(
					'property' => array(
						0 => array(
							'media.property.id' => '',
							'media.property.type' => 'size',
						),
					),
				),
			),
		);

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );


		$result = $this->object->save();


		$manager->delete( $this->view->item->getId() );

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertEmpty( $result );

		$mediaItems = $this->view->item->getRefItems( 'media' )->toArray();
		$this->assertEquals( 2, count( $mediaItems ) );
		$this->assertEquals( 1, count( reset( $mediaItems )->getPropertyItems() ) );
	}


	public function testSaveException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Catalog\Media\Property\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$this->view = \TestHelperJqadm::getView();
		$this->view->item = \Aimeos\MShop::create( $this->context, 'catalog' )->create();

		$object->setView( $this->view );

		$this->expectException( \RuntimeException::class );
		$object->save();
	}


	public function testSearch()
	{
		$this->assertEmpty( $this->object->search() );
	}


	public function testGetSubClient()
	{
		$this->expectException( \Aimeos\Admin\JQAdm\Exception::class );
		$this->object->getSubClient( 'unknown' );
	}
}
