<?php

namespace Drupal\open5e_import\Service;

use Drupal\Core\File\FileSystemInterface;
use GuzzleHttp\Exception\GuzzleException;

abstract class AbstractImporter
{
  protected $baseURL = 'https://api.open5e.com/v1/';
  protected $targetDir = 'public://open5E';
  public function __construct(
    protected \GuzzleHttp\Client $httpClient,
    protected FileSystemInterface $fileSystem,
  ) {
  }

  /**
   * Path after $baseURL from https://api.open5e.com
   * @return string
   */
  abstract protected function getPathRoot(): string;

  /**
   * @throws GuzzleException
   * @throws \JsonException
   */
  public function fetch(): bool
  {
    $targetURL = $this->baseURL . $this->getPathRoot() . '?limit=250';
    $page = 0;
    $nextPage = true;

    if (!$this->fileSystem->prepareDirectory(
        $this->targetDir,
        FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS
    )) {
      // @todo Log an error.
      return FALSE;
    }

    while ($nextPage) {
      $page++;
      $response = $this->httpClient->get($targetURL);
      $body = json_decode($response->getBody(), false, flags: JSON_THROW_ON_ERROR);


      if ($body->results) {
        $this->fileSystem->saveData(json_encode($body), $this->generateFileName($page));
      }

      if ($body->next) {
        $targetURL =  $body->next;
        usleep(300000 + random_int(0, 200000)); // be nice
      } else {
        $nextPage = false;
      }
    }

    return true;
  }

  private function generateFileName(int $page): string
  {
    return $this->targetDir . '/' . $this->getPathRoot() . '-' . $page . '.json';
  }
}
