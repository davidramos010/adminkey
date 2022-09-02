<?php
/**
 * @package   yii2-bootstrap5-dropdown
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2022
 * @version   1.0.2
 */

namespace kartik\bs5dropdown;

use yii\bootstrap5\ButtonDropdown as Yii2ButtonDropdown;

/**
 * ButtonDropdown renders a bootstrap 5.x button dropdown component. It extends the [[Yii2ButtonDropdown]] to render
 * a button dropdown component individually, or with a button group, or split button dropdown. The Krajee
 * enhancement adds multi level submenu dropdown capability to the bootstrap default component.
 *
 * For example,
 *
 * ```php
 * echo ButtonDropdown::widget([
 *     'label' => 'Action',
 *     'dropdown' => [
 *         'items' => [
 *             ['label' => 'DropdownA', 'url' => '/'],
 *             ['label' => 'DropdownB', 'url' => '#'],
 *         ],
 *     ],
 * ]);
 * ```
 * @see https://getbootstrap.com/docs/5.1/components/dropdowns
 * @see https://getbootstrap.com/docs/5.1/forms/input-group/#buttons-with-dropdowns
 * @see https://getbootstrap.com/docs/5.1/components/button-group/#nesting
 */
class ButtonDropdown extends Yii2ButtonDropdown
{
    /**
     * @var string name of a class to use for rendering dropdowns withing this widget. Defaults to [[Dropdown]].
     */
    public $dropdownClass = 'kartik\bs5dropdown\Dropdown';
}
