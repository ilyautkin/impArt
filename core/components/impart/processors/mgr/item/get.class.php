<?php
/**
 * Get an Item
 */
class impArtItemGetProcessor extends modObjectGetProcessor {
	public $objectType = 'impArticle';
	public $classKey = 'impArticle';
	public $languageTopics = array('impart:default');
}

return 'impArtItemGetProcessor';