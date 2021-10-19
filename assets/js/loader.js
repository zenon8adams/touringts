/// Loader
(function()
{
    
    window.displayInfo = function( message, once = false)
    {
        if( displayInfo.once)
            return;
        displayInfo.once = once;
        let infoPane = document.getElementById( 'info-pane'),
            time = ( message?.split( ' ').length / 150) * 60;
        infoPane.children[ 0].innerHTML = message;
        $( infoPane)
        .css( 'display', 'flex')
        .animate({
            height: `${infoPane.children[ 0].clientHeight + 15}px`,
        }, 800, function()
        {
            setTimeout(function(){
                arguments[ 0].animate({
                    height: '0'
                }, 800);
            }, time * 1000, $( this));
        });
    }
    
    let loader = document.getElementById( 'loader'),
        ctx = loader.getContext( '2d');

    document.body.style.overflow = 'hidden';
    function drawWaterDrop( ctx, opts)
    {
        let options = {
            x: ctx.canvas.width/2,
            y: ctx.canvas.height/2,
            w: 20,
            h: 20,
            radius: 10,
            fill: 'green',
            riot: [],
            singleFill: false,
            count: 5,
            idx: 0,
            angle: 0,
        };
        Object.assign( options, opts);
        
        let { x, y, w, h, radius, fill, riot, singleFill, count, idx, angle} = options,
            sangle;
        sangle = count == 1 ? sangle = angle : sangle = 2 * Math.PI / count;
        if( count == 0)
            return;

        for( let i = 0; i < count; ++i)
        {
             let px = x + radius * Math.cos( angle),
                 py = y + radius * Math.sin( angle);                        

            ctx.beginPath();

            ctx.translate( px, py);
            ctx.rotate( angle);

            ctx.moveTo( 0, 0 - .4 * h);
            ctx.bezierCurveTo( 0 + .5 * w, 0 - .2 * h, 0, 0 + .5 * h, 0, 0 + .6 * h);
            ctx.bezierCurveTo( 0, 0 + .5 * h, 0 - .5 * w, 0 - .2 * h, 0, 0 - .4 * h);
            
            ctx.rotate( -angle);
            ctx.translate( -px, -py);
            
            angle += sangle;
            
            if( singleFill)
            {
                if( i == idx)
                    ctx.fillStyle = fill;
                else
                    ctx.fillStyle = 'transparent';
            }
            else
                ctx.fillStyle = riot[ i] ?? fill;

            ctx.fill();
        }
    }

    let count = 10,
        idx = 0,
        dots = 3;

    setInterval(() => {
        ctx.clearRect( 0, 0, loader.width, loader.height);
            drawWaterDrop( ctx, { 
            idx: idx, 
            radius: 50, 
            count: 10,
            fill: 'lightseagreen',
            singleFill: true
        });
        idx = (idx + 1) % count;
        dots = ( dots + 1) % 4;
    }, 70);
    setInterval(() => document.querySelector( '.dot').innerText = '.'.repeat( dots), 1500);

    window.onload = function( event)
    {
        setTimeout( () => {
            $( '.loader').hide();
            document.body.style.overflow = 'scroll';
            window.pageMessage && displayInfo( window.pageMessage);
        }, 500);
        
    }

})();

