<?php

class Validator {

    private  $namings, $customErrorsWithInputName, $customErrors;
    public $errors;
    /**
     * Constructor is not allowed because SimpleValidator uses its own
     * static method to instantiate the validaton
     */
    final private function __construct($errors, $namings) {
        $this->errors = $errors;
        $this->namings = $namings;
    }

    /**
     * 
     * @return boolean
     */
    final public function isSuccess() {
        return (empty($this->errors) == true);
    }

    /**
     * 
     * @param Array $errors_array
     */
    final public function customErrors($errors_array) {
        foreach ($errors_array as $key => $value) {
            // handle input.rule eg (name.required)
            if (preg_match("#^(.+?)\.(.+?)$#", $key, $matches)) {
                // $this->customErrorsWithInputName[name][required] = error message
                $this->customErrorsWithInputName[(string) $matches[1]][(string) $matches[2]] = $value;
            } else {
                $this->customErrors[(string) $key] = $value;
            }
        }
    }

    protected function getDefaultLang() {
        return "en";
    }

    protected function getErrorFilePath($lang) {
        return null;
    }

    final protected function getDefaultErrorTexts($lang = null) {
        /* handle default error text file */
        $default_error_texts = array();
        if (file_exists("error" . DS. $lang . ".php")) {
            $default_error_texts = include("error". DS . $lang . ".php");
        }
        return $default_error_texts;
    }

    final protected function getCustomErrorTexts($lang = null) {
        /* handle error text file for custom validators */
        $custom_error_texts = array();
        if (file_exists($this->getErrorFilePath($lang)))
            $custom_error_texts = include($this->getErrorFilePath($lang));
        return $custom_error_texts;
    }

    final protected function handleNaming($input_name) {
        if (isset($this->namings[(string) $input_name])) {
            $named_input = $this->namings[(string) $input_name];
        } else {
            $named_input = $input_name;
        }
        return $named_input;
    }

    final protected function handleParameterNaming($params) {
        foreach ($params as $key => $param) {
            if (preg_match("#^:([a-zA-Z0-9_]+)$#", $param, $param_type)) {
                if (isset($this->namings[(string) $param_type[1]]))
                    $params[$key] = $this->namings[(string) $param_type[1]];
                else
                    $params[$key] = $param_type[1];
            }
        }
        return $params;
    }

    /**
     * 
     * @param string $error_file
     * @return array
     * @throws SimpleValidatorException
     */
    final public function getErrors($lang = null) {
        if ($lang == null)
            $lang = $this->getDefaultLang();

        $default_error_texts = $this->getDefaultErrorTexts($lang);
        $custom_error_texts = $this->getCustomErrorTexts($lang);
        foreach ($this->errors as $input_name => $results) {
            foreach ($results as $rule => $result) {
                $named_input = $this->handleNaming($input_name);
                /**
                 * if parameters are input name they should be named as well
                 */
                $result['params'] = $this->handleParameterNaming($result['params']);
                // if there is a custom message with input name, apply it
                if (isset($this->customErrorsWithInputName[(string) $input_name][(string) $rule])) {
                    $error_message = $this->customErrorsWithInputName[(string) $input_name][(string) $rule];
                }
                // if there is a custom message for the rule, apply it
                else if (isset($this->customErrors[(string) $rule])) {
                    $error_message = $this->customErrors[(string) $rule];
                }
                // if there is a custom validator try to fetch from its error file
                else if (isset($custom_error_texts[(string) $rule])) {
                    $error_message = $custom_error_texts[(string) $rule];
                }
                // if none try to fetch from default error file
                else if (isset($default_error_texts[(string) $rule])) {
                    $error_message = $default_error_texts[(string) $rule];
                } else {
                    throw new SimpleValidatorException(SimpleValidatorException::NO_ERROR_TEXT, $rule);
                }
                /**
                 * handle :params(..)
                 */
                if (preg_match_all("#:params\((.+?)\)#", $error_message, $param_indexes))
                    foreach ($param_indexes[1] as $param_index) {
                        $error_message = str_replace(":params(" . $param_index . ")", $result['params'][$param_index], $error_message);
                    }
                $error_results[] = str_replace(":attribute", $named_input, $error_message);
            }
        }
        return $error_results;
    }

    /**
     * 
     * @return boolean
     */
    final public function has($input_name, $rule_name = null) {
        if ($rule_name != null)
            return isset($this->errors[$input_name][$rule_name]);
        return isset($this->errors[$input_name]);
    }

    final public function getResults() {
        return $this->errors;
    }

    /**
     * Gets the parameter names of a rule
     * @param type $rule
     * @return mixed
     */
    final private static function getParams($rule) {
        if (preg_match("#^([a-zA-Z0-9_]+)\((.+?)\)$#", $rule, $matches)) {
            return array(
                'rule' => $matches[1],
                'params' => explode(",", $matches[2])
            );
        }
        return array(
            'rule' => $rule,
            'params' => array()
        );
    }

    /**
     * Handle parameter with input name
     * eg: equals(:name)
     * @param mixed $params
     * @return mixed
     */
    final private static function getParamValues($params, $inputs) {
        foreach ($params as $key => $param) {
            if (preg_match("#^:([a-zA-Z0-9_]+)$#", $param, $param_type)) {
                $params[$key] = @$inputs[(string) $param_type[1]];
            }
        }
        return $params;
    }

    /**
     * 
     * @param Array $inputs
     * @param Array $rules
     * @param Array $naming
     * @return Validator
     * @throws SimpleValidatorException
     */
    final public static function validate($inputs, $rules, $naming = null) {
        $errors = null;
        foreach ($rules as $input => $input_rules) {
            if (is_array($input_rules)) {
                foreach ($input_rules as $rule => $closure) {
                    if (!isset($inputs[(string) $input]))
                        $input_value = null;
                    else
                        $input_value = $inputs[(string) $input];
                    /**
                     * if the key of the $input_rules is numeric that means
                     * it's neither an anonymous nor an user function.
                     */
                    if (is_numeric($rule)) {
                        $rule = $closure;
                    }
                    $rule_and_params = static::getParams($rule);
                    $params = $real_params = $rule_and_params['params'];
                    $rule = $rule_and_params['rule'];
                    $params = static::getParamValues($params, $inputs);
                    array_unshift($params, $input_value);
                    /**
                     * Handle anonymous functions
                     */
                    if (@get_class($closure) == 'Closure') {
                        $refl_func = new \ReflectionFunction($closure);
                        $validation = $refl_func->invokeArgs($params);
                    }/**
                     * handle class methods 
                     */ else if (@method_exists(get_called_class(), $rule)) {
                        $refl = new \ReflectionMethod(get_called_class(), $rule);
                        if ($refl->isStatic()) {
                            $refl->setAccessible(true);
                            $validation = $refl->invokeArgs(null, $params);
                        } else {
                            throw new SimpleValidatorException(SimpleValidatorException::STATIC_METHOD, $rule);
                        }
                    } else {
                        throw new SimpleValidatorException(SimpleValidatorException::UNKNOWN_RULE, $rule);
                    }
                    if ($validation == false) {
                        $errors[(string) $input][(string) $rule]['result'] = false;
                        $errors[(string) $input][(string) $rule]['params'] = $real_params;
                    }
                }
            } else {
                throw new SimpleValidatorException(SimpleValidatorException::ARRAY_EXPECTED, $input);
            }
        }
        return new static($errors, $naming);
    }

    

    protected static function required($input = null) {
        return (!is_null($input) && (trim($input) != ''));
    }

    protected static function numeric($input) {
        return is_numeric($input);
    }

    protected static function email($input) {
        return filter_var($input, FILTER_VALIDATE_EMAIL);
    }

    protected static function integer($input) {
        return is_int($input) || ($input == (string) (int) $input);
    }

    protected static function float($input) {
        return is_float($input) || ($input == (string) (float) $input);
    }

    protected static function alpha($input) {
        return (preg_match("#^[a-zA-Z]+$#", $input) == 1);
    }

    protected static function alpha_numeric($input) {
        return (preg_match("#^[a-zA-Z0-9]+$#", $input) == 1);
    }

    protected static function ip($input) {
        return filter_var($input, FILTER_VALIDATE_IP);
    }

    /*
     * TODO: need improvements for tel and urn urls. 
     * check out url.test.php for the test result
     * urn syntax: http://www.faqs.org/rfcs/rfc2141.html
     * 
     */

    protected static function url($input) {
        return filter_var($input, FILTER_VALIDATE_URL);
    }

    protected static function max_length($input, $length) {
        return (strlen($input) <= $length);
    }

    protected static function min_length($input, $length) {
        return (strlen($input) >= $length);
    }

    protected static function exact_length($input, $length) {
        return (strlen($input) == $length);
    }

    protected static function equals($input, $param) {
        return ($input == $param);
    }

}



class SimpleValidatorException extends \Exception {

    const NO_ERROR_TEXT = 1;
    const STATIC_METHOD = 2;
    const UNKNOWN_RULE = 3;
    const ARRAY_EXPECTED = 4;

    private static $error_messages;

    public function __construct($code, $param) {
        static::$error_messages = array(
            static::NO_ERROR_TEXT => "Error text could not found for ':param', or Error file could not found",
            static::STATIC_METHOD => "The method :param should be static",
            static::UNKNOWN_RULE => "Unknown Rule: :param",
            static::ARRAY_EXPECTED => "Rules are expected to Array. Input Name: :param"
        );
        parent::__construct(str_replace(":param", $param, static::$error_messages[$code]), $code);
    }

}

?>