<?php
/**
 * Created by PhpStorm.
 * User: LeeAllen
 * Date: 12/4/13
 * Time: 5:23 PM
 */

namespace Core;
class Session
{
    /**
     * AOL users may switch IP addresses from one proxy to another.
     *
     * @link http://webmaster.info.aol.com/proxyinfo.html
     * @var array
     */

    /**
     * This function starts, validates and secures a session.
     *
     * @param string $name The name of the session.
     * @param int $limit Expiration date of the session cookie, 0 for session only
     * @param string $path Used to restrict where the browser sends the cookie
     * @param string $domain Used to allow subdomains access to the cookie
     * @param bool $secure If true the browser only sends the cookie over https
     *
     *  Creates a basic session.
     *  Session::sessionStart('InstallationName');
     *
     *  Creates a session thats ends when the browser closes and is only accessible at www.site.com/myBlog/
     *  Session::sessionStart('Blog_myBlog', 0, '/myBlog/', 'www.site.com');
     *
     *  Creates a session thats ends when the browser closes and is only accessible at https://accounts.bank.com/
     *  Session::sessionStart('Accounts_Bank', 0, '/', 'accounts.bank.com', true);
     */

    static function sessionStart($name, $limit = 0, $path = '/', $domain = null, $secure = null)
    {
        // Set the cookie name
        session_name($name . '_Session');

        // Set SSL level
        $https = isset($secure) ? $secure : isset($_SERVER['HTTPS']);

        // Set session cookie options
        session_set_cookie_params($limit, $path, $domain, $https, true);
        session_start();

        // Make sure the session hasn't expired, and destroy it if it has
        if (self::validateSession()) {
            // Check to see if the session is new or a hijacking attempt
            if (!self::preventHijacking()) {
                // Reset session data and regenerate id
                $_SESSION = array();
                $_SESSION['IPaddress'] = isset($_SERVER['HTTP_X_FORWARDED_FOR'])
                    ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
                $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
                self::regenerateSession();

                // Give a 5% chance of the session id changing on any request
            } elseif (rand(1, 100) <= 5) {
                self::regenerateSession();
            }
        } else {
            $_SESSION = array();
            session_destroy();
            session_start();
        }
    }

    /**
     * This function regenerates a new ID and invalidates the old session. This should be called whenever permission
     * levels for a user change.
     *
     */
    static function regenerateSession()
    {
        // If this session is obsolete it means there already is a new id
        if (isset($_SESSION['OBSOLETE']) || $_SESSION['OBSOLETE'] == true)
            return;

        // Set current session to expire in 10 seconds
        $_SESSION['OBSOLETE'] = true;
        $_SESSION['EXPIRES'] = time() + 10;

        // Create new session without destroying the old one
        session_regenerate_id(false);

        // Grab current session ID and close both sessions to allow other scripts to use them
        $newSession = session_id();
        session_write_close();

        // Set session ID to the new one, and start it back up again
        session_id($newSession);
        session_start();

        // Now we unset the obsolete and expiration values for the session we want to keep
        unset($_SESSION['OBSOLETE']);
        unset($_SESSION['EXPIRES']);
    }

    /**
     * This function is used to see if a session has expired or not.
     *
     * @return bool
     */
    static protected function validateSession()
    {
        if (isset($_SESSION['OBSOLETE']) && !isset($_SESSION['EXPIRES']))
            return false;

        if (isset($_SESSION['EXPIRES']) && $_SESSION['EXPIRES'] < time())
            return false;

        return true;
    }

    /**
     * This function checks to make sure a session exists and is coming from the proper host. On new visits and hacking
     * attempts this function will return false.
     *
     * @return bool
     */
    static protected function preventHijacking()
    {
        if (!isset($_SESSION['IPaddress']) || !isset($_SESSION['userAgent']))
            return false;


        if ($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT']
            && !(strpos($_SESSION['userAgent'], ÔTridentÕ) !== false
                && strpos($_SERVER['HTTP_USER_AGENT'], ÔTridentÕ) !== false)
        ) {
            return false;
        }

        $sessionIpSegment = substr($_SESSION['IPaddress'], 0, 7);

        $remoteIpHeader = isset($_SERVER['HTTP_X_FORWARDED_FOR'])
            ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

        $remoteIpSegment = substr($remoteIpHeader, 0, 7);

        $aolProxies = array('195.93.', '205.188', '198.81.', '207.200', '202.67.', '64.12.9');

        if ($_SESSION['IPaddress'] != $remoteIpHeader
            && !(in_array($sessionIpSegment, $aolProxies) && in_array($remoteIpSegment, $aolProxies))
        ) {
            return false;
        }

        if ($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT'])
            return false;

        return true;
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        if (isset($_SESSION[$key]))
            return $_SESSION[$key];
    }

    public static function kill($key)
    {
        if (isset($_SESSION[$key]))
            unset($_SESSION[$key]);
    }

    public static function destroy()
    {
        $_SESSION = array();
        unset($_SESSION);
        session_destroy();
    }

    public static function checkNewVisitor(){ // check to see if new or returning visitor
        if(isset($_COOKIE['visitorId']) && !isset($_SESSION['visitorId'])){
            $_SESSION['visitorId']=$_COOKIE['visitorId'];
        }else{
            if(!isset($_SESSION['visitorId'])){
                $visitorId = md5(time()+rand());
                $_SESSION['visitorId']=$visitorId;
            }

        }
    }
}
