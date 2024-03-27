<?php

namespace Drupal\bibcite_pubmed\Encoder;

use SimpleXMLElement;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

/**
 * Pubmed format encoder.
 */
class PubmedEncoder implements DecoderInterface {

  /**
   * The format that this encoder supports.
   *
   * @var array
   */
  protected static $format = 'pubmed';

  /**
   * {@inheritdoc}
   */
  public function supportsDecoding($format) {
    return $format === self::$format;
  }

  /**
   * {@inheritdoc}
   */
  public function decode($data, $format, array $context = []) {
    try {
      $xml = new SimpleXMLElement($data);
    }
    catch (\Exception $e) {
      throw new NotEncodableValueException('Invalid XML data.');
    }

    if (!count($xml->PubmedArticle)) {
      throw new NotEncodableValueException('Invalid XML data: no PubmedArticle elements found.');
    }

    $result = [];
    foreach ($xml->PubmedArticle as $el) {
      $result[] = static::getValues($el);
    }

    return $result;
  }

  /**
   * Extract article properties values from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return array
   *   Associative array of article properties values.
   */
  public static function getValues(SimpleXMLElement $xml) {
    $values = [
      'ArticleTitle' => static::getArticleTitle($xml),
      'PublicationType' => static::getPublicationType($xml),
      'AuthorList' => static::getAuthorList($xml),
      'KeywordList' => static::getKeywordList($xml),
      'Abstract' => static::getAbstract($xml),
      'Year' => static::getYear($xml),
      'JournalTitle' => static::getJournalTitle($xml),
      'Volume' => static::getVolume($xml),
      'Issue' => static::getIssue($xml),
      'Pagination' => static::getPagination($xml),
      'PubDate' => static::getPubDate($xml),
      'Language' => static::getLanguage($xml),
      'ISSN' => static::getIssn($xml),
      'doi' => static::getDoi($xml),
      'ISOAbbreviation' => static::getIsoAbbreviation($xml),
      'PMID' => static::getPmid($xml),
    ];
    $values['url'] = "https://www.ncbi.nlm.nih.gov/pubmed/{$values['PMID']}";

    return $values;
  }

  /**
   * Extract ArticleTitle value from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string
   *   ArticleTitle element value.
   */
  protected static function getArticleTitle(SimpleXMLElement $xml) {
    return (string) $xml->MedlineCitation->Article->ArticleTitle;
  }

  /**
   * Extract PublicationType value from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string
   *   PublicationType element value.
   */
  protected static function getPublicationType(SimpleXMLElement $xml) {
    return (string) $xml->MedlineCitation->Article->PublicationTypeList->PublicationType;
  }

  /**
   * Extract list of Author values from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return array
   *   List of Author elements values.
   */
  protected static function getAuthorList(SimpleXMLElement $xml) {
    $authors = [];

    foreach ($xml->MedlineCitation->Article->AuthorList->Author as $el) {
      $name = '';
      if (isset($el->CollectiveName)) {
        $name = (string) $el->CollectiveName;
        $category = 'corporate_institutional';
      }
      else {
        $category = 'primary';

        if (isset($el->ForeName)) {
          $name = (string) $el->ForeName;
        }
        elseif (isset($el->FirstName)) {
          $name = (string) $el->FirstName;
        }
        elseif (isset($el->Initials)) {
          $name = (string) $el->Initials;
        }

        if (isset($el->LastName)) {
          $name .= ' ';
          $name .= (string) $el->LastName;
        }
      }
      if ($name !== '') {
        $authors[] = ['name' => $name, 'category' => $category];
      }
    }

    return $authors;
  }

  /**
   * Extract list of Keyword values from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string[]
   *   List of Keyword elements values.
   */
  protected static function getKeywordList(SimpleXMLElement $xml) {
    $keywords = [];
    if (isset($xml->MedlineCitation->KeywordList)) {
      foreach ($xml->MedlineCitation->KeywordList->Keyword as $el) {
        $keywords[] = (string) $el;
      }
    }
    return $keywords;
  }

  /**
   * Extract Abstract value from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string
   *   Concatenated AbstractText elements values.
   */
  protected static function getAbstract(SimpleXMLElement $xml) {
    $value = '';
    if (isset($xml->MedlineCitation->Article->Abstract)) {
      foreach ($xml->MedlineCitation->Article->Abstract->AbstractText as $el) {
        $value .= '<p>';
        $attributes = $el->attributes();
        if (isset($attributes['Label'])) {
          $value .= '<b>' . $attributes['Label'] . ': </b>';
        }
        $value .= (string) $el;
        $value .= '</p>';
      }
    }
    return $value;
  }

  /**
   * Extract Year value from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string
   *   Publication year.
   */
  protected static function getYear(SimpleXMLElement $xml) {
    if (!empty($xml->MedlineCitation->Article->Journal->JournalIssue->PubDate->Year)) {
      return (string) $xml->MedlineCitation->Article->Journal->JournalIssue->PubDate->Year;
    }
    else {
      $pubDate = (string) $xml->MedlineCitation->Article->Journal->JournalIssue->PubDate->MedlineDate;
      if (preg_match('/^\d{4,}/', $pubDate)) {
        return substr($pubDate, 0, 4);
      }
      else {
        return (string) $xml->MedlineCitation->Article->ArticleDate->Year;
      }
    }
  }

  /**
   * Extract JournalTitle value from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string
   *   JournalTitle element value.
   */
  protected static function getJournalTitle(SimpleXMLElement $xml) {
    return (string) $xml->MedlineCitation->Article->Journal->Title;
  }

  /**
   * Extract Volume value from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string
   *   Volume element value.
   */
  protected static function getVolume(SimpleXMLElement $xml) {
    return (string) $xml->MedlineCitation->Article->Journal->JournalIssue->Volume;
  }

  /**
   * Extract Issue value from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string
   *   Issue element value.
   */
  protected static function getIssue(SimpleXMLElement $xml) {
    return (string) $xml->MedlineCitation->Article->Journal->JournalIssue->Issue;
  }

  /**
   * Extract Pagination value from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string
   *   Pagination element value.
   */
  protected static function getPagination(SimpleXMLElement $xml) {
    return (string) $xml->MedlineCitation->Article->Pagination->MedlinePgn;
  }

  /**
   * Extract publication month value from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string
   *   PubDate Month element value.
   */
  protected static function getPubMonth(SimpleXMLElement $xml) {
    return (string) $xml->MedlineCitation->Article->Journal->JournalIssue->PubDate->Month;
  }

  /**
   * Extract publication date value from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string
   *   Publication date value in MM/YYYY format.
   */
  protected static function getPubDate(SimpleXMLElement $xml) {
    return date('m', strtotime(static::getPubMonth($xml)))
      . '/'
      . static::getYear($xml);
  }

  /**
   * Extract Language value from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string
   *   Language element value.
   */
  protected static function getLanguage(SimpleXMLElement $xml) {
    return (string) $xml->MedlineCitation->Article->Language;
  }

  /**
   * Extract ISSN value from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string
   *   ISSN element value.
   */
  protected static function getIssn(SimpleXMLElement $xml) {
    return (string) $xml->MedlineCitation->Article->Journal->ISSN;
  }

  /**
   * Extract DOI value from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string
   *   DOI value.
   */
  protected static function getDoi(SimpleXMLElement $xml) {
    $doi = '';
    if (isset($xml->MedlineCitation->Article->ELocationID)) {
      foreach ($xml->MedlineCitation->Article->ELocationID as $uid) {
        // @todo Check EIdType attribute instead?
        if (preg_match('/^10.\d{4,9}\//', $uid)) {
          $doi = (string) $uid;
        }
      }
    }

    return $doi;
  }

  /**
   * Extract ISOAbbreviation value from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string
   *   ISOAbbreviation element value.
   */
  protected static function getIsoAbbreviation(SimpleXMLElement $xml) {
    return (string) $xml->MedlineCitation->Article->Journal->ISOAbbreviation;
  }

  /**
   * Extract PMID value from PubMed XML.
   *
   * @param \SimpleXMLElement $xml
   *   PubMed XML PubmedArticle element.
   *
   * @return string
   *   PMID element value.
   */
  protected static function getPmid(SimpleXMLElement $xml) {
    return (string) $xml->MedlineCitation->PMID;
  }

}
