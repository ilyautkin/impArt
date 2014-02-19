<?php

if ($object->xpdo) {
	/* @var modX $modx */
	$modx =& $object->xpdo;

	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
			$modelPath = $modx->getOption('impart_core_path',null,$modx->getOption('core_path').'components/impart/').'model/';
			$modx->addPackage('impart', $modelPath);

			$manager = $modx->getManager();
			$objects = array(
				'impArticle',
			);
			foreach ($objects as $tmp) {
				$manager->createObjectContainer($tmp);
			}
            // Запоминаем текущий уровень ошибок
            $level = $modx->getLogLevel();
            // Выставляем самый мощный уровень, чтобы не было ругани в логах при попытке создания существующих полей
            $modx->setLogLevel(xPDO::LOG_LEVEL_FATAL);
            
            // Добавляем поле и индекс
            $manager->addField('impArticle', 'parent');
            $manager->addIndex('impArticle', 'parent');
            
            // Возвращаем старый уровень логирования
            $modx->setLogLevel($level);
			break;
		case xPDOTransport::ACTION_UPGRADE:
			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;
