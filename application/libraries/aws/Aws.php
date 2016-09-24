<?php
/**
 * Clase para la manipulacion de datos con el web service AWS (Amazon Web Services)
 */
use Aws\S3\S3Client;
use Aws\CommandPool;

class Aws{


	/**
	 * Instancia la clase AWS
	 */
	function __construct() {
		
		require "aws-autoloader.php";
		
		$this->bucket = 'musicapp-001';

		// Instantiate the S3 client with your AWS credentials
		$this->client = S3Client::factory(array(
			'includes' => array('_aws'),
			'credentials' => array(
				'key'    => 'AKIAIBWPAGTSSFAZMXHQ',
				'secret' => 'skoYt3YZFLb9TdoKdY/tysNsLAlJakFy/TFonDXi',
			),
			'region' => 'us-west-2',
			'version' => 'latest'
		));



// We can poll the object until it is accessible
// $client->waitUntil('ObjectExists', array(
//     'Bucket' => $bucket,
//     'Key'    => 'data_from_file.txt'
// ));

// $result = $client->deleteObject(array(
//     // Bucket is required
//     'Bucket' => 'musicapp-001',
//     // Key is required
//     'Key' => 'fotos/artistas/Selección_001.png'
// ));

	}

	/**
	 * Sube el bache de archivos al repositorio de AWS.
	 * @array bache de archivos
	 */
	public function upload_batch($file_batch){
		foreach ($file_batch as $file){
			$commands[] = $this->client->getCommand('PutObject', array(
				'Bucket'     => $this->bucket,
				'Key'        => $file['key'],
				'SourceFile' => $file['sourcefile'],
			    'ACL' => 'public-read',
			    'ContentType' => $file['contenttype']
			));	
		}

		$pool = new CommandPool($this->client, $commands);

		// Initiate the pool transfers
		$promise = $pool->promise();

		// Force the pool to complete synchronously
		try {
		    $result = $promise->wait();
		} catch (AwsException $e) {
		    echo('error');
		}
	}
	
	/**
	 * Sube el archivo al repositorio de AWS.
	 * @string clave del archivo
	 * @string ruta del archivo a subir
	 * @array  metadata
	 */
	public function upload_file($key, $sourcefile, $metadata = []){
		return $this->client->putObject(array(
			'Bucket'     => $this->bucket,
			'Key'        => $key,
			'SourceFile' => $sourcefile,
			'Metadata'   => $metadata
		));
	}
}