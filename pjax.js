main.pjax = {};
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
};
$(document).pjax('a[href]:not(a[target="_blank"], a[no-pjax]), a[use-pjax]', {
	container: main.pjax.container,
	fragment: main.pjax.container,
	timeout: 10000,
	cache: true,
	storage: true
}).on('pjax:start',function(){
	if(!main.pjax.on){
		main.pjax.on = true;
		$('#main')
			.css('visibility','hidden')
			.css('opacity','0');
		NProgress.start();
	}
}).on('pjax:end',function(){
	NProgress.done();
	main.pjax.on = false;
	main.load();
}).on('pjax:error', function (e, x, s, t, o) {
    const code = x.status;
    switch (code) {
        case 403:
        case 404:
            // https://github.com/defunkt/jquery-pjax/issues/627
            e.preventDefault();
            o.success(x.responseText, s, x);
            return false;
    }
});
