<?php

namespace Drupal\bibcite_pubmed;

use GuzzleHttp\ClientInterface;

/**
 * PubMed client service.
 */
class PubmedClient implements PubmedClientInterface {

  const DATABASE = 'pubmed';

  const URL = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi';

  /**
   * HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Constructor.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client.
   */
  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public function fetch($pmid) {
    if (is_array($pmid)) {
      $pmid = implode(',', $pmid);
    }
    $params = [
      'db' => static::DATABASE,
      'id' => $pmid,
      'retmode' => 'xml',
    ];
    $response = $this->httpClient->request('GET', static::URL, ['query' => $params]);

    return $response->getBody();
  }

}
