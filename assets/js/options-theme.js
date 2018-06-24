function notice(text,type,highlight){
	//type:success|notice|error
	type = type||'notice';
  	highlight = highlight||false;
	var head = $('.typecho-head-nav'),
		p = $('<div id="typecho-notice" class="message popup ' + type + '">'
		+ '<ul><li>' + text + '</li></ul></div>'), offset = 0;
		
	if (head.length > 0) {
		p.insertAfter(head);
		offset = head.outerHeight();
	} else {
		p.prependTo(document.body);
	}
	function checkScroll () {
		if ($(window).scrollTop() >= offset) {
			p.css({
				'position'  :   'fixed',
				'top'       :   0
			});
		} else {
			p.css({
				'position'  :   'absolute',
				'top'       :   offset
			});
		}
	}
	$(window).scroll(function () {
		checkScroll();
	});
	checkScroll();
	p.slideDown(function () {
		var t = $(this), color = '#C6D880';
		if (t.hasClass('error')) {
			color = '#FBC2C4';
		} else if (t.hasClass('notice')) {
			color = '#FFD324';
		}
		t.effect('highlight', {color : color})
			.delay(5000).fadeOut(function () {
			$(this).remove();
		});
	});
	if (highlight) {
		$('#typecho-notice').effect('highlight', 1000);
	}
}

//Emoji
var emoji = Object();
emoji.package = Object();
emoji.package.more = Object();
emoji.display = function(emoji){
	if(emoji.is(':checked')){
		$('#emoji-edit').css('display','block');
	}else{
		$('#emoji-edit').css('display','none');
	}
}
emoji.package.set = function(){
	var index = $(this).parent().parent().index();
	$('#emoji-edit-package tbody tr').removeClass('checked');
	$('#emoji-edit-package input[type=checkbox]').prop('checked',false);
	$('#emoji-edit-package tbody tr').eq(index).addClass('checked');
	emoji.canClickTr = false;
	emoji.package.index = index;
	$('#name-0-1').val(emoji.data[index].name);
	$('#url-0-1').val(emoji.data[index].url);
	$('#class-0-1').val(emoji.data[index].class);
}
emoji.package.table = function(){
	emoji.checked = Array();
	$('#emoji-edit-package table input:checked').each(function(){
		emoji.checked.push(this.value);
	});
	if(emoji.data){
		var html = '';
		for (var i = 0;i < emoji.data.length;i++){
			html += '<tr><td><input type="checkbox" value="'+i+'"></td><td>'+(i+1)+'</td><td><span title="点击编辑">'+emoji.data[i].name+'</span></td></tr>';
		}
	}
	$('#emoji-edit-package tbody').html(html);
	emoji.afterTable();
}
emoji.package.isSelected = function(){
	if(emoji.package.index >= 0){
		return true;
	}else{
		notice('错误：请点击一个表情包再进行操作！','error');
		return false;
	}
}
emoji.put = function(isPackage){
  	isPackage = (isPackage === undefined)||isPackage;
	if(isPackage){
		$('#name-0-1').val('');
		$('#url-0-1').val('');
		$('#class-0-1').val('');
		emoji.package.index = -1;
	}else{
		$('#value-0-1').val('');
		emoji.package.more.index = -1;
	}
}
emoji.package.move = function(arg){
	//-1.上移 1.下移
	if(((arg == -1 && emoji.package.index !== 0)||(arg === 1 && emoji.package.index !== emoji.data.length-1)) && emoji.package.isSelected()){
		emoji.data[emoji.package.index] = emoji.data.splice(emoji.package.index + arg, 1, emoji.data[emoji.package.index])[0];
		emoji.package.index += arg;
		emoji.package.table();
	}
}
emoji.package.save = function(){
	if(emoji.package.index >= 0){
		emoji.data[emoji.package.index].name = $('#name-0-1').val();
		emoji.data[emoji.package.index].url = $('#url-0-1').val().replace(/\/$/i,'')+'/';
		emoji.data[emoji.package.index].class = $('#class-0-1').val();
	}
}
emoji.package.more.set = function(){
	var index = $(this).parent().parent().index();
	$('#emoji-edit-package-more tbody tr').removeClass('checked');
	$('#emoji-edit-package-more input[type=checkbox]').prop('checked',false);
	$('#emoji-edit-package-more tbody tr').eq(index).addClass('checked');
	emoji.canClickTr = false;
	emoji.package.more.index = index;
	$('#value-0-1').val(emoji.data[emoji.package.index].value[index]);
}
emoji.package.more.move = function(arg){
	//-1.上移 1.下移
	if(((arg == -1 && emoji.package.more.index !== 0)||(arg === 1 && emoji.package.more.index !== emoji.data[emoji.package.index].value.length-1)) && emoji.package.more.isSelected()){
		emoji.data[emoji.package.index].value[emoji.package.more.index] = emoji.data[emoji.package.index].value.splice(emoji.package.more.index + arg, 1, emoji.data[emoji.package.index].value[emoji.package.more.index])[0];
		emoji.package.more.index += arg;
		emoji.package.more.table();
	}
}
emoji.package.more.table = function(){
	var html = '';
	if(emoji.package.index >= 0 && emoji.data[emoji.package.index].value){
		var value = emoji.data[emoji.package.index].value;
		for(var i = 0;i < value.length;i++){
			var url = emoji.data[emoji.package.index].url.replace(/{themeUrl}/gm,themeUrl).replace(/{siteUrl}/gm,siteUrl)+emoji.data[emoji.package.index].value[i];
			html += '<tr><td><input type="checkbox" value="'+i+'"></td><td>'+(i+1)+'</td><td><img title="点击编辑" class="'+emoji.data[emoji.package.index].class+'" src="'+url+'"></td></tr>';
		}
	}
	$('#emoji-edit-package-more tbody').html(html);
	emoji.afterTable();
}
emoji.package.more.isSelected = function(){
	if(emoji.package.more.index >= 0){
		return true;
	}else{
		notice('错误：请点击一个表情再进行操作！','error');
		return false;
	}
}
emoji.package.more.save = function(){
	if(emoji.package.more.index >= 0) emoji.data[emoji.package.index].value[emoji.package.more.index] = $('#value-0-1').val();
}
emoji.afterTable = function(){
	if(emoji.package.index !== -1) $('#emoji-edit-package tbody tr').eq(emoji.package.index).addClass('checked');
	if(emoji.package.more.index !== -1) $('#emoji-edit-package-more tbody tr').eq(emoji.package.more.index).addClass('checked');
	$('#emoji-edit table input').unbind('click');
	$('#emoji-edit tbody tr').unbind('click');
	$('#emoji-edit table input').click(function(){
		$('#emoji-edit tbody tr.checked input:not(:checked)').parent().parent().removeClass('checked');
		emoji.canClickTr = false;
		if($(this).is(':checked')){
			$(this).parent().parent().addClass('checked');
		}else{
			$(this).parent().parent().removeClass('checked');
			if(emoji.package.index !== -1) $('#emoji-edit-package tbody tr').eq(emoji.package.index).addClass('checked');
			if(emoji.package.more.index !== -1) $('#emoji-edit-package-more tbody tr').eq(emoji.package.more.index).addClass('checked');
		}
	});
	$('#emoji-edit tbody tr').click(function(){
		if(emoji.canClickTr){
			$('input',this).click();
		}else{
			emoji.canClickTr = true;
		}
	});
	$('#emoji-edit-package span').click(emoji.package.set);
	$('#emoji-edit-package-more img').click(emoji.package.more.set);
}
emoji.getData = function(){
	var dom = $('<data>'+$('[name="emoji"]').val()+'</data>');
	emoji.data = Array();
	$('.emoji-package',dom).each(function(){
		var package = Object();
		var span = $('.emoji-btn span',this);
		package.name = span.text();
		package.url = span.attr('data-url');
		package.class = $('img',this).attr('class');
		package.value = Array();
		$('img',this).each(function(){
			package.value.push($(this).attr('data-src').replace(package.url,''));
		});
		emoji.data.push(package);
	});
}
emoji.setData = function(){
	var html = '';
	for(var i = 0;i < emoji.data.length;i++){
		emoji.data[i].url = emoji.data[i].url?emoji.data[i].url:'';
		emoji.data[i].class = emoji.data[i].class?emoji.data[i].class:'';
		html += '<div class="emoji-package"><div class="emoji-btn"><span data-url="';
		html += emoji.data[i].url+'">'+emoji.data[i].name;
		html += '</span></div><div class="emoji-body"><ul>';
		for(var j = 0;j < emoji.data[i].value.length;j++){
			emoji.data[i].value[j] = emoji.data[i].value[j]?emoji.data[i].value[j]:'';
			html += '<li><img class="'+emoji.data[i].class+'" data-src="'+emoji.data[i].url+emoji.data[i].value[j]+'"></li>';
		}
		html += '</ul></div></div>';
	}
	$('[name="emoji"]').val(html);
}
emoji.canClickTr = true;


emoji.getData();
emoji.input = $('input[id="advanced-emoji"]');
emoji.input.parent().parent().parent().after('<ul class="typecho-option"id="emoji-edit"><div class="emoji-edit-display"><label class="typecho-label">编辑评论表情</label><ul class="typecho-option-tabs clearfix"><li class="current"><span class="current">视图模式</span></li><li><span>Json模式</span></li><li><span>隐藏</span></li></ul></div><div id="emoji-edit-package"><div class="col-mb-12 col-tb-8"><div class="emoji-batch"><input type="checkbox"><button type="button">删除选中项</button></div><div class="typecho-table-wrap"><table class="typecho-list-table"><colgroup><col width="20"><col width="50"><col width></colgroup><thead><tr><th></th><th>顺序</th><th>表情包名</th></tr></thead><tbody></tbody></table></div></div><div class="col-mb-12 col-tb-4"><ul class="typecho-option"><li><label class="typecho-label"for="name-0-1">表情包名*</label><input id="name-0-1"type="text"class="text"></li></ul><ul class="typecho-option"><li><label class="typecho-label"for="class-0-1">样式</label><input id="class-0-1"type="text"class="text"></li></ul><ul class="typecho-option"><li><label class="typecho-label"for="url-0-1">表情包路径</label><input id="url-0-1"name="url"type="text"class="text"><p class="description">如果表情不是在同一个目录下，则不用填写此项<br>内容替换：<br>{siteUrl}：网站地址<br>{themeUrl}：主题地址<br>如：{themeUrl}/emoji/paopao</p></li></ul><ul class="typecho-option"><li><label class="typecho-label"for="name-0-1">操作</label><button type="button">上移</button><button type="button">下移</button><br><button type="button">添加</button><button type="button">删除</button><button type="button">编辑</button></li></ul></div></div><div id="emoji-edit-package-more"style="display: none;"><div class="col-mb-12 col-tb-8"><div class="emoji-batch"><input type="checkbox"><button type="button">删除选中项</button></div><div class="typecho-table-wrap"><table class="typecho-list-table"><colgroup><col width="20"><col width="50"><col width></colgroup><thead><tr><th></th><th>顺序</th><th>表情</th></tr></thead><tbody></tbody></table></div></div><div class="col-mb-12 col-tb-4"><ul class="typecho-option"><li><label class="typecho-label"for="value-0-1">表情文件名*</label><input id="value-0-1"name="name"type="text"class="text"><p class="description">最终表情链接为：表情包路径+表情文件名<br>所以若没有填写表情包路径，则请填写表情完整路径</p></li></ul><ul class="typecho-option"><li><label class="typecho-label"for="name-0-1">操作</label><button type="button">上移</button><button type="button">下移</button><br><button type="button">添加</button><button type="button">删除</button><button type="button">返回</button></li></ul></div></div><div id="emoji-edit-json" style="display: none;"><textarea></textarea><p class="description">Json格式错误将不会保存！</p></div></ul>');
emoji.display(emoji.input);
emoji.input.click(function(){
	emoji.display(emoji.input);
});
$('input').keydown(function(e) {  
	if (e.keyCode == 13) {
		e.returnValue = false;
		e.preventDefault();
	}
});
emoji.package.table();
$('#emoji-edit-package button').click(function(){
	switch($(this).text()){
		case '编辑':
			if(emoji.package.isSelected()){
				$('#emoji-edit-package').css('display','none');
				$('#emoji-edit-package-more').css('display','block');
				emoji.package.more.table();
			}
			break;
		case '添加':
			var name = $('#name-0-1').val();
			if(!name){
				notice('错误：必须填写表情包名','error');
				$('#name-0-1').focus();
				break;
			}
			var a = {name: $('#name-0-1').val(),url:$('#url-0-1').val().replace(/\/$/i,'')+'/',class:$('#class-0-1').val(),value:Array()};
			emoji.data.push(a);
			emoji.put();
			emoji.package.table();
			break;
		case '删除':
			if(emoji.package.isSelected()){
				emoji.data.splice(emoji.package.index,1);
				emoji.put();
				emoji.package.table();
			}
			break;
		case '上移':
			emoji.package.move(-1);
			break;
		case '下移':
			emoji.package.move(1);
			break;
	}
});
$('#emoji-edit-package-more button').click(function(){
	switch($(this).text()){
		case '返回':
			$('#emoji-edit-package-more .emoji-batch input').prop('checked',false);
			$('#emoji-edit-package').css('display','block');
			$('#emoji-edit-package-more').css('display','none');
			emoji.put(false);
			break;
		case '上移':
			emoji.package.more.move(-1);
			break;
		case '下移':
			emoji.package.more.move(1);
			break;
		case '添加':
			var value = $('#value-0-1').val();
			if(!value){
				notice('错误：必须填写表情文件名','error');
				$('#value-0-1').focus();
				break;
			}
			emoji.data[emoji.package.index].value.push(value);
			emoji.put(false);
			emoji.package.more.table();
			break;
		case '删除':
			if(emoji.package.more.isSelected()){
				emoji.data[emoji.package.index].value.splice(emoji.package.more.index,1);
				emoji.put(false);
				emoji.package.more.table();
			}
			break;
	}
});
$('.emoji-batch input').click(function(){
	if($(this).is(':checked')){
		$('table input:not(:checked)',$(this).parent().parent()).click();
	}else{
		$('table input:checked',$(this).parent().parent()).click();
	}
});
$('.emoji-batch button').click(function(){
	if(confirm("删除是不可恢复的，你确认要删除吗？")){
		var isPackage = false;
		var input = $('table input:checked',$(this).parent().parent());
		if($(this).parent().parent().parent().attr('id') == 'emoji-edit-package'){
			isPackage = true;
		}
		for(var i = input.length-1 ;i >= 0;i--){
			if(isPackage){
				emoji.data.splice($(input[i]).val(),1);
			}else{
				emoji.data[emoji.package.index].value.splice($(input[i]).val(),1);
			}
		}
		$('.emoji-batch input').prop('checked',false);
		if(isPackage){
			emoji.package.table();
		}else{
			emoji.package.more.table();
		}
		emoji.put(isPackage);
	}
});
$('#emoji-edit-package .col-mb-12.col-tb-4 input').blur(function(){
	if(!emoji.isAdd){
		emoji.package.save();
		emoji.package.table();
	}
	emoji.isAdd = false;
});
$('#emoji-edit-package-more .col-mb-12.col-tb-4 input').blur(function(){
	if(!emoji.isAdd){
		emoji.package.more.save();
		emoji.package.more.table();
	}
	emoji.isAdd = false;
});
$('#emoji-edit button').mousedown(function(e){
	if($(this).text() == '添加'){
		emoji.isAdd = true;
	}
});
$('.emoji-edit-display ul.typecho-option-tabs.clearfix span').click(function(){
	var before = $('.emoji-edit-display ul.typecho-option-tabs.clearfix li.current').text();
	if(!$(this).parent().hasClass('current')){
		$('.emoji-edit-display ul.typecho-option-tabs.clearfix li').removeClass('current');
		$(this).parent().addClass('current');
		$('#emoji-edit>div:not(.emoji-edit-display)').css('display','none');
		switch($(this).text()){
			case '视图模式':
				$('#emoji-edit-package').css('display','block');
				emoji.package.index = -1;
				emoji.package.table();
				emoji.put();
				break;
			case 'Json模式':
				$('#emoji-edit-json').css('display','block');
				if(before == '视图模式') $('#emoji-edit-json textarea').val(JSON.stringify(emoji.data));
				break;
		}
	}
});
$('#emoji-edit-json textarea').blur(function(){
	try{
		emoji.data = JSON.parse($('#emoji-edit-json textarea').val());
	}catch(e){
		notice('错误：Json格式错误！<br>'+e.toString(),'error');
	}
});

//QPlayer
window.QPlayer = {};
(function () {
	var
		input = $('[name="QPlayer"]'),
		json = input.val(),
		q = QPlayer;
	
	if (json)
		q.json = json = JSON.parse(json);
		
	input.parent().parent().after('<ul class="typecho-option" id="QPlayer"><label class="typecho-label">QPlayer</label><p>网易云音乐歌单ID<input class="text" type="text" id="QPlayer-id"></p><span class="multiline"><input type="checkbox" id="QPlayer-isAuto"><label for="QPlayer-isAuto"> 自动播放</label></span><span class="multiline"><input type="checkbox" id="QPlayer-isRandom"><label for="QPlayer-isRandom"> 随机播放</label></span><span class="multiline"><input type="checkbox" id="QPlayer-isRotate"><label for="QPlayer-isRotate"> 封面旋转</label></span></ul>');

	var
		obj = $('#QPlayer'),
		a = obj.find('#QPlayer-id'),
		b = obj.find('#QPlayer-isAuto'),
		c = obj.find('#QPlayer-isRandom'),
		d = obj.find('#QPlayer-isRotate');
	
	if (json) {
		a.val(json.id);
		b.prop('checked', json.isAuto);
		c.prop('checked', json.isRandom);
		d.prop('checked', json.isRotate);
	}
	
	q.submit = function () {
		q.data = {
			id: a.val(),
			isAuto: b.prop('checked'),
			isRandom: c.prop('checked'),
			isRotate: d.prop('checked'),
		};
		input.val(JSON.stringify(q.data));
	}
	
})();
$('form').submit(function(){
	emoji.setData();
	if (QPlayer)
		QPlayer.submit();
});

/**
 * 检测更新
 */
$.get(themeUrl+'/assets/php/update.php', function (data) {
	if (data == 1)
		$('<div class="update-check message error"><p><stong><a href="https://github.com/moeshin/Typecho-Theme-Littlehands" target="_blank">有新版本可更新！</a><stong></p></div>')
			.insertAfter('.typecho-page-title').effect('highlight');
});