<?php
 

namespace PatricPoba\MtnMomo\Utilities;

/**
 * MassAssignable Trait
 *
 * looks through fillable fields and matches them with provided
 * to enable mass assignment option using a constructor
 * or a make function.
 */
trait AttributesMassAssignable
{
    /**
     * This method is used to mass assign the properties required in a class.
     *  
     * It loops through the fields marked as required and optional
     * and then assisngs values to those fields using accessors
     * available in the class for those required options.
     *
     * @param   array $data
     * @example [
     *              'walletId' => 'some-existing-walletId',
     *              'transactionId' => 'someExistingTransactionId'
     *          ]
     * @return  static
     */
    protected function massAssignAttributes($data = [])
    {
        if ( ! is_array($data)) {
            return $this;
        }

        foreach ($data as $key => $value) {
            if ( method_exists($this, 'set' . ucfirst($key))
                // && in_array($key, array_merge($this->parametersRequired, $this->parametersOptional))
            ) {
                $this->{'set' . $key}($value);
            }else{
                $this->$key = $value;
            }
        }

        return $this;
    }
    
    /**
     * Assign static variables in a dynamic way
     *
     * @param array $data
     * @return void
     */
    // public static function massAssignStaticAttributes($data = []) 
    // {
    //     if (is_array($data)) {
    //         foreach ($data as $key => $value) {
    //             if (method_exists($this, 'set' . $key)
    //                 && in_array($key, array_merge($this->parametersRequired, $this->parametersOptional))
    //             ) {
    //                 self::$key = $value; 
    //             }
    //         }
    //     }
 
    // }
}
