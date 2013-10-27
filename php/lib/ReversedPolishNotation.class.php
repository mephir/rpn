<?php
class ReversedPolishNotation {
  protected $expression = null;
  protected $variables = array();
  protected $operators = array('+', '-', '*', '/', '%');

  public function __construct($expression = null, $variables = array(), $infix = false) {
    if ($infix) {
      $this->expression = $this->fromInfixNotation($expression);
    } else {
      $this->expression = $expression;
    }
    $this->variables = $variables;
  }

  public function fromInfixNotation($expression) {
    //
  }

  public function validate() {
    //
  }

  public function setVariable($key, $value) {
    //
  }

  public function setVariables(array $variables) {
    //
  }

  public function removeVariable($key) {
    //
  }

  public function removeVariables(array $keys) {
    //
  }

  public function evaluate() {
    //
  }
}