<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2017
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Characteristic\Property;


/**
 * Default implementation of product property JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/product/characteristic/property/standard/subparts
	 * List of JQAdm sub-clients rendered within the product property section
	 *
	 * The output of the frontend is composed of the code generated by the JQAdm
	 * clients. Each JQAdm client can consist of serveral (or none) sub-clients
	 * that are responsible for rendering certain sub-parts of the output. The
	 * sub-clients can contain JQAdm clients themselves and therefore a
	 * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
	 * the output that is placed inside the container of its parent.
	 *
	 * At first, always the JQAdm code generated by the parent is printed, then
	 * the JQAdm code of its sub-clients. The order of the JQAdm sub-clients
	 * determines the order of the output of these sub-clients inside the parent
	 * container. If the configured list of clients is
	 *
	 *  array( "subclient1", "subclient2" )
	 *
	 * you can easily change the order of the output by reordering the subparts:
	 *
	 *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
	 *
	 * You can also remove one or more parts if they shouldn't be rendered:
	 *
	 *  admin/jqadm/<clients>/subparts = array( "subclient1" )
	 *
	 * As the clients only generates structural JQAdm, the layout defined via CSS
	 * should support adding, removing or reordering content by a fluid like
	 * design.
	 *
	 * @param array List of sub-client names
	 * @since 2016.01
	 * @category Developer
	 */
	private $subPartPath = 'admin/jqadm/product/characteristic/property/standard/subparts';
	private $subPartNames = [];


	/**
	 * Copies a resource
	 *
	 * @return string HTML output
	 */
	public function copy()
	{
		$view = $this->getView();

		$view->propertyData = $this->toArray( $view->item, true );
		$view->propertyTypes = $this->getPropertyTypes();
		$view->propertyBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->propertyBody .= $client->copy();
		}

		return $this->render( $view );
	}


	/**
	 * Creates a new resource
	 *
	 * @return string HTML output
	 */
	public function create()
	{
		$view = $this->getView();

		$view->propertyData = $view->param( 'characteristic/property', [] );
		$view->propertyTypes = $this->getPropertyTypes();
		$view->propertyBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->propertyBody .= $client->create();
		}

		return $this->render( $view );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string HTML output
	 */
	public function get()
	{
		$view = $this->getView();

		$view->propertyData = $this->toArray( $view->item );
		$view->propertyTypes = $this->getPropertyTypes();
		$view->propertyBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->propertyBody .= $client->get();
		}

		return $this->render( $view );
	}


	/**
	 * Saves the data
	 */
	public function save()
	{
		$view = $this->getView();
		$context = $this->getContext();

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists' );
		$manager->begin();

		try
		{
			$this->fromArray( $view->item, $view->param( 'characteristic/property', [] ) );
			$view->propertyBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->propertyBody .= $client->save();
			}

			$manager->commit();
			return;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'product-item-characteristic-property' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
		}
		catch( \Exception $e )
		{
			$error = array( 'product-item-characteristic-property' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
		}

		$manager->rollback();

		throw new \Aimeos\Admin\JQAdm\Exception();
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
	 */
	public function getSubClient( $type, $name = null )
	{
		/** admin/jqadm/product/characteristic/property/decorators/excludes
		 * Excludes decorators added by the "common" option from the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "admin/jqadm/common/decorators/default" before they are wrapped
		 * around the JQAdm client.
		 *
		 *  admin/jqadm/product/characteristic/property/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/characteristic/property/decorators/global
		 * @see admin/jqadm/product/characteristic/property/decorators/local
		 */

		/** admin/jqadm/product/characteristic/property/decorators/global
		 * Adds a list of globally available decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/characteristic/property/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/characteristic/property/decorators/excludes
		 * @see admin/jqadm/product/characteristic/property/decorators/local
		 */

		/** admin/jqadm/product/characteristic/property/decorators/local
		 * Adds a list of local decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Product\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/characteristic/property/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Product\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/characteristic/property/decorators/excludes
		 * @see admin/jqadm/product/characteristic/property/decorators/global
		 */
		return $this->createSubClient( 'product/characteristic/property/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames()
	{
		return $this->getContext()->getConfig()->get( $this->subPartPath, $this->subPartNames );
	}


	/**
	 * Returns the product properties for the given product ID
	 *
	 * @param string $prodid Unique product ID
	 * @return array Associative list of property IDs as keys and property items as values
	 */
	protected function getProperties( $prodid )
	{
		$excludes = array( 'package-length', 'package-height', 'package-width', 'package-weight' );
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'product/property' );

		$search = $manager->createSearch();
		$expr = array(
			$search->compare( '==', 'product.property.parentid', $prodid ),
			$search->compare( '!=', 'product.property.type.code', $excludes ),
		);
		$search->setConditions( $search->combine( '&&', $expr ) );
		$search->setSlice( 0, 0x7fffffff );

		return $manager->searchItems( $search );
	}


	/**
	 * Returns the available product property types
	 *
	 * @return array Associative list of property type IDs as keys and property type items as values
	 */
	protected function getPropertyTypes()
	{
		$excludes = array( 'package-length', 'package-height', 'package-width', 'package-weight' );
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'product/property/type' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '!=', 'product.property.type.code', $excludes ) );
		$search->setSlice( 0, 0x7fffffff );

		return $manager->searchItems( $search );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object without referenced domain items
	 * @param string[] $data Data array
	 */
	protected function fromArray( \Aimeos\MShop\Product\Item\Iface $item, array $data )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'product/property' );

		$map = $this->getProperties( $item->getId() );
		$ids = (array) $this->getValue( $data, 'product.property.id', [] );

		$propItem = $manager->createItem();

		foreach( $ids as $idx => $propertyId )
		{
			if( isset( $map[$propertyId] ) )
			{
				$propItem = $map[$propertyId];
				unset( $map[$propertyId] );
			}
			else
			{
				$propItem->setId( null );
				$propItem->setParentId( $item->getId() );
			}

			$lang = $this->getValue( $data, 'product.property.languageid/' . $idx, null );

			$propItem->setLanguageId( ( $lang ? $lang : null ) );
			$propItem->setTypeId( $this->getValue( $data, 'product.property.typeid/' . $idx, null ) );
			$propItem->setValue( $this->getValue( $data, 'product.property.value/' . $idx, '' ) );

			$manager->saveItem( $propItem, false );
		}

		$manager->deleteItems( array_keys( $map ) );
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object including referenced domain items
	 * @param boolean $copy True if items should be copied, false if not
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Product\Item\Iface $item, $copy = false )
	{
		$siteId = $this->getContext()->getLocale()->getSiteId();
		$data = [];

		foreach( $this->getProperties( $item->getId() ) as $item )
		{
			$list = $item->toArray( true );

			if( $copy === true )
			{
				$list['product.property.siteid'] = $siteId;
				$list['product.property.id'] = '';
			}

			foreach( $list as $key => $value ) {
				$data[$key][] = $value;
			}
		}

		return $data;
	}


	/**
	 * Returns the rendered template including the view data
	 *
	 * @param \Aimeos\MW\View\Iface $view View object with data assigned
	 * @return string HTML output
	 */
	protected function render( \Aimeos\MW\View\Iface $view )
	{
		/** admin/jqadm/product/characteristic/property/template-item
		 * Relative path to the HTML body template of the property characteristic subpart for products.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in admin/jqadm/templates).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "default" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "default"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating the HTML code
		 * @since 2016.04
		 * @category Developer
		 */
		$tplconf = 'admin/jqadm/product/characteristic/property/template-item';
		$default = 'product/item-characteristic-property-default.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
