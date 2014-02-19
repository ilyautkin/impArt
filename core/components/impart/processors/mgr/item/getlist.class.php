<?php
/**
 * Get a list of Items
 */
class impArtItemGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'impArticle';
	public $classKey = 'impArticle';
	public $defaultSortField = 'imported';
	public $defaultSortDirection = 'ASC';
	public $renderers = '';


	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
		return $c;
	}


	/**
	 * @param xPDOObject $object
	 *
	 * @return array
	 */
	public function prepareRow(xPDOObject $object) {
		$array = $object->toArray();

		return $array;
	}

}

return 'impArtItemGetListProcessor';