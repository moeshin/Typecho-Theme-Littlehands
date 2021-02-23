//创建对象
window.main = {};
main.comments = {};
main.mobileMenu = {};
main.search = {};
main.lazyload = {};
main.scroll = {};
main.touch = {};
main.TOC = {};
main.comments.textarea = {};
main.comments.emoji = {};
main.mobileMenu.btn = {};
main.comments.emoji.btn = {};

/**
 * 评论回复
 *
 * @return bool
 */
main.Typecho_Comment_Js = function(){
	var respondId = $('.respond').attr('id');
	window.TypechoComment = {
		dom : function (id) {
			return document.getElementById(id);
		},
		create : function (tag, attr) {
			var el = document.createElement(tag);
			for (var key in attr) {
				el.setAttribute(key, attr[key]);
			}
			return el;
		},
		reply : function (cid, coid) {
			var startOffset = main.lastEditRange?main.lastEditRange.startOffset:null;
			var comment = this.dom(cid), parent = comment.parentNode,
				response = this.dom(respondId), input = this.dom('comment-parent'),
				form = 'form' == response.tagName ? response : response.getElementsByTagName('form')[0];
			if (null == input) {
				input = this.create('input', {
					'type' : 'hidden',
					'name' : 'parent',
					'id'   : 'comment-parent'
				});
				form.appendChild(input);
			}
			input.setAttribute('value', coid);
			if (null == this.dom('comment-form-place-holder')) {
				var holder = this.create('div', {
					'id' : 'comment-form-place-holder'
				});
				response.parentNode.insertBefore(holder, response);
			}
			comment.appendChild(response);
			this.dom('cancel-comment-reply-link').style.display = '';
			main.comments.textarea.setRange(startOffset);
			main.anchorPoint($('.respond'));
			return false;
		},
		cancelReply : function () {
			var startOffset = main.lastEditRange?main.lastEditRange.startOffset:null;
			var li = $('.respond').parent().parent();
			var response = this.dom(respondId),
			holder = this.dom('comment-form-place-holder'), input = this.dom('comment-parent');
			if (null != input) {
				input.parentNode.removeChild(input);
			}
			if (null == holder) {
				return true;
			}
			this.dom('cancel-comment-reply-link').style.display = 'none';
			holder.parentNode.insertBefore(response, holder);
			main.comments.textarea.setRange(startOffset);
			main.anchorPoint(li);
			return false;
		}
	};
};

/**
 * 绑定可视状态
 *
 * @return void
 */
main.bindVisibilityChange = function(){
	main.titlesave = document.title;
	$(document).on("visibilitychange",function(){
		if(document.visibilityState == "hidden"){
			main.titlesave = document.title;
			document.title = "我在这呢 ≥ω≤";
		}else{
			document.title = main.titlesave;
		}
	});
};

/**
 * 获取一言
 *
 * @return void
 */
main.hitokoto = function(){
	if($('#hitokoto').length == 1)$.getJSON('https://v1.hitokoto.cn',{encode: 'json'},function(data){
		switch(data.type){
			case 'a': data.type = 'Anime - 动画'; break;
			case 'b': data.type = 'Comic – 漫画'; break;
			case 'c': data.type = 'Game – 游戏'; break;
			case 'd': data.type = 'Novel – 小说'; break;
			case 'e': data.type = 'Myself – 原创'; break;
			case 'f': data.type = 'Internet – 来自网络'; break;
			case 'g': data.type = 'Other – 其他'; break;
			default: data.type = '无';
		}
		var time = new Date(data.created_at * 1000);
		$('#hitokoto').text(data.hitokoto);
		$('#hitokoto').attr('href','https://hitokoto.cn?id=' + data.id);
		$('#hitokoto').attr('title','分类：' + data.type + '\n出自：' + data.from + '\n投稿：creator @ ' + time.toLocaleString());
	});
};

/**
 * 锚点
 * 
 * @param obj {jq} 目标jq对象
 * @param num {rise} 距离顶部高度
 * @param fn {callback} 回调函数
 * @return void
 */
main.anchorPoint = function(jq,rise,callback){
	rise = rise||0;
	if(jq.length){
		style = {scrollTop: jq.offset().top-rise};
		$('html,body').animate(style);
		if(typeof(callback) == 'function') setTimeout(callback,400);
	}
};


/**
 * 评论框插入内容
 * 
 * @param string
 * @return void
 */
main.comments.textarea.insert = function(html){
	var edit = document.getElementById('textarea');
	edit.focus();
	var el = document.createElement('div');
	el.innerHTML = html;
	var selection = getSelection();
	if (main.lastEditRange) {
		selection.removeAllRanges();
		selection.addRange(main.lastEditRange);
	}
	var range = selection.getRangeAt(0);
	range.deleteContents();
	var frag = document.createDocumentFragment(), node, lastNode;
	while ( (node = el.firstChild) ) {
		lastNode = frag.appendChild(node);
	}
	range.insertNode(frag);
	if (lastNode) {
		range = range.cloneRange();
		range.setStartAfter(lastNode);
		range.collapse(true);
		selection.removeAllRanges();
		selection.addRange(range);
	}
	main.lastEditRange = selection.getRangeAt(0);
};

/**
 * 保存评论框光标
 *
 * @return void
 */
main.comments.textarea.saveRange = function(){
	try{
		main.lastEditRange = getSelection().getRangeAt(0);
	}catch(e){}
};

/**
 * 设置评论框光标
 *
 * @param int
 * @param int
 * @return void
 */
main.comments.textarea.setRange = function(startOffset,endOffset){
	if(!main.lastEditRange) main.lastEditRange = document.createRange();
	var textarea = $('#textarea')[0];
	main.lastEditRange.selectNodeContents(textarea);
	if(!isNaN(startOffset)) main.lastEditRange.setStart(textarea,startOffset);
	if(!isNaN(endOffset)) main.lastEditRange.setEnd(textarea,endOffset);
	var selection = getSelection();
	selection.removeAllRanges();
	selection.addRange(main.lastEditRange);
};

/**
 * 评论框鼠标事件处理
 *
 * @return void
 */
main.comments.textarea.mouse = function(){
	var
		range = getSelection().rangeCount?getSelection().getRangeAt(0):0,
		textarea = document.getElementById('textarea');
	if (main.lastEditRange&&(range.commonAncestorContainer == textarea||range.commonAncestorContainer.parentNode == textarea)) main.comments.textarea.saveRange();
};

/**
 * 表情按钮被点击
 *
 * @return void
 */
main.comments.emoji.btn.click = function(){
	var emoji = $(this).parent();
	$('.emoji-package').not(emoji).removeClass('emoji-popup');
	emoji.toggleClass('emoji-popup');
};

/**
 * 表情按钮鼠标放开
 *
 * @return void
 */
main.comments.emoji.btn.mousedown = function(e){
	$('#textarea').blur();
	e.preventDefault();
	event.returnValue=false; 
};

/**
 * 表情被点击
 *
 * @return void
 */
main.comments.emoji.click = function(){
	main.comments.textarea.insert($('img',this)[0].outerHTML);
	$(this).parent().parent().parent().removeClass('emoji-popup');
};

/**
 * 评论即将提交
 *
 * @return bool
 */
main.comments.submit = function(){
	var button = $('.submit',this);
    button.attr('disabled','disabled');
	var equired = $('#comment-form [data-equired]');
	for(var i = 0;i < equired.length;i++){
		var obj = equired[i];
		var bool = false,key;
		if(obj.id == 'textarea'){
			if(obj.innerHTML) bool = true;
		}else if(obj.value) bool = true;
		if(!bool){
			switch(obj.id){
				case 'author': key = '昵称'; break;
				case 'mail': key = '邮箱'; break;
				case 'url': key = '网站'; break;
				case 'textarea': key = '内容'; break;
			}
			swal({
				title: "错误：",
				text: "请填写"+key+'！',
				type: "error",
				confirmButtonText: "确定"
			},function(){
				$(obj).focus();
			});
			button.removeAttr('disabled');
			return false;
		}
	}
	var reg = /[\u4e00-\u9fa5]/g;
	if(reg.test($('#textarea').html())){
		if(window.NProgress) NProgress.start();
		var form = $(this),data = form.serializeArray(),parentId = $('#comment-parent');
		main.comments.parent = parentId.length?parentId.val():null;
		data.push({'name': 'text','value': $('#textarea').html()});
		$.ajax({
			url: form.attr('action'),
			type: 'POST',
			data: data,
			success: main.comments.success
		});
	}else{
		swal({
			title: "错误：",
			text: "评论必须含有中文!",
			type: "error",
			confirmButtonText: "确定"
		},function(){
			button.removeAttr('disabled');
		});
	}
   	return false;
};

/**
 * 评论提交成功
 *
 * @param void
 */
main.comments.success = function(data){
	data = $('<data>'+data+'</data>');
	if($('#comments',data).length){
		$('#body').html($('#body',data).html());
		var jq;
		if(main.comments.parent) jq = $('#li-comment-'+main.comments.parent);
		else jq = $('#comments');
		if(!jq.length) jq = $('#comments');
		main.anchorPoint(jq)
		main.load();
	}else{
		main.dom = data;
		$('style,title',data).remove();
		var container = $('.container',data),text,iframe;
		if(container.length){
			text = container.text().replace(/\s/mg,'');
		}else{
			text = '<iframe frameborder="0" style="width: 100%; height: 100%; border: solid 1px;"></iframe>';
			iframe = true;
		}
		swal({
			title: "错误：",
			text: text,
			type: "error",
			html: true,
			confirmButtonText: "确定"
		});
		if(iframe) $(".sweet-alert iframe")[0].contentWindow.document.write($('code',data).html());
		$('#comment-form .submit').removeAttr('disabled');
	}
	if(window.NProgress) NProgress.done();
};

main.anchorCommentLinkDom = function(dom) {
    if(dom.hash){
        var li = $(dom.hash).parent();
        if(li.length){
            main.anchorPoint(li);
            return false;
        }
    }
}

$(document)
    .on('click', '.at-user', function () {
        return main.anchorCommentLinkDom(this);
    });

/**
 * 移动菜单按钮被点击
 * 
 * @return void
 */
main.mobileMenu.btn = function(){
	$('#mobileMenuButton,#secondary,#parclose').toggleClass('mobileMenuOn');
};

/**
 * 菜单内容被点击
 * 
 * @param obj
 * @return bool
 */
main.mobileMenu.click = function(e){
	var obj = this;
	var secondary = $('#secondary');
	if(secondary.hasClass('mobileMenuOn') && document.body.offsetWidth <= 991){
		e.preventDefault();
		event.returnValue=false;
		main.mobileMenu.btn();
		setTimeout(function(){
			obj.click();
		}, 500);
	}
    return main.anchorCommentLinkDom(this);
};

/**
 * 菜单被滚动
 * 
 * @param obj
 * @return void
 */
main.mobileMenu.scroll = function(e){
	e = e || window.event;
	var delta = e.wheelDelta || e.detail || e.originalEvent.wheelDelta || e.originalEvent.detail;
	if(e.type == 'touchmove') delta = e.originalEvent.changedTouches[0].pageY-main.touch.start.originalEvent.changedTouches[0].pageY;
	var jq = $(this);
	if(((jq.scrollTop() == 0 && delta >0) || (jq.scrollTop() == jq[0].scrollHeight-jq.outerHeight() && delta < 0)) && jq.hasClass('mobileMenuOn') && document.body.offsetWidth <= 991) main.scroll.disable(e);
}

/**
 * 搜索
 * 
 * @return bool
 */
main.search.submit = function(){
	var content = $('[type=text]',this).val().replace(/[^\u4e00-\u9fa5\w]|_/g,'');
	if(content){
		main.pjax.load(indexUrl + 'search/' + content);
	}else{
		main.pjax.load();
	}
	return false;
};

/**
 * 显示主要部分
 * 
 * @return void
 */
main.visible = function(){
	$('#main').css('visibility','visible');
	$('#main').css('opacity','1');
}

/**
 * 懒加载开始
 * 
 * @return void
 */
main.lazyload.star = function(){
	$(this).addClass('lazyload');
}

/**
 * 懒加载完成
 * 
 * @return void
 */
main.lazyload.complete = function(){
	$(this).removeClass('lazyload').removeAttr('data-original');
}

/**
 * 禁止页面滚动
 * 
 * @param obj
 * @return void
 */
main.scroll.disable = function(e){
	if (e.preventDefault)e.preventDefault();
	e.returnValue = false;
}

/**
 * 百度统计
 * 
 * @return void
 */
_hmt = [];
main.baidu = function(){
	if(baiduID){
		delete window[baiduID];
		$('#hm').remove();
		$('<script id="hm" src="//hm.baidu.com/hm.js?'+baiduID+'"></script>').appendTo('head');
	}
}

/**
 * 打赏
 * 
 * @return void
 */
main.reward = function(){
	var jq = $(this).next();
	if(jq.is(':hidden')) jq.fadeIn();
	else jq.fadeOut();
}

/**
 * 目录项被点击
 *
 * @return void
 */
main.TOC.a = function(){
	main.anchorPoint($(this.hash));
	return false;
}

/**
 * 控制台
 *
 * @return void
 */
main.console = function(){
	if(typeof eruda == 'undefined')
		$.getScript('https://cdnjs.loli.net/ajax/libs/eruda/1.3.2/eruda.min.js',function(){
			main.console();
		});
	else 
		if($('#eruda').length == 0)
				eruda.init();
}

/**
 * 页面加载完毕或PJAX执行完成后执行
 *
 * @return void
 */
main.load = function(){
	main.visible();
	main.Typecho_Comment_Js();
	main.hitokoto();
	main.baidu();
	Prism.highlightAll();
	main.exScrollTop = $(document).scrollTop();
	main.lastEditRange = 0;
	$('.post-content img').lazyload({threshold: 180,effect: "fadeIn",appear: main.lazyload.star,load: main.lazyload.complete});
	$('.post-content').lightGallery({selector: '.lg:has(>img)',download: false,scale: 0.25});
	$('.TOC li>a').click(main.TOC.a);
    $('#comment-form').submit(main.comments.submit);
	$('#mobileMenuButton,#parclose').click(main.mobileMenu.btn);
	$('#secondary a,#secondary button').click(main.mobileMenu.click);
    $('#secondary').on('DOMMouseScroll wheel mousewheel touchmove',main.mobileMenu.scroll);
    $('#parclose').on('DOMMouseScroll wheel mousewheel touchmove',main.scroll.disable);
   	$('#search,#404').submit(main.search.submit);
	$('.emoji-btn').click(main.comments.emoji.btn.click);
	$('.emoji-btn').mousedown(main.comments.emoji.btn.mousedown);
	$('.emoji-body li').click(main.comments.emoji.click);
	$('#textarea').mouseup(main.comments.textarea.mouse);
	$('#textarea').mouseout(main.comments.textarea.mouse);
	$('#textarea').keyup(main.comments.textarea.saveRange);
	$('#loadtime').text(main.loadtime);
	$('.reward span').click(main.reward);
};

/**
 * 页面加载完毕后执行
 *
 * @return void
 */
$(function(){
	main.bindVisibilityChange();
	$(document).on('touchstart',function(e){main.touch.start = e;});
	main.load();
});