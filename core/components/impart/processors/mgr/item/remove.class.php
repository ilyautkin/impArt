<?php
/**
 * Remove an Item
 */
class impArtItemRemoveProcessor extends modObjectRemoveProcessor {
	public $checkRemovePermission = true;
	public $objectType = 'impArticle';
	public $classKey = 'impArticle';
	public $languageTopics = array('impart');

}

return 'impArtItemRemoveProcessor';