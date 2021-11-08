<?php

require_once 'auth.php';

class AppsDB {

  /**
  *
  * Parse array and encode quotes that break JSON format
  *
  * @param    array  $array The array to convert
  * @return   array  $array The result
  *
  */

  function convertToUTF8 ($array) {
    array_walk_recursive($array, function(&$item, $key){
      if (strpos($item, 'pages/products') === false) {
        $item = str_replace('\'', '&#39;', $item);
      }
    });
    return $array;
  }

  /**
  *
  * Splits JSON into an array of strings each with length equal to max. length for 1 entry in AppsDB
  *
  * @param    object  $object
  * @return   array   $chunks
  *
  */

  function chunkJSON ($json, $length = 242670) {
    $data = json_decode($json, true);
    $dataUTF8 = AppsDB::convertToUTF8($data);
    $result = json_encode($dataUTF8);
    $chunks = str_split($result, $length);
    return $chunks;
  }

  /**
  *
  * Fetches a list of entries from an AppsDB Store
  *
  * @param    array  $store  The Apps DB Store to fetch
  * @return   array  $entries    The entries from the Apps DB Store
  *
  */

  public function getStore ($store) {
    $kirby = kirby();
    $kirby->impersonate('kirby');

    $token = AppsDB::getToken();
    $url = 'https://'.$kirby->option('popcomms.kirby-showpad.domain').'.showpad.biz/api/v3/appsdb/stores/'.$store.'/globals/entries';

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_POSTFIELDS => null,
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ". $token
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }

  /**
  *
  * Populates all entries in an Apps DB Store
  *
  * @param    string  $store The name of the Apps DB Store
  *
  */

  public function populateStore ($store) {
    $kirby = kirby();
    $kirby->impersonate('kirby');

    $json = file_get_contents($kirby->site()->url().'/api/content' . $store, false);
    $chunks = AppsDB::chunkJSON($json);
    $counter = 1;
    $token = AppsDB::getToken();

    foreach ($chunks as $chunk) {
      $value = addslashes($chunk);
      AppsDB::insertEntry($store, $counter, $value);
      $counter = $counter + 1;
    }
  }

  /**
  *
  * Empties all entries in an Apps DB Store
  *
  * @param    string  $store The name of the Apps DB Store
  *
  */

  public function emptyStore ($store) {

    $entries = AppsDB::getStore($store);
    $entries = json_decode($entries, true);

    foreach ($entries as $entry) {
      foreach($entry as $item) {
        $id = $item['id'];
        AppsDB::deleteEntry($store, $id);
      }
    }
  }

  /**
  *
  * Fetches item from an Apps DB Store
  *
  * @param    string $id The id of the element to delete
  * @param    string $store The name of the Apps DB Store
  * @return   array  $array The result
  *
  */

  public function getEntry ($store, $id) {

    $curl = curl_init();
    $url = 'https://' . $kirby->option('popcomms.kirby-showpad.domain') . '.showpad.biz/api/v3/appsdb/stores/' . $store . '/globals/entries/' . $id;

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ". $token ,
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);
    print_r($response);
    curl_close($curl);

  }

  /**
  *
  * Inserts an item into an Apps DB Store
  *
  * @param    string $store The name of the Apps DB Store
  * @param    string $id The id of the element to delete
  * @return   array  $array The result
  *
  */

  public function insertEntry ($store, $id, $value) {
    $curl = curl_init();
    $url = 'https://' . $kirby->option('popcomms.kirby-showpad.domain') . '.showpad.biz/api/v3/appsdb/stores/' . $store . '/globals/entries/' . $id;

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'PUT',
      CURLOPT_POSTFIELDS => '{
        "value": "' . $value . '"
      }',
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ". $token ,
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);
    print_r($response);
    curl_close($curl);
  }

  /**
  *
  * Deletes a single entry from Apps DB
  *
  * @param    string $store The name of the Apps DB Store
  * @param    string $id The id of the element to delete
  * @return   array  $array The result
  *
  */

  public function deleteEntry ($store, $id) {
    $kirby = kirby();
    $kirby->impersonate('kirby');

    $token = AppsDB::getToken();

    $url = 'https://'.$kirby->option('popcomms.kirby-showpad.domain').'.showpad.biz/api/v3/appsdb/stores/' . $store . '/globals/entries/' . $id;

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'DELETE',
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer " . $token,
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    echo $response;
    return 'Record Deleted with id of: '.$id;
  }
}
