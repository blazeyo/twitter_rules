<?php

namespace Drupal\twitter_rules;

/**
 * Interface TwitterConnectorInterface.
 *
 * @package Drupal\twitter_rules
 */
interface TwitterConnectorInterface {

  /**
   * Sets Twitter handler.
   *
   * @param \Twitter $twitter
   */
  public function setTwitterHandler(\Twitter $twitter);

  /**
   * Publishes message as a tweet.
   *
   * @param string $message
   *   Tweet text.
   * @throws \TwitterException
   */
  public function send($message);

}
