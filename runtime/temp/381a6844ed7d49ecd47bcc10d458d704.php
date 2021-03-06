<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"/www/wwwroot/vip.guochamall.com/public/../application/tmvip/view/store/storeIndex.html";i:1527133751;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>门店管理</title>
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
    <style>
        /*分页*/
        .pagination {
            text-align: right;
            margin-top: 15px;
        }

        .pagination li {
            display: inline-block;
            margin-right: -1px;
            padding: 5px;
            border: 1px solid #e2e2e2;
            min-width: 20px;
            text-align: center;
        }

        .pagination li.active {
            background: #009688;
            color: #fff;
            border: 1px solid #009688;
        }

        .pagination li a {
            display: block;
            text-align: center;
        }
    </style>
    <style>
        .cont_search input.search {
            height: 28px;
            line-height: 28px;
            width: 100px;
            font-weight: 200;
            font-size: 14px;
        }

        .cont_search table {
            width: auto;
        }

        .bench {
            top: 34px;
        }

        .box {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            display: none
        }

        .handfh {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            display: none
        }

        .box-con {
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .5);
            position: absolute;
        }

        .box-text {
            width: 400px;
            height: 250px;
            position: absolute;
            background: #fff;
            z-index: 100;
            top: 50%;
            left: 50%;
            margin-left: -150px;
            margin-top: -150px;
        }

        .box-title {
            height: 40px;
            line-height: 40px;
            border-bottom: solid 1px #ccc;
            background: #f1f1f1;
        }

        .sd {
            margin-left: 15px;
            color: #666;
            font-size: 16px
        }

        .box1 {
            margin: 15px;
            font-size: 16px;
        }

        .box1 p {
            margin: 25px 0
        }

        .close {
            float: right;
            font-size: 18px;
            margin-right: 15px;
            cursor: pointer;

        }

        .closes {
            float: right;
            font-size: 18px;
            margin-right: 15px;
            cursor: pointer;

        }

        input[type=text] {
            width: 180px;
            height: 28px;
            margin-left: 10px
        }

        .qd-btn {
            border: none;
            background: cornflowerblue;
            width: 80px;
            height: 40px;
            border-radius: 20px;
            font-size: 15px;
            margin-top: 15px;
            color: #fff;
        }
        .blank{
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.3);
            z-index: 998;
            display: none;
        }
        .zhezhao{
            display: none;
            position: absolute;
            width: 50%;
            left: 50%;
            top: 35%;
            transform: translate(-50%,-50%);
            background-color: #fff;
            padding:30px 20px;
            z-index: 999;
        }
        .blank.in,.zhezhao.in{
            display: block;
        }
    </style>
</head>
<body>
<!--<div class="cont_search clearfix">-->
<!--<form action=""  method="post">-->
<!--&lt;!&ndash; 关键字 &ndash;&gt;-->
<!--用户名<input type="text" name="username" size="15" />-->
<!--<input type="submit" value="查询" class="btngreen search ">-->
<!--</form>-->
<!--</div>-->
<div class="bench white-scroll">
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>门店编号</td>
                <td>门店名称</td>
                <td>门店联系方式</td>
                <td>操作</td>
            </tr>

            <!---->
            <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
            <tr>
                <td class="intid"><?php echo $v['id']; ?></td>
                <td class="username"><?php echo $v['stores_name']; ?></td>
                <td><?php echo $v['stores_tel']; ?></td>
                <td>
                    <button value="<?php echo $v['id']; ?>" class="handfenhong">分红</button>&nbsp;
                    <button value="<?php echo $v['id']; ?>" class="all">门店下股东</button>

                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <!---->
            </tbody>
        </table>
        <p></p>
    </div>
    <!--<div class="page clearfix">-->
        <!--<div class="text">共<b>1</b>页<b>4</b>条记录</div>-->
        <!--<div class="linklist">-->
            <!--<a class="prev" href="javascript:void(0)">&nbsp;</a>-->
            <!--<a href="javascript:void(0)" class="current">1</a>-->
            <!--<a class="next" href="javascript:void(0)">&nbsp;</a>-->
        <!--</div>-->
    <!--</div>-->
</div>

<!--手动给股东分红-->
<div class="handfh">
    <div class="box-con">
        <div class="box-text">
            <div class="box-title"><span class="sd">分红</span><span class="closes">X</span></div>
            <div class="box1">
                <p><span class="sdusers" style="color:#f00"></span>确定给入股股东分红吗？</p>
                <p>选择总分红金额:<input class="moneys" type="text" value=""></p>
                <div style="text-align:center"><input class="qd-btn" type="button" value="确定"></div>
            </div>
        </div>
    </div>

</div>

<!--展示用户的历史记录-->
<div class="blank"></div>
<div class="zhezhao">
    <div class="globaltable r5px">
        <table border="0" cellpadding="0" cellspacing="0" id="table">
            <thead>
            <tr>
                <td>股东名字</td>
                <td>历史分红总数</td>
                <td>拥有的比例</td>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table><p></p>
    </div>
</div>
</body>
</html>
<script src="/admin/js/jquery.min.js"></script>
<script>
    //        手动分红
    $(".handfenhong").click(function () {
        $(".sdusers").text($(this).parent().parent().find(".username").text());
        int_id = $(this).parent().parent().find(".intid").text();
        $(".handfh").fadeIn(500)
    });
    $(".closes").click(function () {
        $(".handfh").fadeOut(500)
    })
    $(".qd-btn").click(function () {
        var moneys = $(".moneys").val();
        $.ajax({

            type: "post",
            url: "<?php echo url('tmvip/store/handFenhong'); ?>",
            data: {
                int_id: int_id,
                moneys: moneys,
            },
            success: function (data) {
                if (data.status == 0) {
                    alert('服务器错误，操作失败');
                } else {
                    alert("操作成功");
                    location.reload();
                }
            }
        })
    })

    $(".all").click(function () {
        var id=$(this).val()
        $.ajax({
            url:"<?php echo url('tmvip/store/storedown'); ?>",
            data:{id:id},
            type:"post",
            success:function (data) {
                var table=''
                if(data==0){
                    table+="<tr>\n" +
                        "                <td colspan='3'>暂无数据</td>\n" +
                        "            </tr>"
                }else {
                    for(var i=0;i<data.length;i++){
                        table+="<tr>\n" +
                            "                <td class=\"username\">"+data[i].name.user_name+"</td>\n" +
                            "                <td>"+data[i].all+"</td>\n" +
                            "                <td>"+data[i].gufen+"%</td>\n" +
                            "            </tr>"
                    }

                }
                $("#table tbody").html(table)
                $(".zhezhao").addClass("in")
                $(".blank").addClass("in")
            }
        })

    })
    $(".blank").click(function () {
        $(".zhezhao").removeClass("in")
        $(".blank").removeClass("in")
    })
</script>