<?php

namespace Drupal\cirp_profiles\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\node\Entity\Node;

/**
 * Updates nodes from the queue.
 *
 * @QueueWorker(
 *   id = "cirp_profiles_import_worker",
 *   title = @Translation("Cirp Profiles Import Worker"),
 *   cron = {"time" = 10}
 * )
 */
class CirpProfilesImportWorker extends QueueWorkerBase
{

    /**
     * {@inheritdoc}
     * {$data}
     * This function process the queue's created by profile cron
     * This will update the data for Profiles
     */
    public function processItem($data)
    {
        if($data['nid']) {
            $node = Node::load($data['nid']);
        }
        if ($node) {
            // Perform node update operations here.
            $node->set('title', $data['display_name']);
            $node->body->value = $data['bio'];
            $node->body->format = 'full_html';
            $node->set('field_first_name', $data['drupal_title']);
            $node->set('field_last_name', $data['last_name']);
            $node->field_short_bio->value = $data['short_description'];
            $node->field_short_bio->format = 'full_html';
            $node->set('field_external_url', $data['cta_link']);
            $node->set('field_pubmed_url', $data['pubmed_url']);
            $node->set('field_email', $data['email']);
            $node->set('field_phone_number', $data['phone']);
            $node->field_links_of_interest->value = $data['links_of_interest'];
            $node->field_links_of_interest->format = 'full_html';
            $node->field_education_and_training->value = $data['education_or_training'];
            $node->field_education_and_training->format = 'full_html';
            $node->field_titles_and_academic_titles->value = $data['titles_and_academic_titles'];
            $node->field_titles_and_academic_titles->format = 'full_html';
            $node->field_professional_memberships->value = $data['professional_memberships'];
            $node->field_professional_memberships->format = 'full_html';
            $node->field_professional_awards->value = $data['professional_awards'];
            $node->field_professional_awards->format = 'full_html';
            $node->field_active_grants_contracts->value = $data['active_grants_contracts'];
            $node->field_active_grants_contracts->format = 'full_html';
            $node->save();
            unset($node);
        }
    }
}
