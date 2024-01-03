<?php

namespace Drupal\open5e_import\Service;

class SpellsImporter extends AbstractImporter
{
  protected $targetDir = 'public://open5E/spells';

  protected function getPathRoot(): string
  {
    return 'spells';
  }

  protected function prepareResults(\stdClass $body): \stdClass
  {
    if ($body->results) {
      $body->results = array_map(function($spell) {
        // add a guid field to track uniqueness
        $spell->guid = sprintf("%s/%d/%s", $spell->document__slug, $spell->level_int, $spell->slug);
        // split component string
        $spell->component_list = array_map(
          fn($item) => trim($item),
          explode(',', $spell->components)
        );

        if (!$spell->spell_lists) {
          $spell->spell_lists = array_map(
            function($item) {
              $clean = strtolower(trim($item));
              if ($clean === 'sorceror') {
                $clean = 'sorcerer';
              }
              return $clean;
            },
            explode(',', $spell->dnd_class)
          );
        }
        if ($spell->document__url === 'open5e.com') {
          $spell->document__url = 'http://open5e.com/';
        }
        return $spell;
      }, $body->results);
    }
    return $body;
  }
}
