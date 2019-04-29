<?php
/**
 * This file is part of the O2System Framework package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author         Steeve Andrian Salim
 * @copyright      Copyright (c) Steeve Andrian Salim
 */

// ------------------------------------------------------------------------

namespace O2System\Framework\Libraries\Ui\Components\Form\Elements;

// ------------------------------------------------------------------------

use O2System\Framework\Libraries\Ui\Element;

/**
 * Class Checkbox
 * @package O2System\Framework\Libraries\Ui\Components\Form
 */
class Checkbox extends Element
{
    /**
     * Checkbox::$label
     *
     * @var Label
     */
    public $label;

    /**
     * Checkbox::$input
     *
     * @var Input
     */
    public $input;

    // ------------------------------------------------------------------------

    /**
     * Checkbox::__construct
     *
     * @param string|label  $label
     * @param array         $attributes
     */
    public function __construct($label = null, array $attributes = [])
    {
        if (is_array($label)) {
            $attributes = $label;
            $label = null;
        }

        parent::__construct('div');
        $this->attributes->addAttributeClass('form-check');

        $this->label = new Label([
            'class' => 'form-check-label',
        ]);

        if (isset($label)) {
            $this->label->textContent->push($label);
        }

        $this->input = new Input([
            'class' => 'form-check-input',
            'type'  => 'checkbox',
        ]);

        if (isset($attributes[ 'id' ])) {
            $this->entity->setEntityName('input-' . $attributes[ 'id' ]);
        } elseif (isset($attributes[ 'name' ])) {
            $this->entity->setEntityName('input-' . $attributes[ 'name' ]);
        }

        if (count($attributes)) {
            foreach ($attributes as $name => $value) {
                $this->input->attributes->addAttribute($name, $value);
            }
        }

        $this->label->childNodes->push($this->input);
        $this->childNodes->push($this->label);
    }

    // ------------------------------------------------------------------------

    /**
     * Checkbox::inline
     *
     * @return static
     */
    public function inline()
    {
        $this->attributes->addAttributeClass('form-check-inline');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Checkbox::disabled
     *
     * @return static
     */
    public function disabled()
    {
        $this->attributes->addAttributeClass('disabled');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Checkbox::render
     *
     * @return string
     */
    public function render()
    {
        if ( ! $this->label->hasTextContent()) {
            $this->input->attributes->addAttributeClass('position-static');
        }

        return parent::render();
    }
}