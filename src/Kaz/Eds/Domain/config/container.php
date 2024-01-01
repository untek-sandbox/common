<?php

return [
	'singletons' => [
		'Untek\\Kaz\\Eds\\Domain\\Interfaces\\Services\\CrlServiceInterface' => 'Untek\\Kaz\\Eds\\Domain\\Services\\CrlService',
		'Untek\\Kaz\\Eds\\Domain\\Interfaces\\Repositories\\CrlRepositoryInterface' => 'Untek\\Kaz\\Eds\\Domain\\Repositories\\Eloquent\\CrlRepository',
		'Untek\\Kaz\\Eds\\Domain\\Interfaces\\Services\\HostServiceInterface' => 'Untek\\Kaz\\Eds\\Domain\\Services\\HostService',
		'Untek\\Kaz\\Eds\\Domain\\Interfaces\\Repositories\\HostRepositoryInterface' => 'Untek\\Kaz\\Eds\\Domain\\Repositories\\Eloquent\\HostRepository',
		'Untek\\Kaz\\Eds\\Domain\\Interfaces\\Services\\LogServiceInterface' => 'Untek\\Kaz\\Eds\\Domain\\Services\\LogService',
		'Untek\\Kaz\\Eds\\Domain\\Interfaces\\Repositories\\LogRepositoryInterface' => 'Untek\\Kaz\\Eds\\Domain\\Repositories\\Eloquent\\LogRepository',
		'Untek\\Kaz\\Eds\\Domain\\Interfaces\\Services\\CertificateServiceInterface' => 'Untek\\Kaz\\Eds\\Domain\\Services\\CertificateService',
		'Untek\\Kaz\\Eds\\Domain\\Interfaces\\Repositories\\CertificateRepositoryInterface' => 'Untek\\Kaz\\Eds\\Domain\\Repositories\\Eloquent\\CertificateRepository',
	],
];