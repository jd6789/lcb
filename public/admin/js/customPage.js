(function ($, window) {
    $.fn.CustomPage = function (config) {
        // 默认配置
        var defaults = {
            pageSize: 5,
            count: 100,
            current: 1,
            prevDes: "",
            nextDes: "",
            updateSelf: true,
            callback: null
        };
        // 插件配置合并
        this.oConfig = $.extend(defaults, config);
        var self = this;

        // 初始化函数
        var init = function () {
            // 初始化数据
            updateConfig(self.oConfig);
            // 事件绑定
            bindEvent();
        };
        // 更新方法
        var updateConfig = function (config) {
            typeof config.count !== 'undefined' ? self.count = config.count : self.count = self.oConfig.count;
            typeof config.pageSize !== 'undefined' ? self.pageSize = config.pageSize : self.pageSize = self.oConfig.pageSize;
            typeof config.current !== 'undefined' ? self.current = config.current : self.current = self.oConfig.current;
            self.pageCount = Math.ceil(self.count / self.pageSize);
            format();
        };
        var format = function () {
            var current = self.current;
            var count = self.pageCount;
            var html = '<div class="page-container"><ul>';
            if (current != 1){
                html += '<li class="page-item page-prev">' + self.oConfig.prevDes + '</li>';
            }else{
                html += '<li class="page-item page-prev" style="pointer-events: none; ">' + self.oConfig.prevDes + '</li>';
            }

            var start = 1;
            var end = count;
            if (count > 5) {
                if (current <= 5) {
                    start = 1;
                    end = 5;
                } else if (current >= count - 2) {
                    start = count - 4;
                    end = count;
                } else {
                    start = current - 2;
                    end = current + 2;
                }
            }
            for (var i = start; i <= end; i++) {
                html += getItem(i);
            }
            if (current != count){
                html += '<li class="page-item page-next">' + self.oConfig.nextDes + '</li>';
            }else{
                html += '<li class="page-item page-next" style="pointer-events: none; ">' + self.oConfig.nextDes + '</li>';
            }

            html += '</ul></div>';
            self.html(html);
        };
        var getItem = function (i) {
            var item = '';
            var current = (i == self.current);
            item += '<li class="page-item" data-page="' + i + '">';
            if (current) {
               // item += '<div class="page-icon-current page-icon-content"></div>';
                item += '<span class="page-text-current">' + i + '</span></li>';
            } else {
                //item += '<div class="' + (i % 2 == 0 ? 'page-icon-type1' : 'page-icon-type2') + ' page-icon"></div>';
                item += '<span class="page-text">' + i + '</span></li>';
            }
            return item;
        };
        var bindEvent = function () {
            self.on('click', '.page-item', function () {
                var current;
                if ($(this).hasClass('page-prev')) {
                    current = Math.max(1, self.current - 1);
                } else if ($(this).hasClass('page-next')) {
                    current = Math.min(self.pageCount, self.current + 1);
                } else {
                    current = parseInt($(this).data('page'));
                }
                self.oConfig.callback && self.oConfig.callback(current);
                if (self.oConfig.updateSelf) {
                    self.current = current;
                    format();
                }
            })
        };
        // 启动
        init();
        //对外提供更新方法
        this.update = function (config) {
            updateConfig(config);
        };
        // 链式调用
        return self;
    };
})(jQuery, window);

