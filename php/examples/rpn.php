<?php
require('../lib/ReversedPolishNotation.class.php');

$infixExpression = "3+4*2/(1-5)^2";
$rpnExpression = "12 2 3 4 * 10 5 / + * +";

$rpn = new ReversedPolishNotation($infixExpression, true);

echo $rpn->getExpression()."\n";

$rpn->setExpression($rpnExpression);

echo $rpn->evaluate()."\n";