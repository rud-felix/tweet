# Test task: a simple social network like twitter (API) 

- Symfony2
- Doctrine2 ORM
- RESTful
- TwitterBootstrap
- some bundles like FOSUserBundle, FOSRestBundle, Elasticsearch ...

#### Requirements
- PHP 5.5.x >=
- ElasticSearch 1.7.4 

#### Configuring
Install ElasticSearch 
` https://download.elastic.co/elasticsearch/elasticsearch/elasticsearch-1.7.4.deb`
Install vendors
```sh
$ composer install
```
Run ElasticSearch
```sh
$ sudo service elasticsearch start
```
Run reload script 
```sh
$ php bin/reload
```

#### Usage
API Doc URI: `/api/doc`

#### Plans
- Refactoring