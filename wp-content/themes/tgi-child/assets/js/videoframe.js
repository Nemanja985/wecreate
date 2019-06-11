jQuery(document).ready(function ($) {
	window.extractVideoFrames = function(vidUrl, frOffsets, frameWidth) {

	  function extractFrame(video, canvas, offset) {
	    return new Promise((resolve, reject) => {
	      video.onseeked = event => {
	        var ctx = canvas.getContext('2d');
	        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
	        canvas.toBlob(blob => {
	          resolve({offset: offset, imgUrl: canvas.toDataURL() , blob: blob});
	        }, "image/png");
	      };
	      video.currentTime = offset;
	    });
	  };

	  async function serialExtractFrames(video, canvas, offsets) {
	    var frames = {};
	    var lastP = null;

	    for (var offset of offsets) {
	      if (offset < video.duration) {
	        if (lastP) {
	          var f = await lastP
	          frames[f.offset] = f;
	        }
	        lastP = extractFrame(video, canvas, offset);
	      }
	    }
	    if (lastP) {
	      var f = await lastP;
	      frames[f.offset] = f;
	      lastP = null;
	    }
	    return frames;
	  };

	  return new Promise((resolve, reject) => {
	    var vvid = document.createElement("video");
	    var vcnv = document.createElement("canvas");
	    vvid.onloadedmetadata = event => {
	      var aspect_ratio = vvid.videoWidth/vvid.videoHeight;
	      vcnv.width = frameWidth !== undefined ? frameWidth : vvid.videoWidth;
	      vcnv.height = vcnv.width/aspect_ratio;
	      if (vvid.duration) {
	        serialExtractFrames(vvid, vcnv, frOffsets).then(resp => {
	          resolve(resp);
	        })
	      }
	    }
	    vvid.src = vidUrl;
	  });
	};

	//Drag
	$.fn.attachDragger = function(){
	    var attachment = false, lastPosition, position, difference;
	    $( $(this).selector ).on("mousedown mouseup mousemove",function(e){
	        if( e.type == "mousedown" ) attachment = true, lastPosition = [e.clientX, e.clientY];
	        if( e.type == "mouseup" ) attachment = false;
	        if( e.type == "mousemove" && attachment == true ){
	            position = [e.clientX, e.clientY];
	            difference = [ (position[0]-lastPosition[0]), (position[1]-lastPosition[1]) ];
	            $(this).scrollLeft( $(this).scrollLeft() - difference[0] );
	            $(this).scrollTop( $(this).scrollTop() - difference[1] );
	            lastPosition = [e.clientX, e.clientY];
	            console.log(lastPosition)
	        }
	    });
	     $( $(this).selector ).on("mouseenter mouseleave", function(){
	        attachment = false;
	    });
	}
	// $(document).ready(function(){
	// 	$('.video-editor-wrapper').attachDragger(function(){
	// 		console.log('dragging');
	// 	})
	// })
});