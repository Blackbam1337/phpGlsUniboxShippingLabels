<?php
/**
 * Gls_Unibox extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Gls
 * @package    Gls_Unibox
 * @copyright  Copyright (c) 2012 webvisum GmbH
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * @category   webvisum GmbH
 * @package    Gls_Unibox
 */
class Gls_Unibox_Model_Label_Item_Font 
{
	public $name;		//(String) Name of the Font, defaults to "Swiss721_Cn_BT"
	public $size;		//(Integer) Fontsize
	public $face;		//(String) inverse, bold
	public $rotation; 	//rotation im Uhrzeigersinn (integer)
	public $paddingTop; // how much more should the background extend at the top (only used when type is invert)
	public $paddingRight; // how much more should the background extend at the right (only used when type is invert)
	public $paddingBottom; // how much more should the background extend at the bottom (only used when type is invert)
	public $paddingLeft; // how much more should the background extend at the left (only used when type is invert)

	public function __construct() {
		$this->name = "Swiss721_Cn_BT";
		$this->size = null;
		$this->face = null;
		$this->rotation = null;
		$this->paddingTop = 0;
		$this->paddingRight = 0;
		$this->paddingBottom = 0;
		$this->paddingLeft = 0;
	}
	public function setName($val) { $this->name = $val; return $this; }
	public function setSize($val) { $this->size = $val; return $this; }
	public function setFace($val) { $this->face = $val; return $this; }
	public function setRotation($val) { $this->rotation = $val; return $this; }
	public function setPaddingTop($val) { $this->paddingTop = $val; return $this; }
	public function setPaddingRight($val) { $this->paddingRight = $val; return $this; }
	public function setPaddingBottom($val) { $this->paddingBottom = $val; return $this; }
	public function setPaddingLeft($val) { $this->paddingLeft = $val; return $this; }

	public function getName() { return $this->name; }
	public function getSize() { return $this->size; }
	public function getFace() { return $this->face; }
	public function getRotation() { return $this->rotation; }
	public function getPaddingTop() { return $this->paddingTop; }
	public function getPaddingRight() { return $this->paddingRight; }
	public function getPaddingBottom() { return $this->paddingBottom; }
	public function getPaddingLeft() { return $this->paddingLeft; }
}