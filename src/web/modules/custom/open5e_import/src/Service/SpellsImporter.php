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
        $spell->guid = sprintf("%s/%d/%s", $spell->document__slug, $spell->level_int, $spell->slug);
        return $spell;
      }, $body->results);
    }
    return $body;
  }
}
