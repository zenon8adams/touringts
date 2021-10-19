jQuery( document).ready( function( $)
{
    let packet = sessionStorage.getItem( 'touringts-packet');
    if( packet )
        packet = JSON.parse( packet);
    else
        $( '.loader').show();

    let canvas = new fabric.Canvas('map-area'),
        imgSrc = packet[ 'image'],
        html = document.documentElement,
        context = canvas.getElement().getContext( '2d'),
        fontSize = parseInt( getComputedStyle( html).getPropertyValue( '--tagline-fontsize'));
    
    function getCanvasDim() {
        let width = getComputedStyle(html).getPropertyValue('--canvas-width'),
            height = getComputedStyle(html).getPropertyValue('--canvas-height');

        return {
            width: parseInt(width.replace(/px/, '')),
            height: parseInt(height.replace(/px/, '')),
            fontSize: parseInt( fontSize)
        };
    }

    let canvDim = getCanvasDim();
    canvas.setWidth(canvDim.width);
    canvas.setHeight(canvDim.height);

    tippy('#js-tray-slide', {
        content: 'Select Color to paint your map. Drag vertically to reveal other colors',
        maxWidth: canvDim.width * .5,
        followCursor: true,
        hideOnClick: false,
        onShow(instance) {
            setTimeout(() => {
                instance.hide();
            }, 3500);
        },
        theme: 'map'
    });

    tippy('.upper-canvas', {
        content: 'You can click on the map to resize to fit your choice. You can press <b>Escape</b> when you\'re done',
        maxWidth: canvDim.width * .5,
        allowHTML: true,
        followCursor: true,
        hideOnClick: false,
        onShow(instance) {
            setTimeout(() => {
                instance.hide();
            }, 3500);
        },
        theme: 'map'
    });

    window.addEventListener("keydown", (event) => {
        if (event.key == "Escape") {
            close();
            canvas.discardActiveObject().renderAll();
        }
    });

    fabric.Object.prototype.setControlsVisibility({
        // mb: false,
        // ml: false,
        // mr: false,
        // mt: false,
        mtr: false
    });

    fabric.Image.fromURL(imgSrc, (image) => {
        canvas.add(image.set({
            width: canvas.width,
            height: canvas.height - 16,
            top: 8
        }));
        canvas.item(0).selectable = false;
        let map = new Image();
        map.src = packet[ 'map-src'];
        map.addEventListener( 'load', () => {
            image = new fabric.Image( map, {
                width: canvas.width * .55,
                height: canvas.height * .40,
                top: canvas.height * .30,
                left: canvas.width * .26
            });
            canvas.add( image);
            let text = packet[ 'tagline'],
                textLength = context.measureText( text),
                tagline = new fabric.Text( text, {
                    fontSize: fontSize,
                    fill: 'white',
                    objectCaching: false,
                    top: canvas.height * .65,
                    left: .5 * ( canvas.width - textLength.width),
                });
            canvas.add( tagline);
        });
    });

    const colors = [
        {
            color: '131417'
        },
        {
            color: '374047'
        },
        {
            color: '5f6e78'
        },
        {
            color: '7f8a93'
        },
        {
            color: '97a1a7'
        },
        {
            color: 'acb4b9'
        },
        {
            color: 'DF9998',
        },
        {
            color: '7C6862'
        },
        {
            color: 'A3AB84'
        },
        {
            color: 'D6CCB1'
        },
        {
            color: 'F8D5C4'
        },
        {
            color: 'A3AE99'
        },
        {
            color: 'EFF2F2'
        },
        {
            color: 'B0C5C1'
        },
        {
            color: '8B8C8C'
        },
        {
            color: '565F59'
        },
        {
            color: 'CB304A'
        },
        {
            color: 'FED7C8'
        },
        {
            color: 'C7BDBD'
        },
        {
            color: '3DCBBE'
        },
        {
            color: '264B4F'
        },
        {
            color: '389389'
        },
        {
            color: '85BEAE'
        },
        {
            color: 'F2DABA'
        },
        {
            color: 'F2A97F'
        },
        {
            color: 'D85F52'
        },
        {
            color: 'D92E37'
        },
        {
            color: 'FC9736'
        },
        {
            color: 'F7BD69'
        },
        {
            color: 'A4D09C'
        },
        {
            color: '4C8A67'
        },
        {
            color: '25608A'
        },
        {
            color: '75C8C6'
        },
        {
            color: 'F5E4B7'
        },
        {
            color: 'E69041'
        },
        {
            color: 'E56013'
        },
        {
            color: '11101D'
        },
        {
            color: '630609'
        },
        {
            color: 'C9240E'
        },
        {
            color: 'EC4B17'
        },
        {
            color: '281A1C'
        },
        {
            color: '4F556F'
        },
        {
            color: '64739B'
        },
        {
            color: 'CDBAC7'
        },
        {
            color: '946F43'
        },
        {
            color: '66533C'
        },
        {
            color: '173A2F'
        },
        {
            color: '153944'
        },
        {
            color: '27548D'
        },
        {
            color: '438AAC'
        }
    ]

    let slider = document.getElementById('js-tray'),
        sliderItems = document.getElementById('js-tray-slide'),
        difference;

    const TRAY = document.getElementById('js-tray-slide');

    function buildColors(colors) {
        for (let [i, color] of colors.entries()) {
            let swatch = document.createElement('div');
            swatch.classList.add('tray__swatch');
            swatch.style.background = '#' + color.color;
            swatch.setAttribute('data-key', i);
            TRAY.append(swatch);
        }
    }

    buildColors(colors);

    function selectColor( event)
    {
        let activeObject = canvas.getActiveObject(),
            { color } = colors[parseInt(event.target.dataset.key)];
        if( !activeObject)
            return;
        
        if( activeObject.type == "image")
        {
            activeObject.filters.push(new fabric.Image.filters.Tint({ color: `#${color}` }));
            activeObject.applyFilters(canvas.renderAll.bind(canvas));
        }
        else if( activeObject.type == "text")
        {
            activeObject.setColor( `#${ color}`);
            canvas.renderAll.bind( canvas)();
        }
    }

    function slide(wrapper, items) {
        var posY1 = 0,
            posY2 = 0,
            posInitial,
            threshold = 20,
            posFinal,
            slides = items.getElementsByClassName('tray__swatch');

        items.onmousedown = dragStart;
        items.addEventListener('touchstart', dragStart);
        items.addEventListener('touchend', dragEnd);
        items.addEventListener('touchmove', dragAction);

        function dragStart(e) {
            e = e || window.event;
            selectColor( e);
            e.preventDefault();

            posInitial = items.offsetTop;
            difference = sliderItems.offsetHeight - slider.offsetHeight;
            difference = difference * -1;
            if (e.type == 'touchstart')
                posY1 = e.touches[0].clientY;
            else {
                posY1 = e.clientY;
                document.onmouseup = dragEnd;
                document.onmousemove = dragAction;
            }
        }

        function dragAction(e) {
            e = e || window.event;
            e.preventDefault();
            if (e.type == 'touchmove') {
                posY2 = posY1 - e.touches[0].clientY;
                posY1 = e.touches[0].clientY;
            }
            else {
                posY2 = posY1 - e.clientY;
                posY1 = e.clientY;
            }

            if (items.offsetTop - posY2 <= 0 && items.offsetTop - posY2 >= difference)
                items.style.top = (items.offsetTop - posY2) + 'px';
        }

        function dragEnd(e) {
            e.preventDefault();
            posFinal = items.offsetTop;
            if (posFinal - posInitial < -threshold) {
            }
            else if (posFinal - posInitial > threshold) {
            }
            else
                items.style.top = posInitial + 'px';

            document.onmouseup = null;
            document.onmousemove = null;
        }
    }

    slide(slider, sliderItems);

    let cartItemCount = $( '.cart-item-count').attr( 'data-cart-qty');
    assignCartCount();
    
    function assignCartCount()
    {
        let cartText = document.getElementById( 'cart-count'),
            newX = 5 - 8 * ( cartItemCount.length - 1);
        
        cartText.setAttribute( 'x', newX.toString());
        cartText.innerHTML = cartItemCount;
    }
    
    function updateCartContent()
    {
        let design = canvas.toDataURL( 'image/png');
        
        $.ajax({
          method: "POST",
          url: ajax_object.ajax_url,
          data: { 'design': design, 'action': 'ajax_executor', 'productID': packet[ 'productID'] }
        })
        .done(function( _)
        {
            $( '.loader').hide();
            cartItemCount = ( parseInt( cartItemCount) + 1).toString();
            assignCartCount();
        });
    }
    
    $( '#add-to-cart').click( () => {
        canvas.discardActiveObject().renderAll();
        updateCartContent();
        $( '.loader').show();
        $( '#checkout-image').addClass( 'checkout-image');
    });
    
    $( '#checkout-btn').click( () => {
        $( '#checkoutDialog').modal('hide'); 
        $( '.loader').show();
        window.location.href = `${ window.origin}/checkout`;
        
    });
    
    $( '.cart-item-count').click( () => {
        $( '.loader').show();
        window.location.href = `${ window.origin}/cart`;
    });
    
});