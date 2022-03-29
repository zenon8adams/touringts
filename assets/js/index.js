
(function()
{
    class MapAreaSelector {
        
        static selectedAreaCountReachedZeroMessage = null;
        
        constructor() {
            this.selection = [];
            this.selectionCount = 0;
            this.map = $( '.map-customize > svg').clone();
            $( this.map).removeAttr("class");
            $( this.map).attr("width", "400");
            $( this.map).attr("height", "300");
            this.addHoverWatch();
            this.addClickWatch( this);
        }
        
        addHoverWatch() {
            $(".map-customize > svg path").hover( function( event){
                window.displayInfo( $( event.target).attr("data-map"));
            });
        }
        
        addClickWatch( self) {
            $(".map-customize > svg path").click( function ( event) {
                let fillers = [ "#0C7E7E", "#997B0D"],
                    target = event.target,
                    clicked = $( target).attr("data-map");

                self.selection[ clicked ] = ! self.selection[ clicked];
                $( target).css( "fill", fillers[ 0 + self.selection[ clicked]]);
                self.selectionCount += ( self.selection[ clicked] << 1) - 1;
                
                if( self.selectionCount === 0)
                {
                    MapAreaSelector.selectedAreaCountReachedZeroMessage = `<strong>Note</strong>: You can\'t continue
                                                                           until you selected <strong>places</strong> you\'ve visited.`
                }
                else
                    MapAreaSelector.selectedAreaCountReachedZeroMessage = null;
            
                $( self.map).css("fill", "none").css("stroke", "#161032");
                $( self.map).find(`path[data-map='${ clicked}']`).css("fill", self.selection[ clicked] ? fillers[ 1] : "none");
                items[ "map-src"] = self.svgToPng();
            });
        }
        svgToPng()
        {
            let mapData = new XMLSerializer().serializeToString( this.map.get( 0));
            return `data:image/svg+xml;base64,${ btoa( unescape(encodeURIComponent(mapData)))}`;
        }
        static selectedAreaCount() {
            return this.selectionCount;
        }
    }
    
    class InfoManager
    {
        constructor() {
            this.managedObjects = [];
        }
        
        displayInfoOn( obj) {
            let idx = obj.group.index,
                match = null;
            if( idx > 0)
            {
                match = this.__getMatch( obj, -1, ( idx) => idx > 0);
                if( match)
                {
                    window.displayInfo( `<strong>Good!</strong> Don't forget you haven't selected your ${ match.group.name}`);
                    return;
                }
            }
            else if( idx < this.managedObjects.length - 1)
            {
                match = this.__getMatch( obj, +1, ( idx) => idx < this.managedObjects.length);
                if( match)
                {
                    window.displayInfo( `<strong>Good!</strong> You can now proceeed to select your ${ match.group.name}`);
                    return;
                }

            }
            
            window.displayInfo( obj.group.defaultMessage);
        }
        
        __getMatch( obj, step, testFun) {
            let idx = obj.group.index;
            while( testFun( idx))
            {
                let stepObjs = this.managedObjects[ idx + step];
                if( stepObjs === null)
                    break;
                let fufilledAny = stepObjs.filter( o => o.group.fulfilled);
                idx += step;
                if( fufilledAny.length !== 0)
                    continue;
                return stepObjs.at( 0);
            }
            return null;
        }
        
        addObject( obj)
        {
            let matches = this.managedObjects.filter( o => o.at( 0).group.name === obj.group.name);
            if( matches.length === 0)
            {
                obj.group.index = this.managedObjects.length;
                this.managedObjects.push( [obj]);   
            }
            else
            {
                let idx = matches.at( 0).at( 0).group.index;
                if( obj in this.managedObjects[ idx])
                    return;
                this.managedObjects[ idx].push( obj);
            }
        }
    }
    
    
    let continueButton = document.querySelector('.continue > span'),
        apparels = document.querySelectorAll( '.apparel'),
        maps = document.querySelectorAll( '.map-selection > a > svg'),
        taglines = document.querySelectorAll( '.taglines > div'),
        headers = document.querySelectorAll( '.map-section > h1'),
        customizers = document.querySelectorAll( '.map-region'),
        infoManager = new InfoManager(),
        mapAreaSelector = new MapAreaSelector(),
        dress = null,
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
        }),
        dressColors = document.querySelector( '.dress_colors'),
        dressSizes  = document.querySelector( '.dress_sizes');
        
    window.pageMessage = 'This is the Dress and Map selection page. Select your dress, customize your map, and then continue.';
    
    window.addEventListener( 'popstate', ( event) => {
        let location = window.location.hash;
        if( location == '#customize')
        {
            $( headers[ 0]).hide();
            $( customizers[ 0]).hide();
            $( headers[ 1]).show();
            $( customizers[ 1]).show();
            $( headers[ 1]).get( 0).scrollIntoView( {behavior: "smooth", block: "start", inline: "nearest"});
        }
        else if( location == '#map-section') {
            $( headers[ 0]).show();
            $( customizers[ 0]).show();
            $( headers[ 1]).hide();
            $( customizers[ 1]).hide();
            $( '#map-section').get( 0).scrollIntoView( {behavior: "smooth", block: "start", inline: "nearest"});
            $( '#dress-section').attr( 'href', '#map-section');
        }
        else if( location == '#customize') {
            $( '#customize').get( 0).scrollIntoView( {behavior: "smooth", block: "center", inline: "nearest"});
        }
    });
    
    $( headers[ 1]).hide();
    $( customizers[ 1]).hide();
    
    $( '#map-section').click( function() {
       $( '#dress-section').attr( 'href', '#customize'); 
    });
    
    let apparelGroup = {
          name: "Dress",
          defaultMessage: "You can now proceed to select your dress size",
          fulfilled: false
    };
    Array.from( apparels).forEach( apparel => {
        apparel.group = apparelGroup;
        infoManager.addObject( apparel);
        apparel.parentNode.addEventListener('click', () => {
            apparel.group.fulfilled = true;
            infoManager.displayInfoOn( apparel);
            apparel._type = 'APPAREL';
            items[ "white-dress-front-img-src"] = apparel.getAttribute( 'src');
            items[ "white-dress-back-img-src"]  = apparel.getAttribute( 'data-white-dress-back-img-src');
            items[ "black-dress-front-img-src"] = apparel.getAttribute( 'data-black-dress-front-img-src');
            items[ "black-dress-back-img-src"]  = apparel.getAttribute( 'data-black-dress-back-img-src');
            items[ "productID"] = apparel.getAttribute( 'data-id');
            dress = apparel;
            tick( apparel);
            // sessionStorage.setItem('image-src', items["image"]);
        });
    });
    
    items[ "default_size"] = dressSizes.getAttribute( 'data-default-value');
    
    let activeSize = items[ "default_size"] ? $( `[value="${ items[ 'default_size'].toUpperCase()}"`).find( '.before') : null,
        dressSizeGroup = {
            name: "Size",
            defaultMessage: "You can now proceed to selection of your preferred map",
            fulfilled: false
        };
    activeSize?.toggle();
    $( '.dress_sizes > .button').each( function() {
        let dressSize = $( this);
        dressSize.group = dressSizeGroup;
        infoManager.addObject( dressSize);
        dressSize.click( function(){
            dressSize.group.fulfilled = true;
           if( activeSize)
            activeSize.toggle();
            activeSize = $( this).find( '.before');
            activeSize.toggle();
            items[ "size"] = activeSize.parent().text();
            items[ "variationID"] = activeSize.parent().attr( 'data-variation-id'); 
        
            infoManager.displayInfoOn( dressSize);
        });
    });
    
    let mapGroup = {
        name: "Map",
        defaultMessage: "You can now select places you've been on the map",
        fulfilled: false
    }
    Array.from(maps).forEach( map => {
        map.group = mapGroup;
        infoManager.addObject( map);
        map.addEventListener('click', () => {
            map.group.fulfilled = true;
            infoManager.displayInfoOn( map);
            map._type = 'MAP';
            items["map"] = map.parentElement.getAttribute('id');
            tick( map);
        });
    });
    
    let taglineGroup = {
        name: "Tagline",
        defaultMessage: "You can now proceed to the dress customization page. <strong>Well done!</strong>",
        fulfilled: false,
        upgradedMessage: MapAreaSelector.selectedAreaCountReachedZeroMessage
    };
    Array.from( taglines).forEach( tagline => {
        tagline.group = taglineGroup;
        infoManager.addObject( tagline);
        tagline._type = 'TAGLINE';
        tagline.addEventListener( 'click', () => {
            tagline.group.fulfilled = true;
            infoManager.displayInfoOn( tagline);
            items[ "tagline"] = tagline.innerText;
            tick( tagline);
        });
    });
    
    $( 'input[type="textbox"]').change( function( event) {
        items[ "tagline"] = event.target.value;
        updateStates();
    
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
        if( items[ "tagline"] && items[ "white-dress-front-img-src"] && items[ "map-src"])
        {
            continueButton.style.pointerEvents = 'all';
            buttonToolTip.disable();
        }
    }
    
    continueButton.addEventListener( 'click', () => {
        items[ "qty"] = $( dress).parent().parent().find( 'input').get( 0).value;
        items[ "colors"] = dressColors.getAttribute( 'data-values');
        items[ "default_color"] = dressColors.getAttribute( 'data-default-value');
        items[ "default_size"] = dressSizes.getAttribute( 'data-default-value');
        sessionStorage.setItem( 'touringts-packet', JSON.stringify( items));
        window.location.href = continueButton.getAttribute( "data-href");
    });
    
})();