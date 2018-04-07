# Sufel

[![Travis-CI](https://travis-ci.org/giansalex/sufel.svg?branch=master)](https://travis-ci.org/giansalex/sufel)
[![Coverage Status](https://coveralls.io/repos/github/giansalex/sufel/badge.svg?branch=master)](https://coveralls.io/github/giansalex/sufel?branch=master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/87a24796afc94e7ea79f3f5f99a95f7c)](https://www.codacy.com/app/giansalex/sufel?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=giansalex/sufel&amp;utm_campaign=Badge_Grade)  

Api de consultas para receptores de Facturación Electrónica en Perú según normativa de Superintendencia Nacional de Aduanas y de Administración Tributaria (SUNAT).

## Características
- Publicar el xml y pdf .
- Es Multi-Empresa
- Consulta individual de comprobantes empleando datos como el ruc del emisor, tipo, serie, correlativo, fecha y total del comprobante.
- Descarga del comrobante en formato xml y pdf.
- Permite que el receptor pueda registrarse (actualmente solo para receptores con RUC)
- Consulta de todos los comprobantes de un receptor registrado

## UI Client
Una implementación basada en Angular 5 [SUFEL Angular](https://github.com/giansalex/sufel-angular)  

## API Docs
- [Swagger Docs Full](http://petstore.swagger.io/?url=https://raw.githubusercontent.com/giansalex/sufel/master/src/data/swagger.json)  
- [Swagger for Company](http://editor.swagger.io/?url=https://raw.githubusercontent.com/giansalex/sufel/master/src/data/swagger.company.json)
- [Swagger for Consult](http://editor.swagger.io/?url=https://raw.githubusercontent.com/giansalex/sufel/master/src/data/swagger.receiver.json)
## Docker

Disponible en [Docker Hub](https://hub.docker.com/r/giansalex/sufel/)

```bash
docker pull giansalex/sufel
```
