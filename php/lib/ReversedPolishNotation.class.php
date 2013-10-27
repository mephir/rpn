<?php
/**
 * ReversedPolishNotation class - supports converting and evaluating RPN(postfix) expressions
 * @author masiakla
 *
 * @todo: float support in conversion without variables
 * @todo: negative numbers support in infix expression
 */
class ReversedPolishNotation {
  protected $expression = null;
  protected $variables = array();
  protected $operators = array('+', '-', '*', '/', '%', '^');

  public function __construct($expression = null, $infix = false, $variables = array()) {
    if (!is_null($expression)) {
      $this->setExpression($expression, $infix);
    }
    $this->variables = $variables;
  }

  /**
   * Convert infix expression to RPN(postfix) - Edsger Dijkstra's algorithm
   *
   * @param string $expression
   *
   * @throws InvalidArgumentException
   */
  public function fromInfixNotation($expression) {
    $stack = array();
    $output = '';
    $i = 0;

    $expression = preg_replace('/\s+/', '', $expression); //removing all whitespaces
    $expression_length = strlen($expression);
    if (!$this->validateInfix($expression)) {
      throw new InvalidArgumentException('Infix expression is not correct!');
    }

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

  /**
   * Validate RPN(postfix) expression
   *
   * @param string $expression
   * @return boolean
   */
  public function validateRPN($expression) {
    //
  }

  public function setVariable($key, $value) {
    $this->variables[$key] = $value;
  }

  public function setVariables(array $variables) {
    $this->variables = array_merge($this->variables, $variables);
  }

  public function removeVariable($key) {
    //
  }

  public function removeVariables(array $keys) {
    //
  }

  public function evaluate($variables = array()) {
    if (is_null($this->expression)) {
      return false;
    }
    if (!empty($variables)) {
      $this->setVariables($variables);
    }
    $expression = trim($this->getExpression());
    if (!empty($this->variables)) { //if we have any variables we try to replace them with values
      $expression = strtr($expression, $this->variables);
    }

    $stack = array();
    $elements = explode(' ', $expression);

    foreach ($elements as $element) {
      if (is_numeric($element)) {
        array_push($stack, $element);
      } elseif ($this->isOperator($element)) {
        $b = array_pop($stack);
        $a = array_pop($stack);
        array_push($stack, $this->operation($a, $b, $element));
      }
    }
    return array_pop($stack);
  }

  /**
   * Return RPN(postfix) expression
   *
   * @return string
   */
  public function getExpression() {
    return $this->expression;
  }

  /**
   * Sets RPN(postfix) expression
   *
   * @param string $expression
   * @param boolean $infix defines notation of expression
   *
   * @throws InvalidArgumentException
   */
  public function setExpression($expression, $infix = false) {
    if ($infix) {
      $this->fromInfixNotation($expression);
    } else {
      $this->expression = $expression;
    }
  }

  /**
   * Checks if char is supported operator operator is supported
   *
   * @param char $char
   * @return boolean
   */
  protected function isOperator($char) {
    return in_array($char, $this->operators);
  }

  /**
   * Method return weight for arithmetic operator
   *
   * @param char $operator
   * @return integer
   */
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

  /**
   * Very simple method which quickly validate infix expression, not perfect but roks for most of typical errors
   *
   * @param string $expression
   * @return boolean
   */
  protected function validateInfix($expression) {
    $parenthesis_level = 0;
    for ($i = 0; $i < strlen($expression); $i++) {
      if (!$this->isOperator($expression[$i]) && !preg_match('#[0-9a-z()\s]#Uis', $expression[$i])) {
        return false;
      }
      if ($expression[$i] == '(') {
        $parenthesis_level++;
      }
      if ($expression[$i] == ')') {
        $parenthesis_level--;
      }
      if ($parenthesis_level < 0) {
        return false;
      }
    }
    return $parenthesis_level == 0;
  }

  protected function operation($a, $b, $operator) {
    switch ($operator) {
      case '+':
        return $a + $b;
      case '-':
        return $a - $b;
      case '*':
        return $a * $b;
      case '/':
        return $a / $b;
      case '%':
        return $a % $b;
      case '^':
        return pow($a, $b);
      default:
        throw new DomainException('Operation is not supported!');
    }
  }

  public function __toString() {
    return $this->getExpression();
  }
}