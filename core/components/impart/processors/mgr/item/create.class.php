<?php
/**
 * Create an Item
 */
class impArtItemCreateProcessor extends modObjectCreateProcessor {
    public $objectType = 'impArticle';
	public $classKey = 'impArticle';
	public $languageTopics = array('impart');
	public $permission = 'new_document';


	/**
	 * @return bool
	 */
	public function beforeSet() {
        $required = array('content', 'parent');
		foreach ($required as $tmp) {
			if (!$this->getProperty($tmp)) {
				$this->addFieldError($tmp, $this->modx->lexicon('field_required'));
			}
		}
    	if (strpos($this->getProperty('content'),'#') === false) {
			$this->addFieldError('content', $this->modx->lexicon('impart_content_notrules'));
		}
        $articlesList = str_replace(' "', ' «', $this->getProperty('content'));
        $articlesList = str_replace('"', '»', $articlesList);
        $articlesList = str_replace(' - ', ' — ', $articlesList);
        $articles = array_filter(explode('#', $articlesList));
        $articlesList = str_replace(' ', '', $articlesList);
        $objectArr = array();
        $parent = $this->getProperty('parent');
        if ($parentRes = $this->modx->getObject('modResource',$parent)) {
            $context = $parentRes->get('context_key');
        } else {
            $this->addFieldError('parent', $this->modx->lexicon('impart_parent_not_exist'));
        }
    	if ($this->hasErrors()) {
			return false;
		}
        $dublicateQ = array();
        if (!$this->modx->getOption('global_duplicate_uri_check')) {
            $dublicateQ['context_key'] = $context;
        }
        $aliasGenerator = $this->modx->newObject('modResource');

        foreach ($articles as $key => $article) {
            $art = array_filter(explode("\n", $article));
            $pagetitle = trim(array_shift($art));
            $longtitle = trim(array_shift($art));
            $dublicateQ['alias'] = $aliasGenerator->cleanAlias($pagetitle);

            if ($this->modx->getObject('modResource', $dublicateQ)
             || $this->modx->getObject($this->classKey, array('alias' => $dublicateQ['alias'], 'parent' => $parent, 'imported' => 0))) {
                $objectArr[$key]['alias_dublicate'] = true;
            }
            
            foreach ($objectArr as $tmp => $tmpData) {
                if ($tmpData['alias'] == $dublicateQ['alias']) {
                    $objectArr[$key]['alias_dublicate'] = true;
                }
            }
            
            $objectArr[$key]['alias'] = $dublicateQ['alias'];
            $objectArr[$key]['parent'] = $parent;
            $objectArr[$key]['pagetitle'] = $pagetitle;
            $objectArr[$key]['longtitle'] = $longtitle;
            if (mb_strlen($objectArr[$key]['pagetitle']) > 255 || mb_strlen($objectArr[$key]['longtitle']) > 255) {
                $this->addFieldError('content',$this->modx->lexicon('impart_titles_is_big'));
            }
            if ($this->hasErrors()) {
    			return false;
    		}

            $contentArr = array_filter($art);
            $i = 0; $ul = false;
            foreach ($contentArr as $k => $p) {
                if (substr($p,0,1) == "-") {
                    if (!$ul) {
                        $contentArr[$k] = "<ul>\n<li>".str_replace(array('; - ','; -'),";</li>\n<li>",trim(substr($contentArr[$k],1))).'</li>';
                        $ul = true;
                    } else {
                        $contentArr[$k] = '<li>'.trim(substr($contentArr[$k],1)).'</li>';
                    }
                } else {
                    if ($ul) {
                        $contentArr[$k] = "</ul>\n<p>".$contentArr[$k].'</p>';
                        $ul = false;
                    } else {
                        if (strpos($contentArr[$k],'~') !== false) {
                            $contentArr[$k] = '<h2>'.str_replace('~', '', $contentArr[$k]).'</h2>';
                        } elseif (strpos($contentArr[$k],'$') !== false) {
                            $contentArr[$k] = '<h3>'.str_replace('$', '', $contentArr[$k]).'</h3>';
                        } else {
                            $contentArr[$k] = '<p>'.$contentArr[$k].'</p>';
                        }
                    }
                }
            }
            $objectArr[$key]['content'] = implode("\n",$contentArr);
        }
        $objectData = array_shift($objectArr);
        $this->setProperties($objectData);
        unset($objectData);
        foreach ($objectArr as $objectData) {
            if ($objectData['pagetitle'] && $objectData['content']) {
                $art = $this->modx->newObject($this->classKey,$objectData);
                $art->save();
            }
        }
        
		return !$this->hasErrors();
	}

}

return 'impArtItemCreateProcessor';
