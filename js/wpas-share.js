(function(d,s,id){
	var js,fjs = d.getElementsByTagName(s)[0];
	if (!d.getElementById(id)){
		js = d.createElement(s);
		js.id = id;
		js.src = 'https://platform.twitter.com/widgets.js';
		fjs.parentNode.insertBefore(js,fjs);
	}
})(document,'script','twitter-wjs');

(function(d,s,id){
	var js,fjs = d.getElementsByTagName(s)[0];
	if (!d.getElementById(id)){
		js = d.createElement(s);
		js.id = id;
		js.src = 'http://platform.tumblr.com/v1/share.js';
		fjs.parentNode.insertBefore(js,fjs);
	}
})(document,'script','tumblr-sjs');

(function(d){
    var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
    p.type = 'text/javascript';
    p.async = true;
    p.src = '//assets.pinterest.com/js/pinit.js';
    f.parentNode.insertBefore(p, f);
}(document));

(function() {
    var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
    li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
})();


// Facebook Share
function fbShare(url, title, descr, winWidth, winHeight) {
    var winTop = (screen.height / 2) - (winHeight / 2);
    var winLeft = (screen.width / 2) - (winWidth / 2);
    window.open('http://www.facebook.com/sharer.php?s=100&p[title]=' + title + '&p[summary]=' + descr + '&p[url]=' + url , 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
}

// Twitter Share
function twitterShare(url, descr, winWidth, winHeight ) {
    var winTop = (screen.height / 2) - (winHeight / 2);
    var winLeft = (screen.width / 2) - (winWidth / 2);
    window.open('https://twitter.com/share?url=' + url + '&text=' + descr , 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
}

// Google+ Share
function gplusShare(url, winWidth, winHeight ) {
    var winTop = (screen.height / 2) - (winHeight / 2);
    var winLeft = (screen.width / 2) - (winWidth / 2);
    window.open('https://plus.google.com/share?url=' + url , 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
}

// Pinterest Share
function pinterestShare(url, img, descr, winWidth, winHeight ) {
    var winTop = (screen.height / 2) - (winHeight / 2);
    var winLeft = (screen.width / 2) - (winWidth / 2);
    window.open('//pinterest.com/pin/create/button/?url=' + url + '&media=' + img + '&description=' + descr, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
}

// Stumbleupon Share
function stumbleuponShare(url, winWidth, winHeight ) {
    var winTop = (screen.height / 2) - (winHeight / 2);
    var winLeft = (screen.width / 2) - (winWidth / 2);
    window.open('http://www.stumbleupon.com/submit?url=' + url, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
}

// LinkedIn Share
function linkedinShare(url, title, descr, winWidth, winHeight ) {
    var winTop = (screen.height / 2) - (winHeight / 2);
    var winLeft = (screen.width / 2) - (winWidth / 2);
    window.open('http://www.linkedin.com/shareArticle?mini=true&url=' + url + '&title=' + title + '&summary=' + descr, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
}

// Reddit Share
function redditShare(url, winWidth, winHeight ) {
    var winTop = (screen.height / 2) - (winHeight / 2);
    var winLeft = (screen.width / 2) - (winWidth / 2);
    window.open('http://www.reddit.com/submit?url=' + url , 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
}
