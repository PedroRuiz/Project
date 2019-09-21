<?php declare(strict_types=1);

namespace Project\classes;

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
 * Name: classes/contacts.class.php
 *
 * Purpose: Contacts manager
 *
 *
 *
 */


class Contacts extends Database
{

  function get_all(): iterable 
  {
    return $this->do_query([
      'query' => 'SELECT idcontacts,iddoc,first_name,last_name,creation_date,last_update FROM contacts WHERE deletion_date IS NULL',
      'params' => []
    ]);
  }
  
  function get_by_id(iterable $iter): iterable 
  {
    foreach($iter as $id) 
    {
      $response[] = $this->do_query([
        'query' => 'SELECT idcontacts, iddoc, first_name, last_name, creation_date, last_update, deletion_date FROM contacts WHERE idcontacts = :id AND deletion_date IS NULL',
        'params' => [':id' => $id]
      ]);
    }
    return $response;
  }

  function get_by_iddoc(iterable $iter): iterable 
  {
    foreach ($iter as $iddoc) 
    {
      $response[] = $this->do_query([
        'query' => 'SELECT idcontacts, iddoc, first_name, last_name, creation_date, last_update, deletion_date FROM contacts WHERE iddoc = :iddoc AND deletion_date IS NULL',
        'params' => [':iddoc' => (string) $iddoc]
        ]);
    }
    return $response;
  }

  function imperative_delete(iterable $iter): iterable
  {
    foreach ($iter as $id) 
    {
      $response[] = $this->do_sentence([
        'query' => 'DELETE FROM contacts WHERE idcontacts = :id',
        'params' => [':id' => $id]
      ]);
    }
    return $response;
  }

  function delete(iterable $iter): iterable
  {
    foreach ($iter as $id) 
    {
      $response[] = $this->do_sentence([
        'query' => 'UPDATE contacts SET deletion_date = :date WHERE idcontacts = :id',
        'params' => [
          ':id' => $id,
          ':date' => date('Y-m-d h:i:s')
          ]
      ]);
    }
    return $response;
  }

  function new(iterable $iter): iterable
  {
    foreach($iter as $data)
    {
      $response[] = $this->do_sentence([
        'query' => 'INSERT INTO contacts(iddoc,first_name,last_name) VALUES(:iddoc,:first_name,:last_name)',
        'params' => [
          ':iddoc'      =>  strtoupper($data['iddoc']),
          ':first_name' =>  $data['first_name'],
          ':last_name'  =>  $data['last_name']
          ]
      ]);
    }
    return $response;
  }

  function update(iterable $iter): iterable 
  {
    foreach($iter as $data)
    {
      $response[] = $this->do_sentence([
        'query' => 'UPDATE contacts SET iddoc=:iddoc, first_name=:first_name, last_name=:last_name WHERE idcontacts=:idcontacts AND deletion_date IS NULL',
        'params' => [
          ':idcontacts' => $data['idcontacts'],
          ':iddoc'      =>  strtoupper($data['iddoc']),
          ':first_name' =>  $data['first_name'],
          ':last_name'  =>  $data['last_name']
          ]
      ]);
    }
    return $response;
  }

  function search(string $data): iterable 
  {
    return $this->do_query([
      'query' => 'SELECT idcontacts,iddoc,first_name,last_name FROM contacts WHERE MATCH(iddoc,first_name,last_name) AGAINST(:data IN BOOLEAN MODE) AND deletion_date IS NULL',
      'params' => [
        ':data' => $data,
      ]
    ]);
  }

  function bring_back(iterable $iter): iterable 
  {
    foreach($iter as $id)
    {
      $response[] = $this->do_sentence([
        'query' => 'UPDATE contacts SET deletion_date = NULL where idcontacts = :idcontacts',
        'params' => [':idcontacts' => $id  ]
      ]);
    }
    return $response;
  }

  function add_addresses(iterable $iter): iterable 
  {
    foreach($iter as $contact_address)
    {
      $response[] = $this->do_sentence([
        'query' => 'INSERT INTO addresses(street,number,building,zip,province,town,country) VALUES (:street,:number,:building,:zip,:province,:town,:country);
                    INSERT INTO contacts_addresses(idcontacts,idaddresses) values (:idcontacts,LAST_INSERT_ID())',
        'params' => [
          ':idcontacts' => $contact_address['idcontacts'],
          ':street' => $contact_address['street'],
          ':number' => $contact_address['number'],
          ':building' => $contact_address['building'],
          ':zip' => $contact_address['zip'],
          ':province' => $contact_address['province'],
          ':town' => $contact_address['town'],
          ':country' => $contact_address['country']
        ]
      ]);
    }
    return $response;
  }

  function get_addresses(int $idcontact): iterable 
  {
    return $this->do_query([
      'query' => 'SELECT a.* FROM addresses a, contacts_addresses c, contacts x WHERE c.idaddresses = a.idaddresses AND x.idcontacts = c.idcontacts AND x.deletion_date IS NULL AND c.idcontacts = :id',
      'params' => [
        ':id' => $idcontact,
      ]
    ]);
  }

  function add_phones(iterable $iter): iterable 
  {
    foreach($iter as $contact_address)
    {
      $response[] = $this->do_sentence([
        'query' => 'INSERT INTO phones(country_prefix,phone_number,extension,comments) VALUES (:country_prefix,:phone_number,:extension,:comments);
                    INSERT INTO contacts_phones(idcontacts,idphones) values (:idcontacts,LAST_INSERT_ID())',
        'params' => [
          ':idcontacts' => $contact_address['idcontacts'],
          ':country_prefix' => $contact_address['country_prefix'],
          ':phone_number' => $contact_address['phone_number'],
          ':extension' => $contact_address['extension'],
          ':comments' => $contact_address['comments']
        ]
      ]);
    }
    return $response;
  }

  function get_phones(int $idcontact): iterable 
  {
    return $this->do_query([
      'query' => 'SELECT p.* from phones p, contacts_phones x, contacts c where x.idphones = p.idphones and c.idcontacts = x.idcontacts and c.deletion_date is null and x.idcontacts = :id',
      'params' => [
        ':id' => $idcontact,
      ]
    ]);
  }

  function deleted(): iterable 
  {
    return $this->do_query([
      'query' => 'SELECT idcontacts,iddoc,first_name,last_name,creation_date,deletion_date FROM contacts WHERE deletion_date IS NOT NULL',
      'params' => []
    ]);
  }
}