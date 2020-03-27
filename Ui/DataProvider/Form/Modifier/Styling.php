<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://www.magezon.com/license.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://www.magezon.com for more information.
 *
 * @category  BlueFormBuilder
 * @package   BlueFormBuilder_Core
 * @copyright Copyright (C) 2019 Magezon (https://www.magezon.com)
 */

namespace BlueFormBuilder\Core\Ui\DataProvider\Form\Modifier;

use Magento\Ui\Component\Form\Fieldset;
use Magezon\UiBuilder\Data\Form\Element\AbstractElement;
use Magezon\UiBuilder\Data\Form\Element\Factory;
use Magezon\UiBuilder\Data\Form\Element\CollectionFactory;

class Styling extends AbstractModifier
{
    const GROUP_STYLING_NAME               = 'styling';
    const GROUP_STYLING_DEFAULT_SORT_ORDER = 300;
    const FIELD_SHADOW                     = 'shadow';
    const FIELD_CUSTOM_CLASS               = 'custom_class';
    const FIELD_CUSTOM_CSS                 = 'custom_css';

    /**
     * @var Factory
     */
    protected $_factoryElement;

    /**
     * @var CollectionFactory
     */
    protected $_factoryCollection;

    /**
     * @var \Magezon\UiBuilder\Data\Form\Element\Text
     */
    protected $text;

    /**
     * @param Factory                                      $factoryElement    
     * @param CollectionFactory                            $factoryCollection 
     * @param \Magento\Framework\Registry                  $registry          
     * @param \Magezon\UiBuilder\Data\Form\Element\Text $text              
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        \Magento\Framework\Registry $registry,
        \Magezon\UiBuilder\Data\Form\Element\Text $text
    ) {
        parent::__construct($factoryElement, $factoryCollection, $registry);
        $this->text = $text;
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;

        // $this->prepareChildren();

        // $this->createStylingPanel();

        return $this->meta;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        if (isset($data['simply'])) {
            $data['simply'] = (int)$data['simply'];
        }
        return $data;
    }

    /**
     * Create Editor panel
     *
     * @return $this
     * @since 101.0.0
     */
    protected function createStylingPanel()
    {
        $this->meta = array_replace_recursive(
            $this->meta,
            [
                static::GROUP_STYLING_NAME => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label'                           => __('Styling'),
                                'componentType'                   => Fieldset::NAME,
                                'collapsible'                     => true,
                                'initializeFieldsetDataByDefault' => false,
                                'sortOrder'                       => static::GROUP_STYLING_DEFAULT_SORT_ORDER,
                                'additionalClasses'               => 'bfb-styling',
                                'template'                        => 'BlueFormBuilder_Core/form/edit/styling',
                                'dataScope'                       => 'data'
                            ]
                        ]
                    ],
                    'children' => $this->getChildren()
                ]
            ]
        );
        return $this;
    }

    /**
     * @return \Magezon\UiBuilder\Data\Form\Element\Fieldset
     */
    public function prepareChildren()
    {
        $this->addChildren(
            AbstractElement::FIELD_SIMPLY,
            'checkbox',
            [
                'sortOrder'   => 0,
                'displayArea' => 'simply',
                'component'   => 'BlueFormBuilder_Core/js/modal/element/design-simply',
                'links'       => [
                    AbstractElement::FIELD_MARGIN_LEFT  => '${ $.provider }:${ $.parentScope }.' . AbstractElement::FIELD_MARGIN_LEFT . ':changed',
                    AbstractElement::FIELD_BORDER_LEFT  => '${ $.provider }:${ $.parentScope }.' . AbstractElement::FIELD_BORDER_LEFT . ':changed',
                    AbstractElement::FIELD_PADDING_LEFT => '${ $.provider }:${ $.parentScope }.' . AbstractElement::FIELD_PADDING_LEFT . ':changed'
                ],
                'listens' => [
                    AbstractElement::FIELD_MARGIN_LEFT  => 'onMarginChanged',
                    AbstractElement::FIELD_BORDER_LEFT  => 'onBorderChanged',
                    AbstractElement::FIELD_PADDING_LEFT => 'onPaddingChanged'
                ]
            ]
        );

        // MARGIN
        $this->addChildren(
            AbstractElement::FIELD_MARGIN_TOP,
            'text',
            [
                'sortOrder'         => 100,
                'displayArea'       => 'margin',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-top',
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . AbstractElement::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_MARGIN_RIGHT,
            'text',
            [
                'sortOrder'         => 200,
                'displayArea'       => 'margin',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-right',
                'value'             => 'auto',
                'imports'           => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . AbstractElement::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_MARGIN_BOTTOM,
            'text',
            [
                'default'           => 15,
                'sortOrder'         => 300,
                'displayArea'       => 'margin',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-bottom',
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . AbstractElement::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_MARGIN_LEFT,
            'text',
            [
                'sortOrder'         => 400,
                'displayArea'       => 'margin',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-left',
                'value'             => 'auto'
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_MARGIN_UNIT,
            'select',
            [
                'sortOrder'            => 500,
                'displayArea'          => 'margin-unit',
                'additionalClasses'    => 'bfb-design-margin-unit',
                'options'              => $this->text->getUnit(),
                'selectedPlaceholders' => false,
                'value'              => 'px'
            ]
        );

        // BORDER
        $this->addChildren(
            AbstractElement::FIELD_BORDER_TOP,
            'text',
            [
                'sortOrder'         => 100,
                'displayArea'       => 'border',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-top',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ],
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . AbstractElement::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_BORDER_RIGHT,
            'text',
            [
                'sortOrder'         => 200,
                'displayArea'       => 'border',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-right',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ],
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . AbstractElement::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_BORDER_BOTTOM,
            'text',
            [
                'sortOrder'         => 300,
                'displayArea'       => 'border',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-bottom',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ],
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . AbstractElement::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_BORDER_LEFT,
            'text',
            [
                'sortOrder'         => 400,
                'displayArea'       => 'border',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-left',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_BORDER_UNIT,
            'select',
            [
                'sortOrder'            => 500,
                'displayArea'          => 'border-unit',
                'additionalClasses'    => 'bfb-design-border-unit',
                'options'              => $this->text->getBorderUnit(),
                'selectedPlaceholders' => false,
                'value'              => 'px'
            ]
        );

        // PADDING
        $this->addChildren(
            AbstractElement::FIELD_PADDING_TOP,
            'text',
            [
                'sortOrder'         => 100,
                'displayArea'       => 'padding',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-top',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ],
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . AbstractElement::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_PADDING_RIGHT,
            'text',
            [
                'sortOrder'         => 200,
                'displayArea'       => 'padding',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-right',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ],
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . AbstractElement::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_PADDING_BOTTOM,
            'text',
            [
                'sortOrder'         => 300,
                'displayArea'       => 'padding',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-bottom',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ],
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . AbstractElement::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_PADDING_LEFT,
            'text',
            [
                'sortOrder'         => 400,
                'displayArea'       => 'padding',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-left',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_PADDING_UNIT,
            'select',
            [
                'sortOrder'            => 500,
                'displayArea'          => 'padding-unit',
                'additionalClasses'    => 'bfb-design-padding-unit',
                'options'              => $this->text->getUnit(),
                'selectedPlaceholders' => false,
                'value'                => 'px'
            ]
        );

        $this->addChildren(
            self::FIELD_SHADOW,
            'boolean',
            [
                'label'       => __('Enable Shadow'),
                'sortOrder'   => 50,
                'default'     => 1,
                'displayArea' => 'right'
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_WIDTH,
            'text',
            [
                'label'          => __('Width'),
                'sortOrder'      => 100,
                'displayArea'    => 'right',
                'additionalInfo' => 'Ex: 500px, 80%,etc'
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_BORDER_COLOR,
            'text',
            [
                'label'             => __('Border Color'),
                'sortOrder'         => 200,
                'displayArea'       => 'right',
                'additionalClasses' => 'minicolors'
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_BORDER_STYLE,
            'select',
            [
                'label'                => __('Border Style'),
                'sortOrder'            => 300,
                'displayArea'          => 'right',
                'options'              => $this->text->getBorderStyle(),
                'selectedPlaceholders' => [
                    'defaultPlaceholder' => __('Theme Default')
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_BACKGROUND_COLOR,
            'text',
            [
                'label'             => __('Background Color'),
                'sortOrder'         => 400,
                'displayArea'       => 'right',
                'additionalClasses' => 'minicolors'
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_BACKGROUND_IMAGE,
            'image',
            [
                'label'        => __('Background Image'),
                'sortOrder'    => 500,
                'displayArea'  => 'right',
                'labelVisible' => false
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_BACKGROUND_POSITION,
            'text',
            [
                'label'       => __('Background Position'),
                'sortOrder'   => 600,
                'displayArea' => 'right',
                'value'       => 'center'
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_BACKGROUND_STYLE,
            'select',
            [
                'label'                => __('Background Style'),
                'sortOrder'            => 700,
                'displayArea'          => 'right',
                'options'              => $this->text->getBackgroundStyle(),
                'value'                => 'no-repeat',
                'selectedPlaceholders' => [
                    'defaultPlaceholder' => __('Theme Default')
                ]
            ]
        );

        // BORDER RADIUS
        $this->addChildren(
            AbstractElement::FIELD_BORDER_RADIUS_TOP_LEFT,
            'text',
            [
                'sortOrder'         => 100,
                'displayArea'       => 'borderRadius',
                'additionalClasses' => 'bfb-design-top bfb-design-border-radius-top',
                'placeholder'       => '-',
                'notice'            => __('Border Radius'),
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_BORDER_RADIUS_TOP_RIGHT,
            'text',
            [
                'sortOrder'         => 200,
                'displayArea'       => 'borderRadius',
                'additionalClasses' => 'bfb-design-right',
                'placeholder'       => '-',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_BORDER_RADIUS_BOTTOM_RIGHT,
            'text',
            [
                'sortOrder'         => 300,
                'displayArea'       => 'borderRadius',
                'additionalClasses' => 'bfb-design-bottom',
                'placeholder'       => '-',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_BORDER_RADIUS_BOTTOM_LEFT,
            'text',
            [
                'sortOrder'         => 400,
                'displayArea'       => 'borderRadius',
                'additionalClasses' => 'bfb-design-left',
                'placeholder'       => '-',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ]
            ]
        );

        $this->addChildren(
            AbstractElement::FIELD_BORDER_RADIUS_UNIT,
            'select',
            [
                'sortOrder'            => 500,
                'displayArea'          => 'borderRadius',
                'additionalClasses'    => 'bfb-design-border-radius-unit',
                'options'              => $this->text->getUnit(),
                'selectedPlaceholders' => false,
                'value'                => 'px'
            ]
        );

        $this->addChildren(
            static::FIELD_CUSTOM_CLASS,
            'text',
            [
                'label'             => __('Custom Class'),
                'sortOrder'         => 100,
                'additionalClasses' => 'bfb-custom-class',
                'displayArea'       => 'footer'
            ]
        );

        $this->addChildren(
            static::FIELD_CUSTOM_CSS,
            'code',
            [
                'label'             => __('Custom CSS'),
                'sortOrder'         => 200,
                'rows'              => 12,
                'additionalClasses' => 'bfb-custom-css',
                'displayArea'       => 'footer'
            ]
        );

        return $this;
    }
}
