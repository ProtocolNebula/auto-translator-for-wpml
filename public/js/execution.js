/*
 * Script to do the "execution" process with ajax
 */
jQuery(document).ready(function ($) {
  var data = {
    'action': 'wpmlat_execute',
    'datapage': parseInt(ajax_object.datapage),
    'current_page': parseInt(ajax_object.datapage),
    'max_step': parseInt(ajax_object.max_step),
  };
  
  var container_process = $("#wpmlat_execution_content"),
    nextTimeout = 1; // Timeout for errors (in seconds)
  
  update_process_data();
  process_next_page(data.datapage + 1);
  
  function process_next_page( page ) {
    data.datapage = parseInt(page);
    
    jQuery.post(ajax_object.ajax_url, data)
      .done(function ( response ) {
        try {
          response = jQuery.parseJSON(response);

          if ( response.refresh ) {
            nextTimeout = 1;

            data.current_page = data.datapage;
            
            // replace url for the current page (usually will be 1 more than "current_page" (page show) or the same if user refresh
            window.history.replaceState( {}, '', response.next_url );

            // Callback to the next page
            process_next_page ( response.next_page );

            // Show process to the user
            update_process_data( response.result_execution );
          } else if ( response.finished ) {
            // Redirect to finish page
            window.location.replace ( response.next_url );
          } else {
            // Error?
            process_next_page_error ();
          }
        } catch ( error ) {
          process_next_page_error ( error );
        }
      })
      .fail(function (error) {
         process_next_page_error (error);
      });
  }
  
  /**
   * Update the content div with information
   * @return {undefined}
   */
  function update_process_data( response ) {
    if ( response === undefined ) response = '';
    container_process.html( 'Updating with ajax...<br />' +
      'Page: ' + data.current_page.toString() + '<br />' +
      'Elements processed: ' + (data.current_page * data.max_step).toString() + '<br />' +
      'Last response:<br />' + response
    );
  }
  
  function process_next_page_error( error ) {
    // 1 second more to every error up to 30 seconds
    if (nextTimeout < 30) {
      nextTimeout += 1;
    }
    
    update_process_data ( 'An error ocurred trying to update: <i>' + error + '</i>.<br /><br />' +
      'Execution will continue in <b>' + nextTimeout + ' second/s</b>'
    );
    
    setTimeout(() => {
      process_next_page( data.datapage );
    }, nextTimeout * 1000 );
  }
});
