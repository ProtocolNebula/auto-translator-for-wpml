<?php
namespace racs\wpmlat\TranslationService;

/**
 * This class provide some methods to develop faster the Translations Service
 * adding some generic functions.
 * 
 * To use this class
 *  - set in your TranslationService::__constructor:
 *      $this->initHelper();
 * 
 * This file is include in "./TranslationService.php"
 */
class TranslationServiceHelpers {
    
    /**
     * Save the latest query timestamp
     * Methods to access:
     *  ->waitUntilNextRequest();
     *  ->getNextQueryMiliseconds(); // Manual alternative to ->waitUntilNextRequest()
     *  ->updateLastQueryTime();
     */
    private $lastQueryTime = null;
    
    
    /**
     * Save the current timestap to calculate "getNextQueryMiliseconds()"
     */
    public updateLastQueryTime() {
        $this->lastQueryTime = \microtime(true);
        return $this;
    }
    
    /**
     * Returns the miliseconds left to make the next query
     * @param number $milisecondsToWait Miliseconds between executions, 
     *  if not specified, will load from settings
     * @return number Miliseconds to wait
     */
    public getNextQueryMiliseconds( $milisecondsToWait = null ) {
        if (!$milisecondsToWait) $milisecondsToWait = 0;
        
        $nextQuery = microTime(true) - $this->lastQueryTime - $milisecondsToWait;
        
        return ($nextQuery > 0) ? $nextQuery : 0;
    }
    
    public waitUntilNextRequest ( $milisecondsToWait = null) {
        $wait = $this->getNextQueryMiliseconds ( $milisecondsToWait );
        if ( $wait > 0 ) {
            usleep($wait);
        }
        return $this;
    }
}