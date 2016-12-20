<?php

namespace Drupal\twitter_rules;

use Drupal\Core\Config\ConfigFactory;

/**
 * Class TwitterConnector.
 *
 * @package Drupal\twitter_rules
 */
class TwitterConnector implements TwitterConnectorInterface {

  /**
   * A config object for twitter credentials retrieval.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Twitter handler class.
   *
   * @var \Twitter
   */
  protected $twitter;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   */
  public function __construct(ConfigFactory $configFactory) {
    $this->config = $configFactory->get('twitter_rules.settings');
  }

  /**
   * {@inheritdoc}
   */
  public function setTwitterHandler(\Twitter $twitter) {
    $this->twitter = $twitter;
  }

  /**
   * {@inheritdoc}
   */
  public function send($message) {
    if (!isset($this->twitter)) {
      $this->twitter = new \Twitter(
        $this->config->get('consumer_key'),
        $this->config->get('consumer_secret'),
        $this->config->get('access_token'),
        $this->config->get('access_token_secret')
      );
    }

    $this->twitter->send($message);
  }

}
