<?php

namespace Drupal\Tests\twitter_rules\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\twitter_rules\TwitterConnector;
use Twitter;

/**
 * @coversDefaultClass \Drupal\twitter_rules\TwitterConnector
 *
 * @group rules
 */
class TwitterConnectorTest extends UnitTestCase {

  /**
   * @covers ::send
   */
  public function testSend() {
    $twitterProphecy = $this->prophesize(Twitter::class);
    $twitterProphecy->send('Test message')->shouldBeCalled();

    $configProphecy = $this->prophesize('\Drupal\Core\Config\ConfigFactory');

    $twitterConnector = new TwitterConnector($configProphecy->reveal());
    $twitterConnector->setTwitterHandler($twitterProphecy->reveal());
    $twitterConnector->send('Test message');
  }

}
