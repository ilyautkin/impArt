<?php
/**
 * Send an Queue
 */
class impArtItemIterateProcessor extends modProcessor {
    public $objectType = 'impArticle';
	public $classKey = 'impArticle';

	public function process() {
        $c = $this->modx->newQuery($this->classKey, array(
                'imported' => 0,
                'alias_dublicate' => 0
            ));
        $c->limit(10);
        $articles = $this->modx->getCollection($this->classKey, $c);
        foreach ($articles as $article) {
            $response = $this->modx->runProcessor('resource/create', $article->toArray());
            if ($response->isError()) {
                return $response->response;
            }
            $article->set('imported', true);
            $article->save();
        }
        return $this->success();
	}

}

return 'impArtItemIterateProcessor';