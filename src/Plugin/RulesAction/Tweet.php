<?php

namespace Drupal\twitter_rules\Plugin\RulesAction;

use Drupal;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\rules\Core\RulesActionBase;
use Drupal\twitter_rules\TwitterConnectorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides "Tweet" rules action.
 *
 * @RulesAction(
 *   id = "rules_tweet",
 *   label = @Translation("Tweet"),
 *   category = @Translation("Twitter"),
 *   context = {
 *     "message" = @ContextDefinition("string",
 *       label = @Translation("Message"),
 *       description = @Translation("The email's message body."),
 *     ),
 *   }
 * )
 */
class Tweet extends RulesActionBase implements ContainerFactoryPluginInterface {

  /**
   * The logger channel the action will write log messages to.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Twitter connector to send tweets.
   *
   * @var \Drupal\twitter_rules\TwitterConnectorInterface
   */
  protected $connector;

  /**
   * Constructs a SendEmail object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\twitter_rules\Plugin\RulesAction\LoggerInterface|\Psr\Log\LoggerInterface $logger
   *   The alias storage service.
   * @param \Drupal\twitter_rules\TwitterConnectorInterface $connector
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerInterface $logger, TwitterConnectorInterface $connector) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->logger = $logger;
    $this->connector = $connector;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory')->get('rules'),
      $container->get('twitter_rules.connector')
    );
  }

  /**
   * Send a system email.
   *
   * @param string $message
   *   Tweet text.
   */
  protected function doExecute($message) {
    try {
      $this->connector->send($message);
      $this->logger->notice('Tweet successfully sent.');
    } catch (\TwitterException $exception) {
      $this->logger->error('Error sending tweet: %message', $exception->getMessage());
    }
  }

}
