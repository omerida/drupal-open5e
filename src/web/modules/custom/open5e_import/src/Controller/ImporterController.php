<?php

namespace Drupal\open5e_import\Controller;

use Drupal\Core\Url;

class ImporterController
{
  public function main(): array
  {
    return [
      '#theme' => 'open5e_main',
      '#spellURL' => Url::fromRoute('open5e_import.spells')->toString(),
    ];
  }

  public function spells(): array
  {
    $spells = \Drupal::service('open5e_import.spells_service');
    $pages = $spells->fetch();

    return [
      '#theme' => 'open5e_spells',
      '#pages' => $pages,
    ];
  }
}
