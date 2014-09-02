<pre>
<?php
class HTTPException extends Exception {}

class HTTPSClient{
	/** Classe utilitaire pour accéder à l'API

	    Utilise php5-curl (il est donc nécessaire de l'installer).

	    $cert_file     : chemin vers le fichier de certificat client
	    $ca_file       : chemin vers le certificat du serveur oasiswork
	    $cert_password : mot de passe du certificat client
	 */

	private $ch;
	private $base_options;

	public function __construct($cert_file, $ca_file, $cert_password) {
		$this->base_options = array(
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_CAINFO         => $ca_file,
		  CURLOPT_SSLCERT        => $cert_file,
		  CURLOPT_SSLCERTPASSWD  => $cert_password,
		  CURLOPT_HTTPHEADER     => array('Content-Type: application/json')
		);
		$this->ch = curl_init();
	}

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
$ca_file = '/home/jocelyn/dev/munch/etc/ssl/ssl-example-ca-clients.pem';
$cert_file = '/home/jocelyn/dev/munch/etc/ssl/client-1024-test1-cert.pem';
$client_url = 'https://localhost:4243/api/v1/customers/1024/';

// Initialisation du client HTTP
$client = new HTTPSClient($cert_file, $ca_file, '');

// Récupération des informations client
$customer = $client->get($client_url);

// Création d'une campagne
$campaigns_url = 'https://localhost:4243/api/v1/campaigns/';
$campaign = $client->post($campaigns_url, array(
  'name'         => 'Newsletter de Juillet',
  'sender_email' => 'newsletter@example.com',
  'sender_name'  => 'Communication ACME chaussures',
  'tech_contacts'=> 'admins@example.com, communication@example.com',
  'owners'       => 'communication@example.com',
  'customer'     => 1024));


echo "**** Détails de la campagne\n";

print_r($campaign);

// Définition du message de la campagne
$message = $client->put($campaign->message,
                        array('subject' => 'Mon subjet',
                              'html'    => '<h1>Hello world</h1>'));

// Vérification du niveau de spam.
if ($message->is_spam) {
	printf("SPAM ! (score: %d)\n", $message->spam_score);
 } else {
	printf("pas spam (score: %d)\n", $message->spam_score);
}

// Ajout des destinataires individuellement…

$client->post($campaign->mails, array("to"=>"solo@domaine.tld"));


// ou par lot…

$client->post($campaign->mails, array(
                                      array("to"=>"john@domaine.tld"),
                                      array("to"=>"jane@domain.tld"),
                                      array("to"=>"fox@autredomaine.tld")));

// Récupération de tous les destinataires

echo "**** Destinataires\n";
print_r($client->get($campaign->mails));

echo "**** Preview de la campagne\n";
print_r($client->get($campaign->preview));

// Envoi

print_r($client->patch($campaign->url, array("status"=>"sending")));

// Consultation des optout

echo "**** Consultation des opt-outs\n";
print_r($client->get($customer->opt_outs));

?>
</pre>
