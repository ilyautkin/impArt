<?php
/**
 * Update an Item
 */
class impArtItemUpdateProcessor extends modObjectUpdateProcessor {
	public $objectType = 'impArticle';
	public $classKey = 'impArticle';
	public $languageTopics = array('impart');
	public $permission = 'edit_document';
    
    public function beforeSet() {
    	$required = array('pagetitle', 'longtitle', 'content', 'alias');
		foreach ($required as $tmp) {
			if (!$this->getProperty($tmp)) {
				$this->addFieldError($tmp, $this->modx->lexicon('field_required'));
			}
		}
        $dublicateQ = array();
        if ($parentRes = $this->modx->getObject('modResource', $this->getProperty('parent'))) {
            $context = $parentRes->get('context_key');
            if (!$this->modx->getOption('global_duplicate_uri_check')) {
                $dublicateQ['context_key'] = $context;
            }
        }
        $dublicateQ['alias'] = $this->getProperty('alias');
		if ($this->modx->getObject('modResource', $dublicateQ)) {
			$this->addFieldError('alias', $this->modx->lexicon('impart_there_is_dublicate_page'));
		} elseif ($this->modx->getObject($this->classKey, array('alias' => $dublicateQ['alias'], 'parent' => $this->getProperty('parent'), 'imported' => 0))) {
    	   $this->addFieldError('alias', $this->modx->lexicon('impart_there_is_dublicate_item'));
		}
		if ($this->hasErrors()) {
			return false;
		}
        $this->setProperty('alias_dublicate', 0);
        return !$this->hasErrors();
    }
}

return 'impArtItemUpdateProcessor';
