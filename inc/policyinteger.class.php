<?php
/**
 * LICENSE
 *
 * Copyright © 2016-2018 Teclib'
 * Copyright © 2010-2018 by the FusionInventory Development Team.
 *
 * This file is part of Flyve MDM Plugin for GLPI.
 *
 * Flyve MDM Plugin for GLPI is a subproject of Flyve MDM. Flyve MDM is a mobile
 * device management software.
 *
 * Flyve MDM Plugin for GLPI is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * Flyve MDM Plugin for GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 * along with Flyve MDM Plugin for GLPI. If not, see http://www.gnu.org/licenses/.
 * ------------------------------------------------------------------------------
 * @copyright Copyright © 2018 Teclib
 * @license   https://www.gnu.org/licenses/agpl.txt AGPLv3+
 * @link      https://github.com/flyve-mdm/glpi-plugin
 * @link      https://flyve-mdm.com/
 * ------------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * @since 0.1.0.33
 */
class PluginFlyvemdmPolicyInteger extends PluginFlyvemdmPolicyBase implements PluginFlyvemdmPolicyInterface
{

   /**
    * @var integer|null $minValue Minimum value allowed for the policy
    */
   protected $minValue = null;
   /**
    * @var integer|null $minValue Maximum value allowed for the policy
    */
   protected $maxValue = null;

   /**
    * PluginFlyvemdmPolicyInteger constructor.
    * @param PluginFlyvemdmPolicy $policy
    */
   public function __construct(PluginFlyvemdmPolicy $policy) {
      parent::__construct($policy);
      $defaultProperties = [
         'min' => null,
         'max' => null,
      ];
      $propertyCollection = $this->jsonDecodeProperties($policy->getField('type_data'),
         $defaultProperties);
      $this->minValue = $propertyCollection['min'];
      $this->maxValue = $propertyCollection['max'];

      $this->symbol = $policy->getField('symbol');
      $this->unicityRequired = ($policy->getField('unicity') != '0');
      $this->group = $policy->getField('group');
   }

   /**
    * @param mixed $value
    * @param mixed $itemtype
    * @param integer $itemId
    * @return bool
    */
   public function integrityCheck($value, $itemtype, $itemId) {
      if ($value === null || (!strval(intval($value)) == strval($value))) {
         return false;
      }

      if ($this->minValue !== null && ($value < $this->minValue)) {
         return false;
      }

      if ($this->maxValue !== null && ($value > $this->maxValue)) {
         return false;
      }
      return true;

   }

   /**
    * @param mixed $value
    * @param mixed $itemtype
    * @param integer $itemId
    * @return array|bool
    */
   public function getBrokerMessage($value, $itemtype, $itemId) {
      if (!$this->integrityCheck($value, $itemtype, $itemId)) {
         return false;
      }
      $array = [
         $this->symbol => $value,
      ];
      return $array;
   }
}
