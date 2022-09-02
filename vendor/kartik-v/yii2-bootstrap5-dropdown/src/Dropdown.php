<?php
/**
 * @package   yii2-bootstrap5-dropdown
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2022
 * @version   1.0.2
 */

namespace kartik\bs5dropdown;

use Exception;
use yii\base\InvalidConfigException;
use yii\bootstrap5\Dropdown as Yii2Dropdown;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;

/**
 * Dropdown renders a Bootstrap 4.x dropdown menu component. This widget extends the default bootstrap [[Yii2Dropdown]]
 * widget to include nested submenu behavior and styling.
 *
 * For example,
 *
 * ~~~
 * <div class="dropdown">
 *     <?php
 *         echo \yii\helpers\Html::button('Dropdown Button', [
 *             'id' => 'dropdownMenuButton',
 *             'class' => 'btn btn-secondary dropdown-toggle'
 *             'data-toggle' => 'dropdown',
 *             'aria-haspopup' => 'true',
 *             'aria-expanded' => 'false'
 *         ]);
 *         echo Dropdown::widget([
 *             'items' => [
 *                 ['label' => 'Section 1', 'url' => '/'],
 *                 ['label' => 'Section 2', 'url' => '#'],
 *                 [
 *                      'label' => 'Section 3',
 *                      'items' => [
 *                          ['label' => 'Section 3.1', 'url' => '/'],
 *                          ['label' => 'Section 3.2', 'url' => '#'],
 *                          [
 *                              'label' => 'Section 3.3',
 *                              'items' => [
 *                                  ['label' => 'Section 3.3.1', 'url' => '/'],
 *                                  ['label' => 'Section 3.3.2', 'url' => '#'],
 *                              ],
 *                          ],
 *                      ],
 *                  ],
 *             ],
 *             'options' => ['aria-labelledby' => 'dropdownMenuButton']
 *         ]);
 *     ?>
 * </div>
 * ~~~
 *
 * @see https://getbootstrap.com/docs/5.1/components/dropdowns
 */
class Dropdown extends Yii2Dropdown
{
    /**
     * @inheritdoc
     */
    public function run(): string
    {
        DropdownAsset::register($this->getView());

        return parent::run();
    }

    /**
     * Renders menu items for the dropdown with ability for multi level submenu nesting.
     *
     * @param  array  $items  the menu items to be rendered
     * @param  array  $options  the container HTML attributes
     * @return string the rendered result.
     * @throws InvalidConfigException if the label option is not specified in one of the items.
     * @throws Exception
     */
    protected function renderItems(array $items, array $options = []): string
    {
        $lines = [];
        foreach ($items as $item) {
            if (is_string($item)) {
                $lines[] = ($item === '-')
                    ? Html::tag('hr', '', ['class' => 'dropdown-divider'])
                    : $item;
                continue;
            }
            if (isset($item['visible']) && !$item['visible']) {
                continue;
            }
            if (!array_key_exists('label', $item)) {
                throw new InvalidConfigException("The 'label' option is required.");
            }
            /** @noinspection PhpIssetCanBeReplacedWithCoalesceInspection */
            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $itemOptions = ArrayHelper::getValue($item, 'options', []);
            $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
            $active = ArrayHelper::getValue($item, 'active', false);
            $disabled = ArrayHelper::getValue($item, 'disabled', false);

            Html::addCssClass($linkOptions, ['widget' => 'dropdown-item']);
            if ($disabled) {
                ArrayHelper::setValue($linkOptions, 'tabindex', '-1');
                ArrayHelper::setValue($linkOptions, 'aria-disabled', 'true');
                Html::addCssClass($linkOptions, ['disable' => 'disabled']);
            } elseif ($active) {
                ArrayHelper::setValue($linkOptions, 'aria-current', 'true');
                Html::addCssClass($linkOptions, ['activate' => 'active']);
            }

            $url = array_key_exists('url', $item) ? $item['url'] : null;
            if (empty($item['items'])) {
                if ($url === null) {
                    $content = Html::tag('h6', $label, ['class' => 'dropdown-header']);
                } else {
                    $content = Html::a($label, $url, $linkOptions);
                }
                $lines[] = $content;
            } else {
                $submenuOptions = $this->submenuOptions;
                if (isset($item['submenuOptions'])) {
                    $submenuOptions = array_merge($submenuOptions, $item['submenuOptions']);
                }
                Html::addCssClass($submenuOptions, ['widget' => 'dropdown-submenu dropdown-menu']);
                Html::addCssClass($linkOptions, ['toggle' => 'dropdown-toggle']);

                $lines[] = Html::beginTag('li',
                    array_merge_recursive(['class' => ['dropdown'], 'aria-expanded' => 'false'], $itemOptions));
                $lines[] = Html::a($label, $url, array_merge([
                    'data-bs-toggle' => 'dropdown',
                    'aria-expanded' => 'false',
                    'role' => 'button',
                ], $linkOptions));
                $lines[] = static::widget([
                    'items' => $item['items'],
                    'options' => $submenuOptions,
                    'submenuOptions' => $submenuOptions,
                    'encodeLabels' => $this->encodeLabels,
                ]);
                $lines[] = Html::endTag('li');
            }
        }

        return Html::tag('ul', implode("\n", $lines), $options);
    }
}
