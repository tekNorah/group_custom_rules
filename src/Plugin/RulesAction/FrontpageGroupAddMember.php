<?php
/**
 * @file
 * Contains \Drupal\group_custom_rules\Plugin\RulesAction\FrontpageGroupAddMember.
 */
namespace Drupal\group_custom_rules\Plugin\RulesAction;
use Drupal\rules\Core\RulesActionBase;

use Drupal\user\UserInterface;
use Drupal\Core\Url;

/**
 * Provides a 'FrontpageGroupAddMember' action.
 *
 * @RulesAction(
 *  id = "rules_frontpage_group_add_member",
 *  label = @Translation("Add user to Group on Frontpage"),
 *   category = @Translation("Group"),
 *   context = {
 *     "user" = @ContextDefinition("entity:user",
 *       label = @Translation("User"),
 *       description = @Translation("Specifies the user, that should be added to the Group assigned to the Frontpage.")
 *     )
 *   }
 * )
 */
class FrontpageGroupAddMember extends RulesActionBase {

  /**
   * Adds user as member of Group assigned to the Frontpage
   *
   * @param \Drupal\user\UserInterface $account
   *   User object.
   */
  protected function doExecute(UserInterface $account) {
    //Get Node Id for Front Page
    $frontpagepath = \Drupal::config('system.site')->get('page.front');
    $alias = \Drupal::service('path.alias_manager')->getPathByAlias($frontpagepath);
    $params = Url::fromUri("internal:" . $alias)->getRouteParameters();
    $entity_type = key($params);

    // Do nothing if node on front page is not group
    if ($entity_type == 'group') {
      $group = \Drupal::entityTypeManager()->getStorage($entity_type)->load($params[$entity_type]);
      $group->addMember($account);
      $group->save();
    }
  }
}
