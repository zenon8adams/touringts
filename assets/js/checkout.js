jQuery( document).ready( function( $)
{
    console.log( ajax_object);

    console.log( document);
    
    let country_select = $( '.country_to_state.country_select').change( updateSelectBillingOrShipping);
    
    function field( country_code, type) {
        
        let states = ajax_object.states[ country_code],
            input = `
                <label for="${ type}_state">State&nbsp;<abbr class="required" title="required">*</abbr></label>
                <span class="woocommerce-input-wrapper">
                    <span class="woocommerce-input-wrapper">
                        <input type="text" class="input-text" name="${ type}_city" id="${ type}_city" placeholder="Enter your state" value="" autocomplete="address-level2">
                    </span>
                </span>
            `,
            select_start = `
                <label for="${ type}_state">State&nbsp;<abbr class="required" title="required">*</abbr></label><span class="woocommerce-input-wrapper">
                <select name="${ type}_state" id="${ type}_state" class="state_select " autocomplete="address-level1" data-placeholder="Select an option…" data-input-classes="" data-label="State">
                    <option value="">Select an option…</option>`,
			select_end = '</select></span>',
            select_options = "",
            repl = document.createElement( 'p');
            
        repl.setAttribute( 'class', 'form-row form-row-wide address-field validate-required validate-state');
        repl.setAttribute( 'id', `${ type}_state_field`);
        repl.setAttribute( 'data-priority', "80");
        
        if( states && states.length !== 0)
        {
            for( const prop in states)
            {
                select_options += `<option value="$prop">${ states[ prop] }</option>`;                
            }
            repl.innerHTML = select_start + select_options + select_end;
            
            $( repl).find( 'select').change( updateSelectBillingOrShipping);
        }
        else
            repl.innerHTML = input;
            
        
        return repl;
    }
    
    function updateSelectBillingOrShipping( event) {

        let id = event.target.getAttribute( 'id'),
            parts = id.split( '_'),
            type = parts[ 0],
            stateOrCountry = parts[ 1],
            stateOrCountryIndex = event.target.selectedIndex,
            other = oppositeOf( type);
        
        if( stateOrCountry == 'country')
            $( `#${ type}_state_field`).replaceWith( field( event.target.value, type));
            
    }
    
    function oppositeOf( type) {
        const lookup = {
            billing: "shipping",
            shipping: "billing"
        };
        
        return lookup[ type];
    }
    
})();