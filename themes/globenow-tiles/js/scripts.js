var win = null,
	scroll = 'yes',
	pos = 'center',
	w = 500,
	h = 400
	myname = 'template_window';
	
function share(mypage) {
    if (pos == "random") {
        LeftPosition = (screen.width) ? Math.floor(Math.random() * (screen.width - w)) : 100;
        TopPosition = (screen.height) ? Math.floor(Math.random() * ((screen.height - h) - 75)) : 100;
    }
    if (pos == "center") {
        LeftPosition = (screen.width) ? (screen.width - w) / 2 : 100;
        TopPosition = (screen.height) ? (screen.height - h) / 2 : 100;
    } else if ((pos != "center" && pos != "random") || pos == null) {
        LeftPosition = 0;
        TopPosition = 20
    }
    settings = 'width=' + w + ',height=' + h + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=no';
    win = window.open(mypage, myname, settings);
};

// DOM Ready
$(function() {
	
	// Share button states
	$('.share-reaction-icon.start').click(function(){
		$(this).toggleClass('on');
		return false;
	})
	
	// Tag tooltips
	if(!$('body').hasClass('mobile')){
		$('.tags .tag').tooltip({
			title:'Add tag to filters'
		});
	}
	/* - Last Seen Post - Cookie: Storing the latest post and changing colours - */
	if($('body').hasClass('home')){
	var lastSeenPostId = parseInt($.cookie('globeolympics-lastseenpost'));
	var newLastSeenPostId = 0;
	// If no lastseenpost, set to the most recent
	if(!$('#loop article').eq(0).hasClass('noposts') && (isNaN(lastSeenPostId) || lastSeenPostId == '' || typeof lastSeenPostId == 'undefined')) lastSeenPostId = $('#loop article').eq(0).attr('id').split('-')[1];
	// Get latest post if exists
	if($('#loop article').length > 1){
		$('#loop article').each(function(){
			thisId = parseInt($(this).attr('id').split('-')[1]);
			if(thisId > lastSeenPostId){
				if(thisId > newLastSeenPostId) newLastSeenPostId = thisId;
				$(this).addClass('unseen');
			}
		});
	}
	}

	/* If new posts, apply new cookie - only on homepage */
	if($('body').hasClass('home')){
		if(newLastSeenPostId != 0) $.cookie('globeolympics-lastseenpost',newLastSeenPostId);
		// Waypoint trigger back to normal
		var initWaypoint = true;
		$(window).scroll(function(){
			if(initWaypoint && $(window).scrollTop() > 0){
				$('#loop article.unseen').waypoint(function(){
					$(this).removeClass('unseen');
				},{'offset':'60%'});
				initWaypoint = false;
			}
		})	
		$('#loop article.unseen').hover(function(){
			$(this).removeClass('unseen');
		});
	}
	
	/* Keyboard/arrow navigation */
	var trackSpot = 0;
	$("body").keydown(function(e) {
	  if(e.keyCode == 37) { // left
    	trackSpot--;
    	$("body,html").stop().animate({
	      scrollTop: $('#loop article').eq(trackSpot).offset().top-50-$('#mobile-header').height()
   	  	});
  	  } else if(e.keyCode == 39) { // right
    	trackSpot++;
    	$("body,html").stop().animate({
	      scrollTop: $('#loop article').eq(trackSpot).offset().top-50-$('#mobile-header').height()
   	  	});
  	  }
	});
	
	/* YouTube video resize */
	var $allVideos = $("iframe[src^='//www.youtube.com']"),
	    // The element that is fluid width
	    $fluidEl = $("#loop");
	// Figure out and save aspect ratio for each video
	$allVideos.each(function() {
	$(this)
		.data('aspectRatio', this.height / this.width)
		// and remove the hard coded width/height
		.removeAttr('height')
		.removeAttr('width');
	});
	// When the window is resized
	$(window).resize(function() {
		var newWidth = $fluidEl.width();
		// Resize all videos according to their own aspect ratio
		$allVideos.each(function() {
			var $el = $(this);
			$el
				.width(newWidth)
				.height(newWidth * $el.data('aspectRatio'));
		});
	// Kick off one resize to fix all videos on page load
	}).resize();
	
	/* - Search button - */
	$('#menu-search-button').click(function(){
		if(!$(this).hasClass('selected')){
			$('#search-box').css('visibility','visible').animate({
				'marginTop':0
			},250);
			$('.search-input').focus();
		} else {
			$('#search-box').animate({
				'marginTop':-50
			},250,function(){
				$('#search-box').css('visibility','hidden');
			});
		}
		$(this).toggleClass('selected');
		return false;
	})
	$('.search-cancel').click(function(){
		$('#menu-search-button').removeClass('selected');
		$('#search-box').animate({
			'marginTop':-50
		},250,function(){
			$('#search-box').css('visibility','hidden');
		});
		return false;
	});
	$('form.search').keyup(function(e){
		if(e.keyCode == 13){
			$(this).submit();
		}
	});
	
	/* *********************** SHARING *************************** */
	//add sharing to item
	function addSharing(id,link,title,description) {
		var ua = new gigya.socialize.UserAction();
		ua.setLinkBack(link); 
		ua.setTitle(title);
		ua.setDescription(description);

		var params = { 
			userAction:ua,
			noButtonBorders:true,
			showCounts:'none',
			iconsOnly:true,
			layout: 'vertical',
			shareButtons:
			[
				{
					provider:'facebook',
					tooltip:'Share this on Facebook',
					action:'share',
					iconImgUp:'http://beta.images.theglobeandmail.com/static/ROB/interactives/crisis/images/fb20.png',
					iconImgOver:'http://beta.images.theglobeandmail.com/static/ROB/interactives/crisis/images/fb20over.png'
				},
				{
					provider:'twitter',
					tooltip:'Share this on Twitter',
					iconImgUp:'http://beta.images.theglobeandmail.com/static/ROB/interactives/crisis/images/tw20.png',
					iconImgOver:'http://beta.images.theglobeandmail.com/static/ROB/interactives/crisis/images/tw20over.png'
				}			        
			],
			containerID: id,
		    cid:''
		};

		gigya.socialize.showShareBarUI(params);
	}
	
	$('body,html').on('click','.gig-button-container .tw a',function(){
		var shareUrl = $(this).attr('href');
		share(shareUrl);
		return false;
	});
	
	$('body,html').on('click','.gig-button-container .fb a',function(){
		var shareUrl = $(this).attr('href');
		share(shareUrl);
		return false;
	});
	
	$('body,html').on('click','.gig-button-container .email a',function(){
		
	});
	$('body').on('click','.gig-button-container .lk a',function(){
		$(this).parent().append('<div class="share-link"><input readonly type="text" value="' + $(this).data('url') + '" /></div>');
		$(this).parent().find('input').focus();
		return false;
	});
	$('body').click(function(e){
		if(!$(e.target).is('input')){
			$('.share-link').remove();
		}
	})

	// Omniture page depth tracking
	function interactiveTracking(description) {
	    s.linkTrackVars = "";   // we don't want to send any vars or props
	    s.linkTrackEvents = ""; // we don't want to trigger any events
	    s.tl(true, 'o', description);
	}

	$('article').waypoint(function(){
		if(!$(this).hasClass('omnitureSeenThisPost')){
			interactiveTracking("Olympic Blog - Depth - " + ($(this).index('article')+1));
 		}
		$(this).addClass('omnitureSeenThisPost');
	},{'offset':'50%'});
	
	$('#home-highlights a').click(function(){
		interactiveTracking("Olympic Blog - Link - Big Moments");
	});
	$('#home-nav a').click(function(){
		interactiveTracking("Olympic Blog - Link - Live Updates");
	});
	$('#filterSelect').change(function(){
		interactiveTracking("Olympic Blog - Link - Tag dropdown - " + $(this).val());
	});	
	$('.tag a').click(function(){
		interactiveTracking("Olympic Blog - Link - Tag link - " + $(this).text());
	});
	
	// Check for Twitter cards and resize
	var twitterCardCount = 0;
	var twitterInterval = setInterval(function(){
		if($('body').find('iframe').hasClass('twitter-tweet')){
			clearInterval(twitterInterval);
			$('body').find('iframe.twitter-tweet').each(function(){
				$(this).css('max-width','98%').css('max-width','100%');
			});
		} else if(twitterCardCount > 5){
			clearInterval(twitterInterval);
		}
	},1000)
	
});