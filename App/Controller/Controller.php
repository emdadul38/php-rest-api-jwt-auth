<?php
    namespace App;
    use App\UserModel;

    /**
     * Controller - The Base Controller for all other Controllers.... All Other Controllers extends this Controller.
     *
     * @author      Ilori Stephen A <stephenilori458@gmail.com>
     * @link        https://github.com/learningdollars/php-rest-api/App/Controller/Controller.php
     * @license     MIT
     */
    class Controller {

        /**
         * validation
         *
         * Validates an array of objects using defined rules...
         *
         * @param array $Payload  Contains an array of Objects that will be validated.
         * @return array $response
         */
        protected static function validation($payloads)
        {
            $response = [];
            foreach($payloads as $payload) {
                if ($payload->validator == 'required') {
                    if ($payload->data == null || $payload->data = '' || !isset($payload->data)) {
                        array_push($response, [
                            'key' => $payload->key,
                            'message' => "The {$payload->key} field is required"
                        ]);
                    }
                }

                if ($payload->validator == 'string') {
                    if (preg_match('/[^A-Za-z]/', $payload->data)) {
                        array_push($response, [
                            'key' => $payload->key,
                            'message' => "Sorry {$payload->key} expects an Alphabet."
                        ]);
                    }
                }

                if ($payload->validator == 'numeric') {
                    if (preg_match('/[^0-9_]/', $payload->data)) {
                        array_push($response, [
                            'key' => $payload->key,
                            'message' => "Sorry {$payload->key} expects a Number."
                        ]);
                    }
                }

                if ($payload->validator == 'boolean') {
                    if (strtolower(gettype($payload->data)) !== 'boolean') {
                        array_push($response, [
                            'key' => $payload->key,
                            'message' => "Sorry {$payload->key} expects a Boolean."
                        ]);
                    }
                }

                if (stristr($payload->validator, ':')) {
                    $operationName = explode(':', $payload->validator)[0];
                    $operationChecks = (int) explode(':', $payload->validator)[1];

                    if (strtolower($operationName) == 'min' && $operationChecks > strlen($payload->data)) {
                        array_push($response, [
                            'key' => $payload->key,
                            'message' => "Sorry {$payload->key} is supposed to be less than " . strlen($payload->data)
                        ]);
                    }


                    if (strtolower($operationName) == 'max' && $operationChecks < strlen($payload->data)) {
                        array_push($response, [
                            'key' => $payload->key,
                            'message' => "Sorry {$payload->key} is supposed to be greather than " . strlen($payload->data)
                        ]);
                    }


                    if (strtolower($operationName) == 'between') {
                        $operationChecksTwo = (int) explode(':', $payload->validator)[2];
                        array_push($response, [
                            'key' => $payload->key,
                            'message' => "Sorry {$payload->key} is supposed to be between " . $operationChecks . ' and ' . $operationChecksTwo
                        ]);
                    }

                }

                if ($payload->validator == 'emailExists') {
                    try {
                        $UserModel = new UserModel();
                        $checkEmail = $UserModel::checkEmail($payload->data);

                        if ($checkEmail['status']) {
                            array_push($response, [
                                'key' => $payload->key,
                                'message' => "Sorry {$payload->key} already exists. Please try with a different Email."
                            ]);
                        }
                    } catch (Exception $e) { /** */ }
                }

            }

            if (count($response) < 1) {
                $validationErrors = new \stdClass();
                $validationErrors->status = false;
                $validationErrors->errors = [];

                return $validationErrors;
            }

            $validationErrors = new \stdClass();
            $validationErrors->status = true;
            $validationErrors->errors = $response;
            return $validationErrors;
        }

        /**
         * JWTSecret
         *
         * Returns a JWT Secret....
         *
         * @param void
         * @return string Annonymous
         */
        protected static function JWTSecret()
        {
            return 'K-lyniEXe8Gm-WOA7IhUd5xMrqCBSPzZFpv02Q6sJcVtaYD41wfHRL3';
        }
    }
?>
