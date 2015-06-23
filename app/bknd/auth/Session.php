<?php
class Session
{
   /**
    * Inicia una nueva sesion
    * @return boolean
    */
   public static function crearSesion()
   {
      session_set_cookie_params(0);
      session_start();
      self::set('open', true);
      self::set('last_activity', time());
      return true;
   }

   /**
    * Termina la sesion actual
    */
   public static function terminarSesion()
   {
      if( session_id()!=''){
         session_unset();
         session_destroy();
      }
   }

   /**
    * Revisa si la sesion esta activa
    * @return boolean
    */
   public static function isActive()
   {
      session_set_cookie_params(0);
      session_start();
      if(self::get('open')==true && (time()- self::get('last_activity') < 1200)){
          self::set('last_activity', time());
          return true;
      }
      else{
         self::terminarSesion();
         return false;
      }
   }

   /**
    * Declara el valor de una variable en la sesion actual
    * @param varName
    * @param value
    */
   public static function set($varName, $value)
   {
      $_SESSION[$varName] = $value;
   }

   /**
    * Devuelve una variable de la sesion actual
    * @param  string
    * @return string|boolean
    */
   public static function get($varName)
   {
      if(isset($_SESSION[$varName])){
         return $_SESSION[$varName];
      }
      else{
         return NULL;
      }
   }
}

?>