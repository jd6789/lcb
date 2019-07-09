// 当鼠标移动到有下拉菜单的按钮或链接上时显示下来菜单，鼠标移除时隐藏
$('.dropdown').parent().on({
    mouseover : function(){$(this).children('.dropdown').addClass('in');} ,
    mouseout : function(){$(this).children('.dropdown').removeClass('in');}
});
$('.dropdown_white').parent().on({
    mouseover : function(){$(this).children('.dropdown_white').addClass('in');} ,
    mouseout : function(){$(this).children('.dropdown_white').removeClass('in');}
});

// 当左侧菜单被点击
$('.leftmenu ul li').click(function () {
    $('.leftmenu').find('a').removeClass('current');
    $(this).children('a').addClass('current');
});

// 当营业界面的桌台被点击
$(document).on('click','.bench_entry ul li',function () {
    open_right_view();
});

// 当删除按钮被点击
$(document).on('click','.delete',function () {
    open_warning_view();
});

// 当销售按钮被点击
$('#sale_button').click(function () {
    open_new_view();
});


// 打开侧边栏
function open_right_view() {
    $(window.parent.document).find('.drawerdetail').addClass('in');
    $(window.parent.document).find('.bgnone').addClass('in');
    $(window.parent.document).find('.drawerdetail').load('table_right_view.html');
};

// 打开普通弹窗
function open_view() {
    $(window.parent.document).find('#viewroom').addClass('in');
    $(window.parent.document).find('.bgblack').addClass('in');
    $(window.parent.document).find('#viewroom').load('table_kaidan.html');
};

// 打开新弹窗
function open_new_view() {
    $(window.parent.document).find('.newview').addClass('in');
    $(window.parent.document).find('.bgblack').addClass('in');
};

// 打开小弹窗
function open_small_view() {
    $(window.parent.document).find('.newview').addClass('in');
    $(window.parent.document).find('.bgblack').addClass('in');
};

// 打开提示窗口
function open_warning_view() {
    $(window.parent.document).find('.viewwarning').addClass('in');
    $(window.parent.document).find('.bgblack').addClass('in');
};

// 关闭侧边栏
function close_right_view() {
    $(window.parent.document).find('.bgblack').removeClass('in');
    $(window.parent.document).find('.drawerdetail').removeClass('in');
};

// 当关闭按钮被点击
$(document).on('click','.close', function () {
    $(this).parents('.in').removeClass('in');
    $(window.parent.document).find('.bgblack').removeClass('in');
});

// 当空白遮罩层被点击
$('.bgnone').click(function () {
    $(window.parent.document).find('.drawerdetail').removeClass('in');
    $(this).removeClass('in');
});