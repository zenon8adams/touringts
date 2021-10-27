jQuery( document).ready( function( $){
   
   $( '.country_select ').click( function(){
        
        $.ajax({
          method: "POST",
          url: ajax_object.ajax_url,
          data: { 'action': 'ajax_states', 'countryID': $( this).val() }
        })
        .done(function( response)
        {
            // $( '.state_select');
            console.log( response);
        });
       
   });
    
});

/*

jQuery( document).ready( function( $){
   
   $( '.country_select ').click( function( e){
        
        $.ajax({
          method: "POST",
          url: ajax_object.ajax_url,
          data: { 'action': 'ajax_states', 'countryID': $( this).val() }
        })
        .done(function( response)
        {
            if( response == "0")
            {
                let text = `
                    <p class="form-row form-row-wide address-field" id="${ type}_city_field" data-priority="70">
                        <label for="${ type}_city" class="">State</label>
                        <span class="woocommerce-input-wrapper">
                            <input type="text" class="input-text " name="${ type}_city" id="${ type}_city" placeholder="" value="" autocomplete="address-level2">
                        </span>
                    </p>
                `;
            }
            let id = e.target.getAttribute( 'id'),
                type = id.split( '_')[ 0],
                row_start = `
                    <p class="form-row form-row-wide address-field validate-required validate-state" id="${ type}_state_field" data-priority="80">
                    <label for="${ type}_state" class="">State&nbsp;<abbr class="required" title="required">*</abbr></label><span class="woocommerce-input-wrapper">
                    <select name="${ type}_state" id="${ type}_state" class="state_select " autocomplete="address-level1" data-placeholder="Select an option…" data-input-classes="" data-label="State">
                        <option value="">Select an option…</option>`,
				row_end = '</select></span></p>',
                row_content = "",
                respObj = JSON.parse( response.slice( 0, response.length-1));
           
            for( const prop in respObj)
            {
                console.log( respObj[ prop]);
                row_content += `<option value="$prop">${ respObj[ prop] }</option>`;                
            }
            
            $( row_start + row_content + row_end).insertAfter( '#billing_city_field');
            
        });
   });
    
});

*/