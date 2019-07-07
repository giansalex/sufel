# Sufel

[![Travis-CI](https://travis-ci.org/giansalex/sufel.svg?branch=master)](https://travis-ci.org/giansalex/sufel)
[![Coverage Status](https://coveralls.io/repos/github/giansalex/sufel/badge.svg?branch=master)](https://coveralls.io/github/giansalex/sufel?branch=master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/87a24796afc94e7ea79f3f5f99a95f7c)](https://www.codacy.com/app/giansalex/sufel?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=giansalex/sufel&amp;utm_campaign=Badge_Grade)  

Sufel es una libreria que implementa los procesos de almacenamiento de comprobantes electronicos, y posterior acceso a estos por parte de los receptores de dichos comprobantes, en el marco de 
la facturación electrónica en Perú, y exigido por [SUNAT](http://orientacion.sunat.gob.pe/index.php/empresas-menu/comprobantes-de-pago-empresas/comprobantes-de-pago-electronicos-empresas/see-desde-los-sistemas-del-contribuyente/4-efectos-de-ser-emisor-electronico) a los emisores electrónicos.
> 6.Definir una forma de autenticación que garantice que solo el adquirente o usuario puede acceder a la información.

## Install
From [packagist](https://packagist.org/packages/giansalex/sufel).
```bash
composer require giansalex/sufel
```

## Características
- Recepcionamiento de xml y pdf.
- Multi-Empresa
- Consulta individual de comprobantes empleando datos como el ruc del emisor, tipo, serie, correlativo, fecha y total del comprobante.
- Descarga del comrobante en formato xml y pdf.
- Acceso al receptor para registrarse (actualmente solo para receptores con RUC)
- Consulta de todos los comprobantes de un receptor registrado

## API REST
Empleando Slim Framework, [Sufel Rest](https://github.com/giansalex/sufel-rest).

## UI Client
Una implementación basada en Angular 6 [SUFEL Angular](https://github.com/giansalex/sufel-angular)  
