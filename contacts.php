<?php declare(strict_types=1); 

namespace Project;

/**
 * @author Pedro Ruiz Hidalgo <ruizhidalgopedro@gmail.com>
 * @pedroruizhidalg
 *
 * coding the world since 1983
 *
 * date 2019-09-19
 *
 * @package Project
 *
 * Name: contacts.php
 *
 * Purpose: Starts the Project
 *
 *
 *
 */
 system('figlet Contacts');

 const CLASSES = 'classes';

/**
 * imports all files *.system.php files
 */

foreach( glob(  __DIR__ . DIRECTORY_SEPARATOR . CLASSES . DIRECTORY_SEPARATOR . "*.system.php") as $file ) 
  include  $file;
  

/**
 * imports all classes *.class.php
 */

foreach( glob(  __DIR__ . DIRECTORY_SEPARATOR . CLASSES . DIRECTORY_SEPARATOR . "*.class.php") as $file ) 
  include $file;
  

require_once 'Console/Table.php';


$contactos = new classes\Contacts;

if(! isset($argv[1]))
{
  echo "then nothing to do, none?\n";
  exit(66);
}

switch($argv[1])
{
  case 'all':
  case 'getAll':
  case 'get_all':
    echo "A L L   C O N T A C T S\n"; 
    echo show($contactos->get_all());
    break;
  
  case 'iddoc':
    if( !isset($argv[2]))
    {
      echo "what iddoc you mean?\n";
      exit(66);
    }
    
    $idstring = (string) $argv[2];
    echo "C O N T A C T    I D:   $id\n";
    $contact = $contactos->get_by_iddoc([$idstring])[0];
    echo show($contact);
    $id =(int) $contact['result'][0]['idcontacts'];
    echo "A D D R E S S E S for   $id\n";
    echo show($contactos->get_addresses($id));
    echo "P H O N E S   F O R     $id\n";
    echo show($contactos->get_phones($id));
    break;
  
  case 'get_id':
  case 'id':
    if( !isset($argv[2]))
    {
      echo "what id you mean?\n";
      exit(66);
    }
    $id =(int) $argv[2];
    echo "C O N T A C T    I D:   $id\n";
    echo show($contactos->get_by_id([$id])[0]);
    echo "A D D R E S S E S for   $id\n";
    echo show($contactos->get_addresses($id));
    echo "P H O N E S   F O R     $id\n";
    echo show($contactos->get_phones($id));
    break;
  case 'new':
    if( !isset($argv[2]))
    {
      echo "what is new?\n";
      exit(66);
    }
    $new = (array) json_decode($argv[2]);
    $id = $contactos->new([$new])[0]['last_insert_id']; 
    
    echo show($contactos->get_by_id([$id])[0]);
    break;
  case 'update':

    if( !isset($argv[2]))
    {
      echo "what exactly do I have to modify?\n";
      exit(66);
    }
    
    $update = (array) json_decode($argv[2]);
    $contactos->update([$update]);
    echo show($contactos->get_by_id([$update['idcontacts']])[0]);
    break;
  case 'delete':
    if( !isset($argv[2]))
    {
      echo "Why I have nothing to delete?\n";
      exit(66);
    }
    $id = $argv[2];
    $contactos->delete([$argv[2]]);
    echo "DONE!\n";
    break;

  case 'bring_back':
  case 'resurrect':
    if( !isset($argv[2]))
    {
      echo "?\n";
      exit(66);
    }
    $id =(int) $argv[2];
    echo "R E S U R R E C T I O N   O F :   $id\n";
    $contactos->bring_back([$id])[0];
    echo "D O N E !   $id\n";
    break;

  case 'search':
    if( !isset($argv[2]))
    {
      echo "?\n";
      exit(66);
    }
    $search =(string) $argv[2];
    echo "S E A R C H I N G:   $search\n";
    $search = $contactos->search($search);

    echo show($search);    
    break;

  case 'getmodel':
  case 'model':
  case 'getmodels';
    echo "M O D E L S\n";
    echo json_encode(['idcontacts'=>'','iddoc'=>'','first_name'=>'','last_name'=>''])."\n";
    echo json_encode(['iddoc'=>'','first_name'=>'','last_name'=>''])."\n";
    echo "\n";
    break;

  case 'deleted':
    echo "D E L E T E  D\n"; 
    echo show($contactos->deleted());
    break;
    
}
exit(0);
/*

GET ALL

show( $contactos->get_all() );
*/

/*

GET BY ID

echo json_encode($contactos->get_by_id([1,11]));
*/

/*

GET BY IDDOC

echo json_encode( $contactos->get_by_iddoc(['','idcontacto2']));
*/

/*

DELETE

echo json_encode( $contactos->delete([2,13]) );
*/

/*

NEW

$contactos->new([
  ['iddoc' => '62025331N','first_name'=>'Rodrigo','last_name'=>'Zapata Puente'],
  ['iddoc' => '00198543F','first_name'=>'Purificación','last_name'=>'Ramos Ortiz'],
  ['iddoc' => '42490860Q','first_name'=>'Joana','last_name'=>'Villalba Cobos'],
  ['iddoc' => '42490860Q','first_name'=>'Juan Pedro','last_name'=>'Coll Ruiz'],
  ]);
*/

/*

UPDATE (needed idcontacts)

var_dump($contactos->update([
  ['idcontacts'=> '13', 'iddoc' => '13681146X','first_name'=>'Lorenzo','last_name'=>'Alcaide Rojo'],
  ['idcontacts'=> '12', 'iddoc' => '13681146X','first_name'=>'Germán','last_name'=>'Garzón Ruiz'],
  ]));
*/

/*
 
SEARCH MATCH-AGAINST

var_dump( $contactos->search('13681146X') );
*/

/*

BRING BACK (to recover deleted)

$contactos->bring_back([2]);
*/

/*

ADD ADDRESSES

$contactos->add_addresses([
  ['idcontacts' => 2, 'street'=>'RAMBLA DE ESPAÑA','number'=>29,'building'=>'2º A','province'=>'Zaragoza','town'=>'LOBERA DE ONSELLA','country'=>'España','zip'=>''],
  ['idcontacts' => 2, 'street'=>'CARRERA IGLESIA','number'=>23,'building'=>'única casa','province'=>'Cuenca','town'=>'HUERTA DEL MARQUESADO','country'=>'España','zip'=>''],
  ]);
*/

/*

GET CONTACTS ADDRESSES

var_dump( $contactos->get_addresses(12) );
*/

/*
ADD PHONES

$contactos->add_phones([
  ['idcontacts'=>2,'country_prefix'=>'+34','phone_number'=>'123456789','extension'=>'4185','comments'=>'departamento de compras'],
  ['idcontacts'=>2,'country_prefix'=>'+34','phone_number'=>'689774589','extension'=>'4180','comments'=>'almacén'],
  ['idcontacts'=>2,'country_prefix'=>'','phone_number'=>'578545961','extension'=>'','comments'=>'particular']
  ]);
*/

/*
GET PHONES

show($contactos->get_by_id([15])[0]);
show($contactos->get_addresses(15));
show($contactos->get_phones(15));
*/


function show(iterable $data)
{
  if(empty($data['result'])) return "[No content]\n";
  $tbl = new \Console_Table();
  $tbl->setHeaders(array_keys($data['result'][0]));
  foreach( $data['result'] as $contact)
  {
    $tbl->addRow($contact);
  }
  return $tbl->getTable();
}
