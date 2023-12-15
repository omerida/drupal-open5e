<?php

namespace Drupal\open5e_import\Service;

class SpellsImporter extends AbstractImporter
{
  protected function getPathRoot(): string
  {
    return 'spells';
  }
}
