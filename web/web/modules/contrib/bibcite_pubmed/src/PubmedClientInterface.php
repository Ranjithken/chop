<?php

namespace Drupal\bibcite_pubmed;

/**
 * Define an interface for PubmedClient service.
 */
interface PubmedClientInterface {

  /**
   * Retrieve work metadata by PubMed ID.
   *
   * @param int|array $pmid
   *   PubMed ID to lookup.
   *
   * @return string
   *   Response XML string.
   */
  public function fetch($pmid);

}
