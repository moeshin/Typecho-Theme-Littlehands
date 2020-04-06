/**
 * QPlayer
 * 一款简洁小巧的HTML5底部悬浮音乐播放器
 * @package QPlayer
 * @author 小さな手は
 * @version 1.0.2
 * @link https://www.littlehands.site/
 * @link https://github.com/moeshin/QPlayer/
 */
window.QPlayer = {
	isAuto: false,
	onSetRotate: function () {}
};
(function (q) {
	var
		isRandom = q.isRandom,
		isRotate = q.isRotate;
		
	if (isRandom === undefined)
		isRandom = true;
	
	if (isRotate === undefined)
		isRotate = true;
	
	/**
	 * 变量监听
	 */
	Object.defineProperties(q,{
		isRandom: {
			get: function () {
				return isRandom;
			},
			set: function (bool) {
				isRandom = bool;
				q.history = [q.listIndex];
				q.histIndex = 0;
			}
		},
		isRotate: {
			get: function () {
				return isRotate;
			},
			set: function (bool) {
				isRotate = bool;
				q.onSetRotate(bool);
			}
		}
	});
})(QPlayer);
$(function () {

	/**
	 * 鼠标点击进度
	 * 
	 * @param int
	 */
	function mouseProgress(mouseX) {
		if (isProgressClick){
			var x1 = mouseX - $progress.offset().left;
				x2 = $progress.width();
			if (x1 <= x2)
				$already.width(x1);
			else
				$already.width(x2);
		}
	}
	
	/**
	 * 秒到分钟
	 * 
	 * @param int
	 * @return string eg:"00:00"
	 */
	function sToMin(s) {
		min = parseInt(s/60);
		s = parseInt(s%60);
		if (min < 10)
			min = '0'+min;
		if (s < 10)
			s = '0'+s;
		return min+":"+s;
	}
	
	/**
	 * 是否超过标签
	 * 
	 */
	function isExceedTitle() {
		var width = 0;
		$title.children().each(function () {
			width += $(this).width();
		});
		return width > $title.width();
	}
	
	/**
	 * 随机
	 * 
	 * @return int
	 */
	function random() {
		if (q.list instanceof Array) {
			if (q.isRandom) {
				return Math.round(Math.random() * q.list.length-1);
			}
			return ++q.listIndex === q.list.length ? 0 : q.listIndex;
		}
		return 0;
	}
	
	/**
	 * 选择歌词
	 * 
	 * @param int
	 */
	function lyricSelect(index){
		$lyricLi.removeClass('current');
		var $current = $lyricLi.eq(index).addClass('current');
		$lyricOl.stop(true).animate({
			scrollTop: $current.offset().top-$lyricOl.offset().top+$lyricOl.scrollTop()-($lyricOl.height()-$current.height())/2
		});
	}
	
	/**
	 * HTML转义
	 * 
	 * @param string
	 * @return string
	 */
	function html_encode(str) {
		return str.replace(/[<>&"]/g, function (c) {
			return {
				'<':'&lt;',
				'>':'&gt;',
				'&':'&amp;',
				'"':'&quot;'
			}[c];
		});
	}
	
	/**
	 * 检测列表
	 * 
	 * @return bool
	 */
	function testList() {
		if (q.list.length)
			return true;
		$title.text('没有歌曲');
		$list.html('<li>没有歌曲</li>');
		$lyricOl.addClass('no').html('<li>暂无歌词，请欣赏</li>');
		return false;
	}
	
	var
		$player = $('#QPlayer'),
		$progress = $player.find('.progress'),
		$already = $progress.find('.already'),
		$title = $player.find('.title'),
		$audio = $player.find('audio'),
		$cover = $player.find('.cover img'),
		$timer = $player.find('.timer'),
		$play = $player.find('.play'),
		$more = $player.find('.more'),
		$list = $player.find('.list'),
		$listBtn = $player.find('.list-btn'),
		$lyric = $player.find('.lyric'),
		$lyricOl = $lyric.find('ol'),
		$lyricBtn = $player.find('.lyric-btn'),
		$listLi = $(),
		$lyricLi = $(),
		isLoad = false,
		isProgressClick = false,
		q = QPlayer,
		audio = q.audio = $audio[0],
		lyric = q.lyric = {
			arr: [],
			obj: {},
			index: -1
		};
		const
			
			/**
			 * 网易云API
			 * 
			 * @link https://github.com/moeshin/API-NeteaseMusic/
			 */
			api1 = 'https://api.littlehands.site/NeteaseMusic/',
			
			/**
			 * 重定向API
			 * 
			 * @link https://github.com/moeshin/API-Redirect/
			 */
			api2 = 'https://api.littlehands.site/Redirect/',
			
			lyricRegex1 = /(?:^|\n)((?:\[\d\d:\d\d\.\d{2,3}\])+)(.*)/g,
			lyricRegex2 = /\[(\d\d):(\d\d\.\d{2,3})\]/g;
		
	q.list = [];
	if (!q.type) {
		q.type = 'list';
	}
	
	//获取播放歌单列表
	$.ajax({
		url: api1,
		data: {
			type: q.type,
			id: q.id
		},
		dataType: 'jsonp',
		success: function (json) {
			q.list = json;
			if (testList()) {
				//生成播放列表
				for (var i = 0; i < q.list.length; i++) {
					var data = q.list[i];
					data.name = html_encode(data.name.replace(/\u00A0/g,'\u0020'));
					$list.append('<li><strong>'+data.name+'</strong><span>'+data.artist.join('/')+'</span></li>');
				}
				
				$listLi = $list.find('li').click(function () {
					var obj = $(this);
					if (!obj.hasClass('error')) {
						// 清除当前历史节点之后的内容
						q.history = q.history.slice(0, ++q.histIndex);

						var index = obj.index();
						q.play(index);
						q.history.push(index);
					}
				});
				
				//触发监听事件
				q.isRotate = q.isRotate;
				q.isRandom = q.isRandom;
				
				q.listIndex = -1;
				q.histIndex = 0;
				q.load(random());
				q.history = [q.listIndex];
				$list[0].scrollTop = $list.find('.current')[0].offsetTop - $list[0].offsetTop + 1;
				if (q.isAuto)
					q.play();
			}
		},
		error: testList
	});
		
	/**
	 * 播放
	 * 
	 * @param int
	 */
	q.play = function (n) {
		if (q.load(n))
			return;
		$player.addClass('playing');
		if (isLoad) {
			var id = q.current.id;
			isLoad = false;
			audio.load();
			if (isExceedTitle())
					$title.marquee({
						duration: 15000,
						gap: 50,
						delayBeforeStart: 0,
						direction: 'left',
						duplicated: true
					});
			$.ajax({
				url: api2,
				data: {
					url: 'https://music.163.com/song/media/outer/url?id='+id,
					type: 1
				},
				dataType: 'jsonp',
				success: function (json) {
					if (id !== q.current.id)
						return;
					var url = json[json.length - 1].url;
					if (/^https?:\/\/music.163.com\/404\b/.test(url)) {
						q.error();
						return;
					}
					audio.src = url.replace(/^http:\/\//, 'https://');
					q.playId = id;
					if ($player.hasClass('playing'))
						audio.play();
				}
			});
		} else {
			if (q.playId !== q.current.id || audio.networkState === 3)
				return;
			audio.play();
		}
	};
	
	/**
	 * 加载
	 *
	 * @param n
	 * @return boolean 是否跳过
	 */
	q.load = function (n) {
		if (typeof(n) === "number") {
			if (n < 0) {
				return true;
			}
			if ($listLi.eq(n).hasClass('error')) {
				if (q.history[q.histIndex] === n) {
					q.history.splice(q.histIndex--, 1);
				}
				q.next();
				return true;
			}
		} else {
			// 首次初始化，不预加载音频，节省移动设备流量
			return false;
		}
		q.listIndex = n;
		$listLi.removeClass('current').eq(n).addClass('current');
		var data = q.current = QPlayer.list[n];
		$title.html('<strong>'+data.name+'</strong><span> - </span><span class="artist">'+data.artist.join('/')+'</span>');
		$cover.attr('src', data.pic.replace(/^http:\/\//i,'https://'));
		$already.width('0%');
		$timer.text('00:00');
		$lyricOl.addClass('no').html('<li>暂无歌词，请欣赏</li>');
		lyric.arr = [];
		lyric.obj = {};
		lyric.index = -1;
		$lyricLi = $();
		$.ajax({
			url: api1,
			data: {
				type: 'lyric',
				id: data.id
			},
			dataType: 'jsonp',
			success: function (json) {
				json.lyric.replace(lyricRegex1, function (match1, t, str) {
					var times = [];
					t.replace(lyricRegex2, function (match2, min, s) {
						times.push(min*60+s*1);
					});
					for (var i = 0; i < times.length; i++) {
						var time = times[i];
						lyric.arr.push(time);
						lyric.obj[time] = str?str:'';
					}
				});
				json.tlyric.replace(lyricRegex1, function (match1, t, str) {
					var times = [];
					t.replace(lyricRegex2, function (match2, min, s) {
						times.push(min*60+s*1);
					});
					for (var i = 0; i < times.length; i++) {
						var time = times[i];
						if (lyric.obj[time] !== undefined)
							lyric.obj[time] += '<br>'+str;
						else {
							lyric.arr.push(time);
							lyric.obj[time] = str?str:'';
						}
					}
					
				});

				lyric.arr = lyric.arr.sort(function (a, b) {
					return a-b;
				});
				if (lyric.arr.length > 0)
					$lyricOl.removeClass('no').html('');
				for (var i = 0; i < lyric.arr.length; i++) {
					$lyricOl.append('<li>'+lyric.obj[lyric.arr[i]]+'</li>');
				}
				$lyricLi = $lyricOl.find('li');
			}
		});
		isLoad = true;
	};
	
	/**
	 * 暂停
	 * 
	 */
	q.pause = function () {
		if (audio.networkState === 3)
			return;
		$player.removeClass('playing');
		audio.pause();
	};
	
	/**
	 * 下一首
	 * 
	 */
	q.next = function () {
		if (++q.histIndex < q.history.length) {
			q.play(q.history[q.histIndex]);
		} else {
			var index = random();
			q.history.push(index);
			q.play(index);
		}
	};
	
	/**
	 * 上一首
	 * 
	 */
	q.last = function () {
		if (q.histIndex === 0) {
			if (q.isRandom)
				q.play(random());
			else if (q.listIndex)
				q.play(q.listIndex-1);
			else
				q.play(q.list.length-1);
			q.history.splice(0,0,q.listIndex);
			q.histIndex = 0;
		} else
			q.play(q.history[--q.histIndex]);
	};
	
	/**
	 * 播放错误
	 * 
	 */
	q.error = function () {
		$listLi.eq(q.listIndex).addClass('error');
		q.history.splice(q.histIndex--, 1);
		q.next();
	};
	
	/**
	 * 设置rotate
	 * 
	 * @param bool
	 */
	q.onSetRotate = function (bool) {
		if (bool) {
			$cover.attr('title', '点击不旋转封面');
			$cover.addClass('rotate');
		} else {
			$cover.attr('title', '点击旋转封面');
			$cover.removeClass('rotate');
		}
	};
	
	/**
	 * .pop-btn点击
	 */
	$player.find('.pop-btn').click(function () {
		$player.toggleClass('pop');
	});
	
	/**
	 * 进度条按下
	 */
	$progress.mousedown(function (e){
		if (!isProgressClick) {
			isProgressClick = true;
			$player.addClass('unselectable');
			mouseProgress(e.pageX);
		}
	}).on('touchstart', function (e) {
		isProgressClick = true;
		$player.addClass('unselectable');
		mouseProgress(e.originalEvent.changedTouches[0].pageX);
	});
	
	/**
	 * 文档操作
	 */
	$("body")
		.on('mouseup touchend', function () {
			if (isProgressClick) {
				isProgressClick = false;
				$player.removeClass('unselectable');
				if (isNaN(audio.duration))
					$already.width('0');
				else {
					var time = audio.duration*$already.width()/$progress.width();
					audio.currentTime = time;
					for (var i = 0; i < lyric.arr.length; i++) 
						if (lyric.arr[i] >= time) {
							lyricSelect(lyric.index = i-1);
							break;
						}
				}
				
			}
		}).mousemove(function (e) {
			mouseProgress(e.pageX);
		}).on('touchmove',function (e) {
			if (isProgressClick)
				e.preventDefault();
			mouseProgress(e.originalEvent.changedTouches[0].pageX);
		});
	
	/**
	 * .play点击
	 */
	$play.click(function () {
		if (q.list.length)
			if ($player.hasClass('playing')) 
				q.pause();
			else
				q.play();
	});
	
	/**
	 * .next点击
	 */
	$player.find('.next').click(q.next);
	
	/**
	 * .last点击
	 */
	$player.find('.last').click(q.last);
	
	
	$audio
	
		/**
		 * 播放结束
		 */
		.on('ended', q.next)
		
		/**
		 * 播放中
		 */
		.on('playing', function () {
			$player.addClass('playing');
			$title.marquee('resume');
		})
	
		/**
		 * 播放暂停
		 */
		.on('pause', function () {
			$player.removeClass('playing');
			$title.marquee('pause');
		})
		.on('timeupdate', function () {
			$timer.text(sToMin(audio.currentTime));
			if (!isProgressClick)
				$already.width(100*audio.currentTime/audio.duration+"%");
			
			//播放歌词
			if (lyric.index+1 !== lyric.arr.length)
				if (lyric.arr[lyric.index+1] <= audio.currentTime)
					lyricSelect(++lyric.index);
		})
		.on('error', q.error);
	
	$listBtn.click(function () {
		$more.toggleClass('list-show');
	});
	
	$lyricBtn.click(function () {
		$more.toggleClass('lyric-show');
	});
	
	$cover.click(function () {
		q.isRotate = !q.isRotate;
	});
	
});
