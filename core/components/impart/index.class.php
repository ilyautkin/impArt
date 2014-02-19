<?php

/**
 * Class impArtMainController
 */
abstract class impArtMainController extends modExtraManagerController {
	/** @var impArt $impArt */
	public $impArt;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('impart_core_path', null, $this->modx->getOption('core_path') . 'components/impart/');
		require_once $corePath . 'model/impart/impart.class.php';

		$this->impArt = new impArt($this->modx);

		$this->addCss($this->impArt->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->impArt->config['jsUrl'] . 'mgr/impart.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			impArt.config = ' . $this->modx->toJSON($this->impArt->config) . ';
			impArt.config.connector_url = "' . $this->impArt->config['connectorUrl'] . '";
		});
		</script>');

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('impart:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends impArtMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}