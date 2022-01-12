<?php
//namespace AntonioPrimera\AdminPanel;

function adminPanelPackagePath(?string $path = null) {
	return rtrim(dirname(__DIR__), DIRECTORY_SEPARATOR)
		. DIRECTORY_SEPARATOR
		. ltrim($path ? $path : '', DIRECTORY_SEPARATOR);
}