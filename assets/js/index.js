
(function()
{
    let continueButton = document.querySelector('.continue > span'),
        apparels = document.querySelectorAll( '.apparel'),
        maps = document.querySelectorAll( '.map-selection > a > svg'),
        taglines = document.querySelectorAll( '.taglines > div'),
        headers = document.querySelectorAll( '.map-section > h1'),
        customizers = document.querySelectorAll( '.map-region'),
        holder = [], items = {},
        timeOuthandler;
        buttonToolTip = tippy( continueButton, {
            content: "You have to select a map and a shirt before you can proceed",
            maxWidth: 200,
            followCursor: true,
            hideOnClick: false,
            onShow(instance) {
                setTimeout(() => {
                    instance.hide();
                }, 3500);
            },
            theme: 'map'
        });
    
    window.pageMessage = 'Welcome to <strong>Touringts</strong>.<br>Select an apparel, a map, customize your map, and then continue.';
    
    window.addEventListener( 'popstate', ( event) => {
        let location = window.location.hash;
        if( location == '#customize')
        {
            $( headers[ 0]).hide();
            $( customizers[ 0]).hide();
            $( headers[ 1]).show();
            $( customizers[ 1]).show();
            $( headers[ 1]).get( 0).scrollIntoView( true);
        }else if( location == '#map-section') {
            $( headers[ 0]).show();
            $( customizers[ 0]).show();
            $( headers[ 1]).hide();
            $( customizers[ 1]).hide();
            $( '#map-section').get( 0).scrollIntoView();
        }
    });
    
    
    
    $( headers[ 1]).hide();
    $( customizers[ 1]).hide();
    
    Array.from( apparels).forEach( apparel => {
        apparel.parentNode.addEventListener('click', () => {
            window.displayInfo('Next, choose the <strong>map</strong> you wish to add to your dress.');
            apparel._type = 'APPAREL';
            items["image"] = apparel.getAttribute('src');
            items[ "productID"] = apparel.getAttribute( 'data-id');
            tick( apparel);
            sessionStorage.setItem('image-src', items["image"]);
        });
    });
    
    Array.from(maps).forEach( map => {
        map.addEventListener('click', () => {
            if( !items[ "image"])
                window.displayInfo( '<strong>Don\'t forget</strong> to select your preferred dress.');
            else
                window.displayInfo( '<strong>Good!</strong> You can now click on places you\'ve visited.');
            map._type = 'MAP';
            items["map"] = map.parentElement.getAttribute('id');
            tick( map);
        });
    });
    
    Array.from( taglines).forEach( tagline => {
        tagline._type = 'TAGLINE';
        tagline.addEventListener( 'click', () => {
            if( !items[ "map"])
                window.displayInfo( '<strong>Don\'t forget, you have not selected a <strong>map<strong>.');
            else
                window.displayInfo( '<strong>Finally!</strong> Press the <strong>Continue</strong> button.');
            items[ "tagline"] = tagline.innerText;
            tick( tagline);
            sessionStorage.setItem('tagline', items["tagline"]);
        });
    });
    
    function tick( selection)
    {
        if( !holder[ selection._type])
            selection.classList.add( 'selected');
        else
        {
            let currentHolder = holder[ selection._type];
            currentHolder.classList.remove( 'selected');
            selection.classList.add( 'selected');
        }
        holder[ selection._type] = selection;
        updateStates();
    }
    
    function updateStates() {
        if( items[ "tagline"] && items[ "image"] && items[ "map-src"])
        {
            continueButton.style.pointerEvents = 'all';
            buttonToolTip.disable();
        }
    }
    
    function svgToPng( $el)
    {
        let mapData = new XMLSerializer().serializeToString( $el.get( 0));
        sessionStorage.setItem( "map-src", "data:image/svg+xml;base64," + btoa( unescape(encodeURIComponent(mapData))));
    
        return `data:image/svg+xml;base64,${ btoa( unescape(encodeURIComponent(mapData)))}`;
    }
    
    
    let selection = [],
        selectionCount = 0,
        map = $(".map-customize > svg").clone();
        $(map).removeAttr("class");
        $(map).attr("width", "400");
        $(map).attr("height", "300");
    
    $(".map-customize > svg path").hover( function(){
        displayInfo( $(this).attr("data-map"));
    });
    
    $(".map-customize > svg path").click(function () {
        let fillers = [ "#0C7E7E", "#997B0D"],
            clicked = $(this).attr("data-map");
        selection[ clicked ] = !selection[ clicked];
        $(this).css( "fill", fillers[ 0 + selection[ clicked]]);
        selectionCount += (selection[ clicked] << 1) - 1;
        
        if( selectionCount === 0)
            displayInfo( '<strong>Note</strong>: You can\'t continue until you selected <strong>places</strong> you\'ve visited.');
    
        let canvas  = document.createElement("canvas"),
            image   = document.getElementById( "chest");
        $(map).css("fill", "none").css("stroke", "#161032");
        $(map).find(`path[data-map='${clicked}']`).css("fill", selection[ clicked] ? fillers[ 1] : "none");
        items[ "map-src"] = svgToPng( map);
    });
    
    continueButton.addEventListener( 'click', () => {
        sessionStorage.setItem( 'touringts-packet', JSON.stringify( items));
        window.location.href = continueButton.getAttribute( "data-href");
    });
    
})();

