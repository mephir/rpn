<?php
require('../lib/ReversedPolishNotation.class.php');

$infixExpression = "3+4*2/(1-5)^2";

$rpn = new ReversedPolishNotation($infixExpression, array(), true);

echo "\n".$rpn->getExpression()."\n";