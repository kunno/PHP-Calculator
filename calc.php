<?php

// Defines assoc type to value.
define('ASSOC_NONE', 0);
define('ASSOC_LEFT', 1);
define('ASSOC_RIGHT', 2);

// Setting $operator to an array that defines each operator as a key and sets
// its precedence and associativity.
$operators = array('^' => array(9, ASSOC_RIGHT),
    '*' => array(8, ASSOC_LEFT),
    '/' => array(8, ASSOC_LEFT),
    '+' => array(5, ASSOC_LEFT),
    '-' => array(5, ASSOC_LEFT),
    '(' => array(0, ASSOC_NONE),
    ')' => array(0, ASSOC_NONE));

/** Function Name: precedence
 *  @param  $opchar
 *  Definition: Function that takes in an operator as a parameter, and returns
 *              its precedence code.
 * @return : int
 *           Returns precedence code of operator as an int. Precedence is
 *           defined by the $operator array and the position of precedence in
 *           the operator array is 0. For example the * operator will return
 *           the code 80.
 * */

function precedence($opchar) {
    global $operators;
    return $operators[$opchar][0];
}

/** Function Name: associativity
 *  @param $opchar
 *  Definition: Function that takes in an operator as a parameter, and returns
 *              its associativity.
 *  @return int
 *          Returns associativity code of operator as an int. Associativity is
 *          defined by the $operator array and the position of associativity in
 *          the operator array is 1. For example the * operator will return
 *          the code 11.
 * */
function associativity($opchar) {
    global $operators;
    return $operators[$opchar][1];
}

/** Function Name: is_operator
 * @param $char
 * Definition : Function that takes in a character key as a parameter, and
 *               checks if given key has been set in operator array.
 * @return bool
 *         Returns true if given key is set in array.
 */
function is_operator($char) {
    global $operators;
    return array_key_exists($char, $operators);
}

/** Function Name: starts_with
 * @param $haystack
 * @param $needle
 * Definition: Function that takes in a string ($needle) and compares it to
 *             another given string ($haystack) and see's if the characters
 *             in the first string ($needle) is what the second string
 *             ($haystack) begins with.
 * @return bool
 *         Returns true if $haystack string starts with $needle string.
 */
function starts_with($haystack, $needle) {
    return !strncmp($haystack, $needle, strlen($needle));
}

/** Function Name: ends_with
 * @param $haystack
 * @param $needle
 * Definition: Function that takes in a string ($needle) and compares it to
 *             another given string ($haystack) and see's if the characters
 *             in the first string ($needle) is what the second string
 *             ($haystack) ends with.
 * @return bool
 *         Returns true if $haystack string ends with $needle string.
 */
function ends_with($haystack, $needle) {
    return substr($haystack, -strlen($needle)) === $needle;
}

/** Function Name: array_peek
 * @param $stack
 * Definition: Function that takes in an array and peeks at what is at the end.
 * @return mixed
 *         Returns the character at the end of the array
 */
function array_peek($stack) {
    return $stack[count($stack) - 1];
}

/** Function Name: postfix
 * @param $expression
 * Definition: Function that takes in users expression in infix notation and
 *             converts it to the postfix equivalent.
 * @return array
 *         Returns postfix equivalence of user defined expression as an array.
 */
function postfix($expression) {

    if (!starts_with($expression, '(')) {
        $expression = '('.$expression;
    }

    if (!ends_with($expression, ')')) {
        $expression .= ')';
    }

    $stack = array();
    $output = array();
    $numtoken = '';

    for ($i = 0; $i < strlen($expression); $i++) {
        $char = $expression[$i];

        if (is_operator($char)) {
            if ($numtoken != '') {
                $output[] = $numtoken;
                $numtoken = '';
            }

            if ($char == '(') {
                array_push($stack, $char);
            }
            else if ($char == ')') {
                while (count($stack) > 0 && ($top = array_peek($stack)) != '(') {
                    $output[] = array_pop($stack);
                }

                array_pop($stack);
            }
            else {
                while (count($stack) > 0) {
                    $peek = array_peek($stack);

                    if (associativity($char) == ASSOC_LEFT && precedence($char) <= precedence($peek)
                        || associativity($char) == ASSOC_RIGHT && precedence($char) < precedence($peek)) {
                        $output[] = array_pop($stack);
                    }
                    else {
                        break;
                    }
                }

                array_push($stack, $char);
            }
        }
        else {
            $numtoken .= $char;
        }
    }

    while (count($stack) > 0) {
        if (array_peek($stack) == '(') {
            array_pop($stack);
        }
        else {
            $output[] = array_pop($stack);
        }
    }

    return $output;
}

/**
 * Function Name: postfix_eval
 * @param $postfix
 * @param array $variables
 * Definition: Takes the postfix expression and an array size as parameters
 *             and returns the evaluation of the postfix expression.
 * @return mixed
 *         Returns postfix evaluation.
 */
function postfix_eval($postfix, $variables = array()) {
    $stack = array();

    foreach ($postfix as $token) {
        if (is_operator($token)) {
            $second = array_pop($stack);
            $first = array_pop($stack);

            if ($second == null || $first == null) {

                continue;
            }

            if (!is_numeric($first) && array_key_exists($first, $variables)) {
                $first = $variables[$first];
            }

            if (!is_numeric($second) && array_key_exists($second, $variables)) {
                $second = $variables[$second];
            }



            if ($token == '^') {
                $result = pow($first, $second);
            }
            else {
                $result = eval("return $first $token $second;");
            }

            array_push($stack, $result);
        }
        else {
            if (strlen($token) > 0)
            {
                array_push($stack, $token);
            }
        }
    }

    return array_pop($stack);
}

// Gets equation from JS file, calculates and echos result
if (isset($_GET['equate'])) {
    $expression = $_GET['equate'];
    $postfix = postfix($expression);
    echo($expression);
    echo('<br />'.'Postfix: '.implode(' ', $postfix).'<br />');
    echo('= '.postfix_eval($postfix, array('size' => 121)));
}
else {
    echo $_GET['equate'];
}

