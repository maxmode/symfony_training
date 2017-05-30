<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client as Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BatteriesControllerTest extends WebTestCase
{
  public function testStatistics()
  {
    $client = static::createClient();
    $client->followRedirects();

    $crawler = $this->submitForm($client, 'AA', 4);
    $crawler = $this->submitForm($client, 'AAA', 3);
    $crawler = $this->submitForm($client, 'AA', 1);

    $rows = $crawler->filter('tr');
    
    $expectedValues = array(
      // 0 => table headers
      1 => ['type' => 'AA', 'count' => 5],
      2 => ['type' => 'AAA', 'count' => 3]
    );

    foreach ($expectedValues as $rowIndex => $values) {
      $columns = $rows->eq($rowIndex)->filter('td');
      $this->assertEquals($expectedValues[$rowIndex]['type'], $columns->eq(0)->text());
      $this->assertEquals($expectedValues[$rowIndex]['count'], $columns->eq(1)->text());
    }
  }

  public function submitForm(Client $client, String $type, int $count)
  {
    $crawler = $client->request('GET', '/batteries/form/');
    $buttonCrawlerNode = $crawler->selectButton('Submit');
    $form = $buttonCrawlerNode->form();
    $crawler = $client->submit($form, array(
      'appbundle_batterysubmit[type]' => $type,
      'appbundle_batterysubmit[count]' => $count,
      'appbundle_batterysubmit[name]' => 'Test'
    ));
    return $crawler;
  }
}
