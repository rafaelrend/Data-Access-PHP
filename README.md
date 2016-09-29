# Data-Access-PHP

Contains PHP classes to access the database by design pattern Bridge. Similar to doctrine / or zend, is a framework to handle data 
but faster and using native extensions for each respective database.

Methods to insert, update, delete, query records using array as input and return of each method. It is not necessary to map objects to database 
tables. 

Currently implemented for: MySQL, PostgreSQL, SQL Server and ODBC. To implement a new database is required that new class extend IdbPersist interface.


-- Descrição em Portugês abaixo:

Contém classes PHP para acesso a banco de dados pelo padrão de projetos Bridge. Similar a doctrine/zend ou framework para manipular dados porém mais rápido
e usando as extensões nativas para cada respectivo banco de dados.

Métodos para inserir, atualizar, excluir, consultar registros usando array como entrada e retorno dos mesmos. Não é necessário mapear objetos, o mapeamento é
feito automaticamente lendo as colunas da tabela no banco de dados.

Atualmente implementado para: MySQL, PostGreSQL, Sql Server e ODBC. Para implementar um novo banco de dados é necessário que a classe extenda a interface 
IdbPersist.




