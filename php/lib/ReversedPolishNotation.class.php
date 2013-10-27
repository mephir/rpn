<?php
class ReversedPolishNotation {
  protected $expression = null;
  protected $variables = array();
  protected $operators = array('+', '-', '*', '/', '%', '^');

  public function __construct($expression = null, $variables = array(), $infix = false) {
    if ($infix) {
      $this->fromInfixNotation($expression);
    } else {
      $this->expression = $expression;
    }
    $this->variables = $variables;
  }

  public function fromInfixNotation($expression) {
    $stack = array();
    $output = '';
    $i = 0;

    $expression = preg_replace('/\s+/', '', $expression); //removing all whitespaces
    $expression_length = strlen($expression);

    while ($i < $expression_length) {
      if (preg_match('#[a-z0-9]#Uis', $expression[$i])) {
        while ($i < $expression_length && preg_match('#[a-z0-9]#Uis', $expression[$i])) {
          $output .= $expression[$i];
          $i++;
        }
        $output .= ' ';
      }

      if ($i < $expression_length && $expression[$i] == '(') {
        array_push($stack, $expression[$i]);
        $i++;
      }

      if ($i < $expression_length && $expression[$i] == ')') {
        $n = array_pop($stack);
        while ( $n != '(') {
          $output .= $n . ' ';
          $n = array_pop($stack);
        }
        $i++;
      }

      if ($i < $expression_length && $this->isOperator($expression[$i])) {
        if (empty($stack)) {
          array_push($stack, $expression[$i]);
        } else {
          $n = array_pop($stack);
          while ($this->operatorsPriority($n) >= $this->operatorsPriority($expression[$i])) {
            $output .= $n . ' ';
            $n = array_pop($stack);
          }
          array_push($stack, $n);
          array_push($stack, $expression[$i]);
        }
        $i++;
      }
    }

    while (!empty($stack)) {
      $operator = array_pop($stack);
      $output .= $operator . ' ';
    }

    $this->expression = trim($output);
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

  public function getExpression() {
    return $this->expression;
  }

  public function setExpression($expression = null) {
    //
  }

  protected function isOperator($char) {
    return in_array($char, $this->operators);
  }

  protected function operatorsPriority($operator) {
    if ($operator == '^') {
      return 3;
    }
    if (in_array($operator, array('*', '/', '%'))) {
      return 2;
    }
    if (in_array($operator, array('+', '-'))) {
      return 1;
    }
    return 0;
  }

  public function __toString() {
    return $this->getExpression();
  }
}