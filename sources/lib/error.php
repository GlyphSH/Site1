<?php
namespace lib\error;
/**
 * Description of error
 *
 * @author Kevin Kessler
 */
class uhoh 
{
    private static $errBuffer = array();
    private static $errCount = NULL;
    private $errPrefix = NULL;

    public function setErrors($errPrefix, $arrErrors, $errTotal)
            {
                //make sure the required parameters exist...
                if (isset($errPrefix) AND isset($arrErrors))
                    {
                        //k they exist, lets make sure they are the correct type..
                            if (is_array($arrErrors) == TRUE AND !empty($arrErrors) == TRUE)
                                {
                                    switch ($errPrefix)
                                        {
                                            case 'registration':
                                                if (isset($errTotal))
                                                    {
                                                        if (is_int($errTotal) == TRUE)
                                                            {
                                                                //set error total
                                                                self::$errCount = $errTotal;
                                                                for ($i = 0; $i < $errTotal;  $i++)
                                                                    {
                                                                        self::$errBuffer['field'.$i] = $arrErrors['field['.$i.']'];
                                                                        self::$errBuffer['rule'.$i] = $arrErrors['rule['.$i.']'];
                                                                        self::$errBuffer['param'.$i] = $arrErrors['param['.$i.']'];
                                                                    }
                                                            }
                                                    }
                                                    break;
                                            case 'login':
                                                if (isset($errTotal))
                                                    {
                                                        if (is_int($errTotal) == TRUE)
                                                            {
                                                                //set error total
                                                                self::$errCount = $errTotal;
                                                                for ($i = 0; $i < $errTotal;  $i++)
                                                                    {
                                                                        self::$errBuffer['field'.$i] = $arrErrors['field['.$i.']'];
                                                                        self::$errBuffer['rule'.$i] = $arrErrors['rule['.$i.']'];
                                                                        self::$errBuffer['param'.$i] = $arrErrors['param['.$i.']'];
                                                                    }
                                                            }
                                                    }
                                                    break;
                                            case 'database':
                                                self::$errBuffer['field0'] = 'Database';
                                                self::$errBuffer['rule0'] = $arrErrors[1];
                                                self::$errBuffer['param0'] = $arrErrors[2];
                                                self::$errCount = $errTotal;
                                                break;
                                        }
                                }
                                else
                                    {
                                        return 'The error handler just had an error.';
                                        die();
                                }
                    }
            }

    public static function getErrors($errCom)
            {
                if (isset($errCom))
                    {
                        switch ($errCom) 
                            {
                                case 'registration':
                                    return uhoh::$errBuffer; 
                                case 'login':
                                    return uhoh::$errBuffer; 
                                case 'regCount':
                                    return uhoh::$errCount;
                                case 'logCount':
                                    return uhoh::$errCount; 
                                case 'database':
                                    return uhoh::$errBuffer; 
                            }
                    }
            }
    public static function clearErrors()
            {
                uhoh::$errBuffer = NULL;
                if (uhoh::$errBuffer == NULL)
                    {
                        return TRUE;
                    }else
                        {
                            return FALSE;
                    }
            }
}
