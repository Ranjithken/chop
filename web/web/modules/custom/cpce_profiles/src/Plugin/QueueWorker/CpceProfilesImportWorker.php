<?php

namespace Drupal\cpce_profiles\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\node\Entity\Node;

/**
 * Updates nodes from the queue.
 *
 * @QueueWorker(
 *   id = "cpce_profiles_import_worker",
 *   title = @Translation("Cpce Profiles Import Worker"),
 *   cron = {"time" = 10}
 * )
 */
class CpceProfilesImportWorker extends QueueWorkerBase
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
            $node->set('field_team_member_external', $data['cta_link']);
            $node->set('field_pubmed_url', $data['pubmed_url']);
            $node->set('field_email', $data['email']);
            $node->field_education_and_training->value = $data['education_or_training'];
            $node->field_education_and_training->format = 'full_html';
            $node->field_titles_and_academic_titles->value = $data['titles_and_academic_titles'];
            $node->field_titles_and_academic_titles->format = 'full_html';
            $node->field_active_grants_contracts->value = $data['active_grants_contracts'];
            $node->field_active_grants_contracts->format = 'full_html';
            unset($node);
        }
    }
}
