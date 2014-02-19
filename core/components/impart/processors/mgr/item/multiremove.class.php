<?php
/**
 * Remove an Item
 */
class impArtItemMultyRemoveProcessor extends modProcessor {
	public $checkRemovePermission = true;
	public $objectType = 'impArticle';
	public $classKey = 'impArticle';
	public $languageTopics = array('impart');

	public function process() {
        $processorProps = array('processors_path' => dirname(__FILE__) . '/');
        foreach (explode(',',$this->getProperty('resources')) as $id) {
            $response = $this->modx->runProcessor('remove', array('id' => $id), $processorProps);
            if ($response->isError()) {
                return $response->response;
            }
        }
        
        return $this->success();
	}

}

return 'impArtItemMultyRemoveProcessor';