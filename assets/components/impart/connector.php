<?php

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('impart_core_path', null, $modx->getOption('core_path') . 'components/impart/');
require_once $corePath . 'model/impart/impart.class.php';
$modx->impart = new impArt($modx);

$modx->lexicon->load('impart:default');

/* handle request */
$path = $modx->getOption('processorsPath', $modx->impart->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));