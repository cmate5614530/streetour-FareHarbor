<?php
/**
 *
 * @class      class_simpledevel_crypt_functions
 * @package    simpledevel
 * @since      Version 1.0.0
 * @author     Daniel Sánchez Sáez
 *
 */

if ( ! class_exists( 'class_simpledevel_crypt_functions' ) ) {
    /**
     * Class class_simpledevel_crypt_functions
     *
     * @author Daniel Sánchez Sáez <dssaez@gmail.com>
     */
    class class_simpledevel_crypt_functions {

        public function __construct() {

        }

        /**
         *
         * Generate public and private key
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.0
         * @return void
         * @access public
         */
        public function generate_public_and_private_keys( $prefix = '' ) {

          // Create the keypair
          $res = openssl_pkey_new();

          // Get private key
          openssl_pkey_export( $res, $private_key );

          // Get public key
          $public_key = openssl_pkey_get_details( $res );
          $public_key = $public_key[ "key" ];

          return array(
            $prefix . 'public_key' => $public_key,
            $prefix . 'private_key' => $private_key,
          );

        }

        /**
         *
         * Generate secret and security key
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.0
         * @return void
         * @access public
         */
        public function generate_secret_and_security_keys( $length ) {

          $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
          $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
          $codeAlphabet.= "0123456789";
          $codeAlphabet.= "/_$&%#@!?";
          $max = strlen($codeAlphabet); // edited

          $secret_key = '';

          for ( $i=0; $i < $length; $i++ ) {
              $secret_key .= $codeAlphabet[ random_int( 0, $max-1 ) ];
          }

          $security_key = '';

          for ( $i=0; $i < $length; $i++ ) {
              $security_key .= $codeAlphabet[ random_int( 0, $max-1 ) ];
          }

        	return array(
        		'secret_key'   => "scrk_" . $secret_key,
        		'security_key' => "scuk_" . $security_key,
        	);
        }

        /**
         *
         * encrypt and decrypt string
         *
         * @author Daniel Sanchez Saez <dssaez@gmail.com>
         * @since  1.0.0
         * @return void
         * @access public
         */
        public function simpledevel_crypt( $secret_key, $security_key, $string, $action = 'encrypt' ) {

            $output = false;
             $encrypt_method = "AES-256-CBC";
             $key = hash( 'sha256', $secret_key );
             $iv = substr( hash( 'sha256', $security_key ), 0, 16 );

             if( $action == 'encrypt' ) {
                 $output = openssl_encrypt( $string, $encrypt_method, $key, 0, $iv );
             }
             else if( $action == 'decrypt' ){
                 $output = openssl_decrypt( $string, $encrypt_method, $key, 0, $iv );
             }

             return $output;

        }

        /**
        *
        * simpledevel_ssl_encrypt_message
        *
        * @author Daniel Sanchez Saez <dssaez@gmail.com>
        * @since  1.0.0
        * @return void
        * @access public
        */
        public function simpledevel_ssl_encrypt_message( $source, $key, $type ) {

            //Assumes 1024 bit key and encrypts in chunks.

            $maxlength = 117;
            $output = '';

            while( $source ){

                $input = substr( $source, 0, $maxlength );
                $source = substr( $source, $maxlength );
                if( $type == 'private' ){
                    $ok = openssl_private_encrypt( $input, $encrypted, $key );
                }else{
                    $ok = openssl_public_encrypt( $input, $encrypted, $key );
                }

                $output.= $encrypted;

            }

            return $output;

        }

      /**
       *
       * encrypt message with public key and private key
       *
       * @author Daniel Sanchez Saez <dssaez@gmail.com>
       * @since  1.0.0
       * @return void
       * @access public
       */
      public function simpledevel_encrypt_message( $string, $remote_public_key, $local_private_key ) {

          $string_encrypted_by_remote_public_key = $this->simpledevel_ssl_encrypt_message( $string, $remote_public_key, 'public' );
          
          $string_encrypted_by_private_key_over_remote_public_key = $this->simpledevel_ssl_encrypt_message( $string_encrypted_by_remote_public_key, $local_private_key, 'private' );

          return $string_encrypted_by_private_key_over_remote_public_key;
     }

     /**
      *
      * simpledevel_ssl_encrypt_message
      *
      * @author Daniel Sanchez Saez <dssaez@gmail.com>
      * @since  1.0.0
      * @return void
      * @access public
      */
     public function simpledevel_ssl_decrypt_message( $source, $key, $type ) {

        // The raw PHP decryption functions appear to work
        // on 256 Byte chunks. So this decrypts long text
        // encrypted with ssl_encrypt().

        $maxlength = 256;
        $output = '';
        while( $source ){
          $input = substr( $source, 0, $maxlength );
          $source = substr( $source, $maxlength );
          if( $type == 'private' ){
            $ok = openssl_private_decrypt( $input, $out, $key );
          }else{
            $ok = openssl_public_decrypt( $input, $out, $key );
          }

          $output.= $out;
        }
        return $output;

    }

    /**
     *
     * encrypt message with public key and private key
     *
     * @author Daniel Sanchez Saez <dssaez@gmail.com>
     * @since  1.0.0
     * @return void
     * @access public
     */
    public function simpledevel_decrypt_message( $string, $remote_public_key, $local_private_key ) {

        $string_decrypted_by_remote_public_key = $this->simpledevel_ssl_decrypt_message( $string, $remote_public_key, 'public' );

        $string_decrypted_by_local_private_key_over_remote_public_key = $this->simpledevel_ssl_decrypt_message( $string_decrypted_by_remote_public_key, $local_private_key, 'private' );

        return $string_decrypted_by_local_private_key_over_remote_public_key;

    }

  }

}

?>
