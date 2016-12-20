<?php

namespace Drupal\Tests\twitter_rules\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\user\Entity\User;

/**
 * Tests integration of twitter_rules with other components.
 *
 * @group rules
 */
class TwitterRulesKernelTest extends EntityKernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['comment', 'node', 'rules', 'typed_data', 'twitter_rules', 'twitter_rules_test'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installSchema('comment', array('comment_entity_statistics'));
    $this->installConfig(['rules', 'twitter_rules_test']);

    $connectorProphecy = $this->prophesize('\Drupal\twitter_rules\TwitterConnector');
    $connectorProphecy->send('Test comment')->shouldBeCalled();
    $this->container->set('twitter_rules.connector', $connectorProphecy->reveal());
  }

  /**
   * Tests the comment validation constraints.
   */
  public function testValidation() {
    // Add a user.
    $user = User::create(array('name' => 'test', 'status' => TRUE));
    $user->save();

    // Add comment type.
    $this->entityManager->getStorage('comment_type')->create(array(
      'id' => 'comment',
      'label' => 'comment',
      'target_entity_type_id' => 'node',
    ))->save();

    // Add comment field to content.
    $this->entityManager->getStorage('field_storage_config')->create(array(
      'entity_type' => 'node',
      'field_name' => 'comment',
      'type' => 'comment',
      'settings' => array(
        'comment_type' => 'comment',
      )
    ))->save();

    // Create a page node type.
    $this->entityManager->getStorage('node_type')->create(array(
      'type' => 'page',
      'name' => 'page',
    ))->save();

    // Add comment field to page content.
    /** @var \Drupal\field\FieldConfigInterface $field */
    $field = $this->entityManager->getStorage('field_config')->create(array(
      'field_name' => 'comment',
      'entity_type' => 'node',
      'bundle' => 'page',
      'label' => 'Comment settings',
    ));
    $field->save();

    $node = $this->entityManager->getStorage('node')->create(array(
      'type' => 'page',
      'title' => 'test',
    ));
    $node->save();

    $comment = $this->entityManager->getStorage('comment')->create(array(
      'entity_id' => $node->id(),
      'entity_type' => 'node',
      'field_name' => 'comment',
      'subject' => 'Test comment',
      'comment_body' => 'Test comment',
    ));
    $comment->save();
  }

}
