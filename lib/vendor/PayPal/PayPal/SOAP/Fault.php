<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Shane Caraveo <Shane@Caraveo.com>   Port to PEAR and more   |
// | Authors: Dietrich Ayala <dietrich@ganx4.com> Original Author         |
// +----------------------------------------------------------------------+
//
// $Id: Fault.php,v 1.1.1.1 2006/02/19 08:15:20 dennis Exp $
//
require_once('PEAR.php');

/**
 * SOAP_Fault
 * PEAR::Error wrapper used to match SOAP Faults to PEAR Errors
 *
 * @package  SOAP
 * @access   public
 * @author   Shane Caraveo <Shane@Caraveo.com>   Port to PEAR and more
 * @author   Dietrich Ayala <dietrich@ganx4.com> Original Author
 */
class SOAP_Fault extends PEAR_Error
{
    
    /**
     * Constructor
     * 
     * @param    string  message string for fault
     * @param    mixed   the faultcode
     * @param    mixed   see PEAR::ERROR 
     * @param    mixed   see PEAR::ERROR 
     * @param    array   the userinfo array is used to pass in the
     *                   SOAP actor and detail for the fault
     */
    function SOAP_Fault($faultstring = 'unknown error', $faultcode = 'Client', $faultactor=NULL, $detail=NULL, $mode = null, $options = null)
    {
        parent::PEAR_Error($faultstring, $faultcode, $mode, $options, $detail);
        if ($faultactor) $this->error_message_prefix = $faultactor;
    }
    
    /**
     * message
     *
     * returns a SOAP_Message class that can be sent as a server response
     *
     * @return SOAP_Message 
     * @access public
     */
    function message()
    {
        $msg =& new SOAP_Base();
        $params = array();
        $params[] =& new SOAP_Value('faultcode', 'QName', 'SOAP-ENV:'.$this->code);
        $params[] =& new SOAP_Value('faultstring', 'string', $this->message);
        $params[] =& new SOAP_Value('faultactor', 'anyURI', $this->error_message_prefix);
        if (isset($this->backtrace)) {
            $params[] =& new SOAP_Value('detail', 'string', $this->backtrace);
        } else {
            $params[] =& new SOAP_Value('detail', 'string', $this->userinfo);
        }
        
        $methodValue =& new SOAP_Value('{'.SOAP_ENVELOP.'}Fault', 'Struct', $params);
        $headers = NULL;
        return $msg->_makeEnvelope($methodValue, $headers);
    }
    
    /**
     * getFault
     *
     * returns a simple native php array containing the fault data
     *
     * @return array 
     * @access public
     */
    function getFault()
    {
        global $SOAP_OBJECT_STRUCT;
        if ($SOAP_OBJECT_STRUCT) {
            $fault =& new stdClass();
            $fault->faultcode = $this->code;
            $fault->faultstring = $this->message;
            $fault->faultactor = $this->error_message_prefix;
            $fault->detail = $this->userinfo;
            return $fault;
        }
        return array(
                'faultcode' => $this->code,
                'faultstring' => $this->message,
                'faultactor' => $this->error_message_prefix,
                'detail' => $this->userinfo
            );
    }
    
    /**
     * getActor
     *
     * returns the SOAP actor for the fault
     *
     * @return string 
     * @access public
     */
    function getActor()
    {
        return $this->error_message_prefix;
    }
    
    /**
     * getDetail
     *
     * returns the fault detail
     *
     * @return string 
     * @access public
     */
    function getDetail()
    {
        return $this->userinfo;
    }
    
}
?>