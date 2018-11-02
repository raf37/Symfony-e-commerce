<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Price;

sprintf( 'price' ); // for translation


/**
 * Default implementation of product price JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/product/price/name
	 * Name of the price subpart used by the JQAdm product implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Product\Price\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2017.07
	 * @category Developer
	 */


	/**
	 * Copies a resource
	 *
	 * @return string HTML output
	 */
	public function copy()
	{
		$view = $this->addViewData( $this->getView() );

		$view->priceData = $this->toArray( $view->item, true );
		$view->priceBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->priceBody .= $client->copy();
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
		$view = $this->addViewData( $this->getView() );
		$siteid = $this->getContext()->getLocale()->getSiteId();
		$data = $view->param( 'price', [] );

		foreach( $data as $idx => $entry )
		{
			$data[$idx]['product.lists.siteid'] = $siteid;
			$data[$idx]['price.siteid'] = $siteid;
		}

		$view->priceData = $data;
		$view->priceBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->priceBody .= $client->create();
		}

		return $this->render( $view );
	}


	/**
	 * Deletes a resource
	 */
	public function delete()
	{
		parent::delete();

		$item = $this->getView()->item;
		$item->deleteListItems( $item->getListItems( 'price', null, null, false ), true );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string HTML output
	 */
	public function get()
	{
		$view = $this->addViewData( $this->getView() );

		$view->priceData = $this->toArray( $view->item );
		$view->priceBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->priceBody .= $client->get();
		}

		return $this->render( $view );
	}


	/**
	 * Saves the data
	 */
	public function save()
	{
		$view = $this->getView();

		try
		{
			$view->item = $this->fromArray( $view->item, $view->param( 'price', [] ) );
			$view->priceBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->priceBody .= $client->save();
			}

			return;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'product-item-price' => $this->getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'product-item-price' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

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
		/** admin/jqadm/product/price/decorators/excludes
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
		 *  admin/jqadm/product/price/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/price/decorators/global
		 * @see admin/jqadm/product/price/decorators/local
		 */

		/** admin/jqadm/product/price/decorators/global
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
		 *  admin/jqadm/product/price/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/price/decorators/excludes
		 * @see admin/jqadm/product/price/decorators/local
		 */

		/** admin/jqadm/product/price/decorators/local
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
		 *  admin/jqadm/product/price/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Product\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/price/decorators/excludes
		 * @see admin/jqadm/product/price/decorators/global
		 */
		return $this->createSubClient( 'product/price/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames()
	{
		/** admin/jqadm/product/price/standard/subparts
		 * List of JQAdm sub-clients rendered within the product price section
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
		return $this->getContext()->getConfig()->get( 'admin/jqadm/product/price/standard/subparts', [] );
	}


	/**
	 * Adds the required data used in the price template
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @return \Aimeos\MW\View\Iface View object with assigned parameters
	 */
	protected function addViewData( \Aimeos\MW\View\Iface $view )
	{
		$context = $this->getContext();

		$priceTypeManager = \Aimeos\MShop\Factory::createManager( $context, 'price/type' );
		$listTypeManager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists/type' );
		$currencyManager = \Aimeos\MShop\Factory::createManager( $context, 'locale/currency' );

		$search = $priceTypeManager->createSearch( true )->setSlice( 0, 0x7fffffff );
		$search->setConditions( $search->compare( '==', 'price.type.domain', 'product' ) );
		$search->setSortations( array( $search->sort( '+', 'price.type.label' ) ) );

		$listSearch = $listTypeManager->createSearch( true )->setSlice( 0, 0x7fffffff );
		$listSearch->setConditions( $listSearch->compare( '==', 'product.lists.type.domain', 'price' ) );
		$listSearch->setSortations( array( $listSearch->sort( '+', 'product.lists.type.label' ) ) );

		$view->priceTypes = $priceTypeManager->searchItems( $search );
		$view->priceListTypes = $this->sortType( $listTypeManager->searchItems( $listSearch ) );
		$view->priceCurrencies = $currencyManager->searchItems( $currencyManager->createSearch( true )->setSlice( 0, 0x7fffffff ) );

		if( $view->priceCurrencies === [] ) {
			throw new \Aimeos\Admin\JQAdm\Exception( 'No currencies available. Please enable at least one currency' );
		}

		return $view;
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object without referenced domain items
	 * @param string[] $data Data array
	 */
	protected function fromArray( \Aimeos\MShop\Product\Item\Iface $item, array $data )
	{
		$context = $this->getContext();

		$priceManager = \Aimeos\MShop\Factory::createManager( $context, 'price' );
		$listManager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists' );
		$priceTypeManager = \Aimeos\MShop\Factory::createManager( $context, 'price/type' );
		$listTypeManager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists/type' );

		$listItems = $item->getListItems( 'price', null, null, false );


		foreach( $data as $idx => $entry )
		{
			$type = $priceTypeManager->getItem( $entry['price.typeid'] )->getCode();
			$listType = $listTypeManager->getItem( $entry['product.lists.typeid'] )->getCode();

			if( ( $listItem = $item->getListItem( 'price', $listType, $entry['price.id'] ) ) === null ) {
				$listItem = $listManager->createItem( $listType, 'price' );
			}

			if( ( $refItem = $listItem->getRefItem() ) === null ) {
				$refItem = $priceManager->createItem( $type, 'product' );
			}

			$refItem->fromArray( $entry );
			$conf = [];

			foreach( (array) $this->getValue( $entry, 'config/key' ) as $num => $key )
			{
				if( trim( $key ) !== '' && ( $val = $this->getValue( $entry, 'config/val/' . $num ) ) !== null ) {
					$conf[$key] = trim( $val );
				}
			}

			$listItem->fromArray( $entry );
			$listItem->setPosition( $idx );
			$listItem->setConfig( $conf );

			$item->addListItem( 'price', $listItem, $refItem );

			unset( $listItems[$listItem->getId()] );
		}

		return $item->deleteListItems( $listItems, true );
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
		$data = [];
		$siteId = $this->getContext()->getLocale()->getSiteId();

		foreach( $item->getListItems( 'price', null, null, false ) as $listItem )
		{
			if( ( $refItem = $listItem->getRefItem() ) === null ) {
				continue;
			}

			$list = $listItem->toArray( true ) + $refItem->toArray( true );

			if( $copy === true )
			{
				$list['product.lists.siteid'] = $siteId;
				$list['product.lists.id'] = '';
				$list['price.siteid'] = $siteId;
				$list['price.id'] = null;
			}

			$list['product.lists.datestart'] = str_replace( ' ', 'T', $list['product.lists.datestart'] );
			$list['product.lists.dateend'] = str_replace( ' ', 'T', $list['product.lists.dateend'] );

			foreach( $list['product.lists.config'] as $key => $val )
			{
				$list['config']['key'][] = $key;
				$list['config']['val'][] = $val;
			}

			$data[] = $list;
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
		/** admin/jqadm/product/price/template-item
		 * Relative path to the HTML body template of the price subpart for products.
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
		$tplconf = 'admin/jqadm/product/price/template-item';
		$default = 'product/item-price-standard.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}