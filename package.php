<?php

require_once 'PEAR/PackageFileManager.php';

$version = '2.0.0beta2';
$notes = <<<EOT
The core of MDB2 is now fairly stable API-wise. The modules, especially the
manager and reverse module, might see some API refinement before the first
stable release.
- added listTables() and listTableFields() methods to MDB2_Driver_Manager_mssql
  and MDB2_Driver_Manager_oci8
- reversed parameter order of getValue(), type parameter is now optional and
  will then be autodetected (BC break!)
- renamed get*Value() to quote*() (BC break!)
- fixed LOB management in MDB2_Driver_ibase
- moved getOne, getRow, getCol, getAll back into the exteneded module (most
  users should be able to move to the queryOne, queryRow, queryCol and queryAll
  equivalent) (BC break!)
- added getAssoc to the extended module
- fixed bug in MDB2_Driver_Datatype_Common::implodeArray()
- added sequence_col_name option to make the column name inside sequence
  emulation tables configurable
- fixed a bug in the MDB2_Driver_oci8 and MDB2_Driver_ibase buffering emulation
  when using limit queries
- removed MDB2_PORTABILITY_NULL_TO_EMPTY in favor of MDB2_PORTABILITY_EMPTY_TO_NULL
  this means that DB and MDB2 work exactly the opposite now, but it seems more
  efficient to do things the way Oracle does since this is the RDBMS which
  creates the original issue to begin with (BC break!)
- fixed a typos in getAll, getAssoc and getCol
- test suite: moved set_time_limit() call to the setup script to be easier to customize
- renamed hasMore() to valid() due to changes in the PHP5 iterator API (BC break!)
- renamed toString() to __toString() in order to take advantage of new PHP5
  goodness and made it public
- MDB2_Driver_Datatype_Common::setResultTypes() can now handle missing elements
  inside type arrays: array(2 => 'boolean', 4 => 'timestamp')
- fixed potential warning due to manipulation query detection in the query*()
  and the get*() query+fetch methods
- added tests for fetchAll() and fetchCol()
- performance tweaks for fetchAll() and fetchCol()
- fixed MDB2_Driver_Manager_mysql::listTableIndexes()
- fixed MDB2_Driver_Common::debug()
- renamed MDB2::isResult() to MDB2::isResultCommon()
- added base result class MDB2_Result from which all result sets should be
  inherited and added MDB2::isResult() which checks if a given object extends from it
- added 'result_wrap_class' option and optional parameter to query() to enable
  wrapping of result classes into an arbitrary class
- added \$result_class param to all drivers where it was missing from the
  query() and _executePrepared() methods
- applied several fixes to the PEAR::DB wrapper
- fixed a typo in MDB2_Driver_Reverse_pgsql::tableInfo()
EOT;

$description =<<<EOT
PEAR MDB2 is a merge of the PEAR DB and Metabase php database abstraction layers.

It provides a common API for all support RDBMS. The main difference to most
other DB abstraction packages is that MDB2 goes much further to ensure
portability. Among other things MDB2 features:
* An OO-style query API
* A DSN (data source name) or array format for specifying database servers
* Datatype abstraction and on demand datatype conversion
* Portable error codes
* Sequential and non sequential row fetching as well as bulk fetching
* Ability to make buffered and unbuffered queries
* Ordered array and associative array for the fetched rows
* Prepare/execute (bind) emulation
* Sequence emulation
* Replace emulation
* Limited Subselect emulation
* Row limit support
* Transactions support
* Large Object support
* Index/Unique support
* Module Framework to load advanced functionality on demand
* Table information interface
* RDBMS management methods (creating, dropping, altering)
* RDBMS independent xml based schema definition management
* Altering of a DB from a changed xml schema
* Reverse engineering of xml schemas from an existing DB (currently only MySQL)
* Full integration into the PEAR Framework
* PHPDoc API documentation

Currently supported RDBMS:
MySQL
PostGreSQL
Oracle
Frontbase
Querysim
Interbase/Firebird
MSSQL
SQLite
Other soon to follow.
EOT;

$package = new PEAR_PackageFileManager();

$result = $package->setOptions(array(
    'package'           => 'MDB2',
    'summary'           => 'database abstraction layer',
    'description'       => $description,
    'version'           => $version,
    'state'             => 'beta',
    'license'           => 'BSD License',
    'filelistgenerator' => 'cvs',
    'ignore'            => array('package.php', 'package.xml'),
    'notes'             => $notes,
    'changelogoldtonew' => false,
    'simpleoutput'      => true,
    'baseinstalldir'    => '/',
    'packagedirectory'  => './',
    'dir_roles'         => array('docs' => 'doc',
                                 'examples' => 'doc',
                                 'tests' => 'test',
                                 'tests/templates' => 'test')
    ));

if (PEAR::isError($result)) {
    echo $result->getMessage();
    die();
}

$package->addMaintainer('lsmith', 'lead', 'Lukas Kahwe Smith', 'smith@backendmedia.com');
$package->addMaintainer('pgc', 'contributor', 'Paul Cooper', 'pgc@ucecom.com');
$package->addMaintainer('fmk', 'contributor', 'Frank M. Kromann', 'frank@kromann.info');
$package->addMaintainer('quipo', 'contributor', 'Lorenzo Alberton', 'l.alberton@quipo.it');

$package->addDependency('php', '4.2.0', 'ge', 'php', false);
$package->addDependency('PEAR', '1.0b1', 'ge', 'pkg', false);
$package->addDependency('XML_Parser', true, 'has', 'pkg', false);

if (isset($_GET['make']) || (isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'make')) {
    $result = $package->writePackageFile();
} else {
    $result = $package->debugPackageFile();
}

if (PEAR::isError($result)) {
    echo $result->getMessage();
    die();
}
