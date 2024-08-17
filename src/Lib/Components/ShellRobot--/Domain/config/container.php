<?php

return [
	'singletons' => [
		'Untek\\Lib\\Components\\ShellRobot\\Domain\\Interfaces\\Services\\VarServiceInterface' => 'Untek\\Lib\\Components\\ShellRobot\\Domain\\Services\\VarService',
		'Untek\\Lib\\Components\\ShellRobot\\Domain\\Interfaces\\Repositories\\VarRepositoryInterface' => 'Untek\\Lib\\Components\\ShellRobot\\Domain\\Repositories\\File\\VarRepository',
		'Untek\\Lib\\Components\\ShellRobot\\Domain\\Interfaces\\Services\\ConfigServiceInterface' => 'Untek\\Lib\\Components\\ShellRobot\\Domain\\Services\\ConfigService',
		'Untek\\Lib\\Components\\ShellRobot\\Domain\\Interfaces\\Repositories\\ConfigRepositoryInterface' => 'Untek\\Lib\\Components\\ShellRobot\\Domain\\Repositories\\File\\ConfigRepository',
		'Untek\\Lib\\Components\\ShellRobot\\Domain\\Interfaces\\Services\\ConnectionServiceInterface' => 'Untek\\Lib\\Components\\ShellRobot\\Domain\\Services\\ConnectionService',
		'Untek\\Lib\\Components\\ShellRobot\\Domain\\Interfaces\\Repositories\\ConnectionRepositoryInterface' => 'Untek\\Lib\\Components\\ShellRobot\\Domain\\Repositories\\File\\ConnectionRepository',
	],
];