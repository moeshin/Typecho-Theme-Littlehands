main.pjax = new Object();
main.pjax.container = '#body';
main.pjax.load = function(url,timeout){
  	url = url||location.href;
  	timeout = timeout||10000;
	$.pjax({
		url: url.replace(/\?.*/mg,''),
		fragment: main.pjax.container,
		container: main.pjax.container,
		timeout: timeout
	});
}
$(document).pjax('a[href^="' + siteUrl + '"]:not(a[target="_blank"], a[no-pjax])', {
    container: main.pjax.container,
    fragment: main.pjax.container,
    timeout: 10000,
	cache: true,
	storage: true
}).on('pjax:start',function(){
	if(!main.pjax.on){
		main.pjax.on = true;
		$('#main').css('visibility','hidden');
		$('#main').css('opacity','0');
		NProgress.start();
	}
}).on('pjax:end',function(){
	NProgress.done();
	main.pjax.on = false;
	main.load();
});