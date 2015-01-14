<?php
class HTTPException extends Exception {}

class HTTPClient{
	/** Classe utilitaire pour accéder à l'API

	    Utilise php5-curl (il est donc nécessaire de l'installer).

	    $cert_file     : chemin vers le fichier de certificat client
	    $ca_file       : chemin vers le certificat du serveur oasiswork
	 */

	private $ch;
	private $base_options;

	public function __construct($api_key) {
		$this->base_options = array(
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_USERPWD        => 'api:'.$api_key,
		  CURLOPT_HTTPHEADER     => array('Content-Type: application/json')
		);
		$this->ch = curl_init();
	}

	/** Handles an HTTP response, throws exception if Error
	 *
	 * @param $ok_statuses an array of acceptable statuses, if the return
	 *                     code is not within this list, an exception is
	 *                     raised.
	 * @param $out         the output, as string
	 * @returns            a structured PHP object (decoded from JSON)
	 **/
	private function handle_return($out, $ok_statuses) {
		if ($out === false) {
			throw new HTTPException(curl_error($this->ch));
		} else {
			$http_status = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
			if (! in_array($http_status, $ok_statuses)) {
				throw new HTTPException('HTTP '.$http_status.': '.$out);
			}
			return json_decode($out);
		}
	}

	/** Issue a request to the api
	 *
	 * @param $reqtype the HTTP verb (GET, PUT...)
	 * @param $data    relevant for PUT/PATCH/POST : data, as structured PHP
	 *                 object
	 */
	public function req($reqtype, $url, $data=NULL) {
		$req_opts = array(CURLOPT_URL            => $url,
		                  CURLOPT_CUSTOMREQUEST  => $reqtype,
		                  CURLOPT_POSTFIELDS     => json_encode($data));
		curl_setopt_array($this->ch, $req_opts + $this->base_options);
		$out = curl_exec($this->ch);
		return $this->handle_return($out, array(200, 201));
	}

	// shortcuts to HTTP methods
	public function delete($url)       {return $this->req('DELETE', $url);}
	public function get($url)          {return $this->req('GET', $url);}
	public function patch($url, $data) {return $this->req('PATCH', $url, $data);}
	public function post($url, $data)  {return $this->req('POST', $url, $data);}
	public function put($url, $data)   {return $this->req('PUT', $url, $data);}
}


/************************
 * Déroulé de l'exemple *
 ************************/

// Paramètres

$base_url = 'https://api.munchmail.net/api/v1';
$client_url = $base_url.'/customers/42/';
$api_key = 'key-xxxxxxxxxxxxx'

// Initialisation du client HTTP
$client = new HTTPClient($api_key);

// Récupération des informations client
$customer = $client->get($client_url);

echo "**** Ajout d'un domaine\n";

$domains_url = $base_url.'/domains/';

try {
  $domain = $client->post($domains_url, array('name'=> 'sandbox.munchmail.net'));
  if (($domain->spf_status == 'ok') &&
      ($domain->dkim_status == 'ok') &&
      ($domain->mx_status == 'ok')) {
      print_r($domain);
    printf('Domaine %s bien configuré\n');
  } else {
    printf('Domaine %s mal configuré\n');
    die();
  }

} catch (HTTPException $e) {
  echo 'Error creating the domain, maybe it already exists';
}




echo "**** Création d'un message\n";

$messages_url = $base_url.'/messages/';
$message = $client->post($messages_url, array(
  'name'         => 'Newsletter de Juillet',
  'sender_email' => 'newsletter@sandbox.example.com',
  'sender_name'  => 'Communication ACME chaussures',
  'subject'      =>  "Tu peux faire tout ce que tu veux",
  'html'  => "<h1>Mais ne marche pas sur mes chaussures en suédine bleue</h1>",
));


print_r($message);

// Vérification du niveau de spam.
if ($message->is_spam) {
	printf("SPAM ! (score: %d)\n", $message->spam_score);
 } else {
	printf("pas spam (score: %d)\n", $message->spam_score);
}

// Ajout des destinataires individuellement…
$mails_url = $base_url.'/mails/';

$client->post($mails_url, array("to"=>"solo@domaine.tld",
				"message"=>$message->url));

// ou par lot…

$client->post($mails_url, array(
  array("to"=>"john@domaine.tld", "message"=> $message->url),
  array("to"=>"jane@domain.tld", "message"=> $message->url),
  array("to"=>"fox@autredomaine.tld", "message"=> $message->url)));

// Récupération de tous les destinataires

echo "**** Destinataires\n";
print_r($client->get($message->_links->mails->href));

echo "**** Preview du message\n";
print_r($client->get($message->_links->preview->href));

// Envoi

print_r($client->patch($message->url, array("status"=>"sending")));

// Consultation des optout

echo "**** Consultation des opt-outs\n";
print_r($client->get($customer->_links->opt_outs->href));

?>
