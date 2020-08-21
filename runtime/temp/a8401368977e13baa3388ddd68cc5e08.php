<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:65:"D:\project2\lcb\public/../application/tmvip\view\login\login.html";i:1528715313;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="/admin/images/mjicon.ico" type="image/x-icon">
    <link href="/admin/css/styles.bundle.css" rel="stylesheet">
    <style>
        body[_ngcontent-c0] {
            color: #333;
            padding-bottom: 60px
        }

        .Login_Bg[_ngcontent-c0] {
            background: url(/admin/images/loginbg.24bf10b5511024311ccc.jpg) no-repeat 50%;
            background-size: cover;
            width: 100%;
            height: 100%;
            position: absolute
        }

        .Login_Bg[_ngcontent-c0]   .viewroom.in[_ngcontent-c0] {
            top: 20%
        }

        .Login[_ngcontent-c0] {
            width: 460px;
            height: 480px;
            margin: -240px 0 0 -230px;
            background: #fff;
            position: absolute;
            top: 50%;
            left: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, .25);
            overflow: hidden;
            z-index: 2;
            transition: none
        }

        .Login[_ngcontent-c0]   .Logo[_ngcontent-c0] {
            height: 120px;
            background: #1a1918;
            text-align: center;
            padding-top: 23px
        }

        .Login[_ngcontent-c0]   .Login_Form[_ngcontent-c0] {
            margin: 0 auto;
            width: 300px;
            padding-top: 40px;
            position: relative
        }

        .prompt[_ngcontent-c0] {
            position: absolute;
            top: 5px;
            left: 0;
            border: 1px solid #e64c2e;
            background: #ffeae5;
            padding: 5px;
            font-size: 12px;
            color: #e64c2e;
            width: 100%;
            text-align: center;
            filter: alpha(opacity=0);
            -moz-opacity: 0;
            -khtml-opacity: 0;
            opacity: 0;
            transition: all .5s linear;
            -moz-transition: all .2s linear;
            -webkit-transition: all .2s linear;
            -o-transition: all .5s linear
        }

        .prompt.in[_ngcontent-c0] {
            filter: alpha(opacity=1);
            -moz-opacity: 1;
            -khtml-opacity: 1;
            opacity: 1
        }

        .Login[_ngcontent-c0]   .Login_Form[_ngcontent-c0]   .prompt[_ngcontent-c0] {
            top: 15px
        }

        .Login[_ngcontent-c0]   .List_Input[_ngcontent-c0] {
            margin: 30px 0 0 0;
            height: 30px
        }

        .Login[_ngcontent-c0] input[_ngcontent-c0] {
            border: none;
            border-bottom: 1px solid #ccc;
            padding: 0 5px 8px 25px;
            width: 300px;
            font-size: 14px;
            height: 30px;
            color: #333;
            background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAIxCAYAAABEnDjbAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NjU1MTY2NEY0RDk3MTFFNkI4NThFNURCMjlDN0MxM0MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NjU1MTY2NEU0RDk3MTFFNkI4NThFNURCMjlDN0MxM0MiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjdCMEQ1M0EwM0FBRDExRTY5OUVDOTY0OUVGQzI4NzhDIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjdCMEQ1M0ExM0FBRDExRTY5OUVDOTY0OUVGQzI4NzhDIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+qBfArQAABzZJREFUeNrsXFuIHEUU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) no-repeat 0 0;
            font-family: Microsoft YaHei;
            transition: all .5s linear;
            -moz-transition: all .2s linear;
            -webkit-transition: all .2s linear;
            -o-transition: all .5s linear;
            border-radius: 0;
            -webkit-border-radius: 0;
            -moz-border-radius: 0
        }

        .Login[_ngcontent-c0] input[_ngcontent-c0]:focus {
            border-color: #e6c18d;
            color: #333
        }

        .Login[_ngcontent-c0]   .Login_Form[_ngcontent-c0] input[_ngcontent-c0]:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 100px #fff inset
        }

        .Login[_ngcontent-c0]   .List_Input[_ngcontent-c0]:first-child input[_ngcontent-c0] {
            background-position: 0 -300px
        }

        .Login[_ngcontent-c0]   .List_Input[_ngcontent-c0]:first-child input[_ngcontent-c0]:focus {
            background-position: 0 -330px
        }

        .Login[_ngcontent-c0]   .List_Input[_ngcontent-c0]:nth-child(2) input[_ngcontent-c0] {
            background-position: 0 -118px
        }

        .Login[_ngcontent-c0]   .List_Input[_ngcontent-c0]:nth-child(2) input[_ngcontent-c0]:focus {
            background-position: 0 -148px
        }

        .Login[_ngcontent-c0]   .List_Input[_ngcontent-c0]:nth-child(3) input[_ngcontent-c0] {
            background-position: 0 -58px
        }

        .Login[_ngcontent-c0]   .List_Input[_ngcontent-c0]:nth-child(3) input[_ngcontent-c0]:focus {
            background-position: 0 -88px
        }

        .Login[_ngcontent-c0]   .List_Input[_ngcontent-c0]:nth-child(3) {
            position: relative
        }

        .Login[_ngcontent-c0]   .List_Input[_ngcontent-c0]:nth-child(3) input[_ngcontent-c0] {
            width: 120px
        }

        .Login[_ngcontent-c0]   .List_Input[_ngcontent-c0]:nth-child(3) a[_ngcontent-c0] {
            position: absolute;
            top: -1px;
            left: 125px
        }

        .Login[_ngcontent-c0]   .List_Input[_ngcontent-c0]:nth-child(3) img[_ngcontent-c0] {
            width: 80px;
            height: 30px
        }

        .Login[_ngcontent-c0] button[_ngcontent-c0] {
            width: 300px;
            height: 42px;
            background: #e1b26f;
            text-align: center;
            cursor: pointer;
            color: #fff;
            border: none;
            text-shadow: 1px 1px 1px rgba(0, 0, 0, .1);
            font: 700 18px/42px 微软雅黑;
            margin: 40px 0 0 0;
            transition: all .5s linear;
            -moz-transition: all .2s linear;
            -webkit-transition: all .2s linear;
            -o-transition: all .5s linear;
            top: 0
        }

        .Login[_ngcontent-c0] button[_ngcontent-c0]:hover {
            background: #d99e4c
        }

        .Login[_ngcontent-c0] button[_ngcontent-c0]:active {
            position: relative;
            top: 2px
        }

        .Login[_ngcontent-c0]   .link[_ngcontent-c0] {
            position: absolute;
            right: 5px;
            bottom: 15px;
            color: #ccc
        }

        .Login[_ngcontent-c0]   .link[_ngcontent-c0] i[_ngcontent-c0] {
            background: url(othericon.f9b4c9cf899f6ae7eeb5.png) no-repeat;
            display: inline-block;
            width: 16px;
            height: 16px;
            vertical-align: middle;
            background-position: 0 -525px;
            margin: 0 2px 0 0;
            position: relative;
            top: -1px
        }

        .Login[_ngcontent-c0]   .link[_ngcontent-c0] a[_ngcontent-c0] {
            color: #999;
            margin: 0 10px;
            position: relative;
            top: 1px
        }

        .Login[_ngcontent-c0]   .link[_ngcontent-c0] a[_ngcontent-c0]:hover {
            color: #d99536;
            text-decoration: underline
        }

        .Login_Bg[_ngcontent-c0]   .gohome[_ngcontent-c0] {
            display: inline-block;
            width: 110px;
            height: 30px;
            text-align: right;
            line-height: 30px;
            color: #fff;
            background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG4AAAAeCAYAAADNeSs6AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDoyRDRDQTBBNTc1NzAxMUU2Qjg0NDg1RjJFRkI0QUY3NCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDoyRDRDQTBBNjc1NzAxMUU2Qjg0NDg1RjJFRkI0QUY3NCI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjJENENBMEEzNzU3MDExRTZCODQ0ODVGMkVGQjRBRjc0IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjJENENBMEE0NzU3MDExRTZCODQ0ODVGMkVGQjRBRjc0Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+K5hzzQAAAa9JREFUeNrsm7FqwlAUho8mTalDhYKbk0MH7ZSMrm4OLm72HfogpUOhg6sUN6F1SEkg9A36HHVphy6CCOn5s7W0aKK5SeD/4A9Xcu5B7s9JziWJLSKO6lZ1rboQc7wJScuX6kV1b+nhTnWjOjP8J97pQ2pOVVeqk5oePgxXGivuCJVXL8g0chjnda5BNcls3GQykdVqlQhjYhY7q2mz2Uwsy0p+Ywzm8zlX1BBoTuI0E0ajkSwWC7Htn55vt1sZj8eyXC7ZnJTNuMFgIL7vi+M4f57fbDYyHA4liiIaV5Z7XL/fT6rpP9MAziEGsaQExrmum1Rao9HYGYsYxGIOKdC4brcrQRBIs9ncOyliMafX63GFizCu0+lIGIbSarVSJ8YcmIccxLBx0+lU2u125uSYixzEsHFo8Q/lGDnIAduBOI7TJa7VuB0ow3aA0DhC42hcajzPS+5jEMakIs3J7+Zj13k2JwVV3Hq9ljxiSc7G4VnbPlsCxPC5XIkulTnASyW7ShpHaBzJ27hPLkPlSF6IfeQ6VI5nvF/3qsLj7Usx+/0Avx3IUGmqJ9XDtwADANhUdH7J1ZnsAAAAAElFTkSuQmCC) no-repeat;
            padding-right: 12px;
            position: absolute;
            top: 10px;
            right: 10px;
            filter: alpha(opacity=50);
            -moz-opacity: .5;
            -khtml-opacity: .5;
            opacity: .5;
            transition: all .2s linear;
            -moz-transition: all .2s linear;
            -webkit-transition: all .2s linear;
            -o-transition: all .2s linear
        }

        .Login_Bg[_ngcontent-c0]   .gohome[_ngcontent-c0]:hover {
            filter: alpha(opacity=80);
            -moz-opacity: .8;
            -khtml-opacity: .8;
            opacity: .8
        }

        .Login_Bg[_ngcontent-c0]   .gohome[_ngcontent-c0]:active {
            top: 11px
        }

        .center[_ngcontent-c0] {
            width: 1200px;
            margin: 0 auto
        }

        .register[_ngcontent-c0] {
            background: url(register_04.4bb0e27212000de860e0.jpg) no-repeat center 60px;
            background-size: 100% auto;
            width: 100%;
            height: 100%;
            min-width: 1200px;
            min-height: 510px
        }

        .register[_ngcontent-c0]   .center[_ngcontent-c0], .register_tit[_ngcontent-c0] {
            position: relative
        }

        .register_tit[_ngcontent-c0] {
            height: 60px;
            border-bottom: 1px solid #e8e8e8;
            background: #fff;
            line-height: 60px;
            color: #999
        }

        .register_tit[_ngcontent-c0]   .logo[_ngcontent-c0] a[_ngcontent-c0] {
            position: absolute;
            left: 0;
            top: 0
        }

        .register_tit[_ngcontent-c0]   .logo[_ngcontent-c0] h2[_ngcontent-c0] {
            font-size: 22px;
            margin-left: 120px;
            color: #333
        }

        .register_tit[_ngcontent-c0]   .signin[_ngcontent-c0] a[_ngcontent-c0] {
            color: #e6c18d;
            margin-left: 5px
        }

        .register_tit[_ngcontent-c0]   .signin[_ngcontent-c0] a[_ngcontent-c0]:hover {
            color: #d99536;
            text-decoration: underline
        }

        .register_box[_ngcontent-c0] {
            background: #fff;
            width: 600px;
            margin: 60px auto 0 auto;
            box-shadow: 0 1px 10px rgba(0, 0, 0, .1);
            padding: 30px 0 60px;
            position: relative;
            z-index: 2
        }

        .register_box[_ngcontent-c0]   .process_img[_ngcontent-c0] {
            background: url(data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAABGAAD/4QN2aHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjUtYzAxNCA3OS4xNTE0ODEsIDIwMTMvMDMvMTMtMTI6MDk6MTUgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ9InhtcC5kaWQ6YWMwNTU5MGYtNDA3MC04ZjQzLWFlYjEtY2ExYzY4ZWIyMmVlIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkREQjQzQjNBMjdBNzExRTY5RTVBODEwRDkwRjE2MzAzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkREQjQzQjM5MjdBNzExRTY5RTVBODEwRDkwRjE2MzAzIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDozZDY5M2M2Zi1jYzViLTk4NGUtOGFmZi00Mjk3MzZmNmFiN2EiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6Mzg5MkIzQUYyNjQ0MTFFNkFERDNFQ0EyOEJBODBGOTUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJE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) no-repeat;
            width: 420px;
            height: 90px;
            margin: 0 auto
        }

        .register_box[_ngcontent-c0]   .forgetpsw[_ngcontent-c0] {
            background: url(data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAABGAAD/4QN2aHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjUtYzAxNCA3OS4xNTE0ODEsIDIwMTMvMDMvMTMtMTI6MDk6MTUgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ9InhtcC5kaWQ6YWMwNTU5MGYtNDA3MC04ZjQzLWFlYjEtY2ExYzY4ZWIyMmVlIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjZEOUI3REQyMzJENDExRTZBM0EzQ0Y1QkM3NUZGRjQyIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjZEOUI3REQxMzJENDExRTZBM0EzQ0Y1QkM3NUZGRjQyIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpkZmUxOWNiNy00YmYyLTg4NDYtOGJjZS1mNzg5MWJmMGQ5NTciIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6Mzg5MkIzQUYyNjQ0MTFFNkFERDNFQ0EyOEJBODBGOTUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJE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) no-repeat
        }

        .register_box[_ngcontent-c0]   .process_img[_ngcontent-c0] img[_ngcontent-c0] {
            clip: rect(0 100px 90px 0);
            position: absolute
        }

        .register_box[_ngcontent-c0]   .process_img.second[_ngcontent-c0] img[_ngcontent-c0] {
            clip: rect(0 240px 90px 0)
        }

        .register_box[_ngcontent-c0]   .process_img.third[_ngcontent-c0] img[_ngcontent-c0] {
            clip: rect(0 420px 90px 0)
        }

        .register_con[_ngcontent-c0] {
            width: 380px;
            margin: 0 auto;
            padding-top: 50px;
            position: relative
        }

        .register_con[_ngcontent-c0] li[_ngcontent-c0] {
            margin-bottom: 30px;
            height: 44px;
            position: relative;
            line-height: 44px
        }

        .register_con[_ngcontent-c0] li[_ngcontent-c0] input.barcode[_ngcontent-c0] + a[_ngcontent-c0] {
            display: inline-block;
            height: 44px;
            line-height: 44px;
            text-align: center;
            width: 120px;
            color: #999;
            border: 1px solid #e8e8e8;
            margin-left: 20px;
            background: #f5f5f5;
            background: linear-gradient(180deg, #fff, #f2f2f2);
            background: -webkit-linear-gradient(top, #fff, #f2f2f2)
        }

        .register_con[_ngcontent-c0] li[_ngcontent-c0] input.barcode[_ngcontent-c0] + a[_ngcontent-c0]:hover {
            background: #f2f2f2;
            background: linear-gradient(180deg, #fff, #f2f2f2);
            background: -webkit-linear-gradient(bottom, #fff, #f2f2f2)
        }

        .register_con[_ngcontent-c0] li[_ngcontent-c0] input.barcode[_ngcontent-c0] + a[_ngcontent-c0]:active {
            background: #eee;
            background: linear-gradient(180deg, #fff, #eee);
            background: -webkit-linear-gradient(bottom, #fff, #eee);
            position: relative;
            top: 1px
        }

        .register_con[_ngcontent-c0] input[type=password][_ngcontent-c0], .register_con[_ngcontent-c0] input[type=text][_ngcontent-c0] {
            border: 1px solid #e8e8e8;
            height: 44px;
            width: 380px;
            padding: 0 5px 0 35px;
            font-size: 14px;
            font-family: Microsoft YaHei;
            color: #ccc;
            background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAIxCAYAAABEnDjbAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NjU1MTY2NEY0RDk3MTFFNkI4NThFNURCMjlDN0MxM0MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NjU1MTY2NEU0RDk3MTFFNkI4NThFNURCMjlDN0MxM0MiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjdCMEQ1M0EwM0FBRDExRTY5OUVDOTY0OUVGQzI4NzhDIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjdCMEQ1M0ExM0FBRDExRTY5OUVDOTY0OUVGQzI4NzhDIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+qBfArQAABzZJREFUeNrsXFuIHEUU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) no-repeat;
            transition: all .5s linear;
            -moz-transition: all .2s linear;
            -webkit-transition: all .2s linear;
            -o-transition: all .5s linear
        }

        .register_con[_ngcontent-c0] input[_ngcontent-c0]:focus {
            border-color: #e6c18d;
            color: #333
        }

        .register_con[_ngcontent-c0] input.username[_ngcontent-c0] {
            background-position: 10px -289px
        }

        .register_con[_ngcontent-c0] input.username[_ngcontent-c0]:focus {
            background-position: 10px -319px
        }

        .register_con[_ngcontent-c0] input.password[_ngcontent-c0] {
            background-position: 10px -107px
        }

        .register_con[_ngcontent-c0] input.password[_ngcontent-c0]:focus {
            background-position: 10px -137px
        }

        .register_con[_ngcontent-c0] input.passwordtwo[_ngcontent-c0] {
            background-position: 10px -167px
        }

        .register_con[_ngcontent-c0] input.passwordtwo[_ngcontent-c0]:focus {
            background-position: 10px -197px
        }

        .register_con[_ngcontent-c0] input.phone[_ngcontent-c0] {
            background-position: 10px 12px
        }

        .register_con[_ngcontent-c0] input.phone[_ngcontent-c0]:focus {
            background-position: 10px -18px
        }

        .register_con[_ngcontent-c0] input.barcode[_ngcontent-c0] {
            background-position: 10px -48px;
            width: 240px
        }

        .register_con[_ngcontent-c0] input.barcode[_ngcontent-c0]:focus {
            background-position: 10px -78px
        }

        .register_con[_ngcontent-c0] input.shopname[_ngcontent-c0] {
            background-position: 10px -348px
        }

        .register_con[_ngcontent-c0] input.shopname[_ngcontent-c0]:focus {
            background-position: 10px -378px
        }

        .register_con[_ngcontent-c0] input.address[_ngcontent-c0] {
            background: none;
            padding: 0 10px
        }

        .register_con[_ngcontent-c0] button[_ngcontent-c0] {
            width: 380px;
            height: 44px;
            background: #269969;
            text-align: center;
            cursor: pointer;
            color: #fff;
            border: none;
            text-shadow: 1px 1px 1px rgba(0, 0, 0, .1);
            font: 700 18px/44px 微软雅黑;
            margin: 20px 0 0 0;
            transition: all .5s linear;
            -moz-transition: all .2s linear;
            -webkit-transition: all .2s linear;
            -o-transition: all .5s linear
        }

        .register_con[_ngcontent-c0] button[_ngcontent-c0]:hover {
            background: #208058
        }

        .register_con[_ngcontent-c0] button[_ngcontent-c0]:active {
            position: relative;
            top: 1px
        }

        .register_con[_ngcontent-c0] li.address[_ngcontent-c0], .register_con[_ngcontent-c0] li.xingzhi[_ngcontent-c0] {
            border: 1px solid #e8e8e8
        }

        .register_con[_ngcontent-c0] li.xingzhi[_ngcontent-c0] label[_ngcontent-c0] {
            margin-left: 20px;
            color: #333;
            cursor: pointer
        }

        .register_con[_ngcontent-c0] li.address[_ngcontent-c0]:before, .register_con[_ngcontent-c0] li.xingzhi[_ngcontent-c0]:before {
            display: inline-block;
            height: 42px;
            border-right: 1px solid #e8e8e8;
            color: #ccc;
            padding: 0 20px 0 35px;
            background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAIxCAYAAABEnDjbAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NjU1MTY2NEY0RDk3MTFFNkI4NThFNURCMjlDN0MxM0MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NjU1MTY2NEU0RDk3MTFFNkI4NThFNURCMjlDN0MxM0MiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjdCMEQ1M0EwM0FBRDExRTY5OUVDOTY0OUVGQzI4NzhDIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjdCMEQ1M0ExM0FBRDExRTY5OUVDOTY0OUVGQzI4NzhDIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+qBfArQAABzZJREFUeNrsXFuIHEUUnWoKDQgqQVw0LFk/4p+LyEYFSfyQ/DgKIj4/Iig+QBAxP4oQRBIkgvrhA/H1JyL4xl0Vn0Qw+rGCs34G8TFLdldQIj4iKpa35PZYW32r6lbNZKd3vQ0nO9Nd9/Stqtt163TVRBljOsMcVWfIQ9cfFhYWTL/fZxlNTk52pqen1b9fbBUsZmdnTf05Bbfs0FUQAiFoB4F2v8zNzZmRetDtdpVFrIyqB5TSx1mNfUQSAiEQAm9AWfr4kazH8oyde1RjRKpPpg73ZtILQrARs3PuA5X0wD5cqQdMc5+6ZHqXOBACIWjTgOIf/vSfnLlz9HHsHNs4dI3VBm5VbDXc7xXH2DfK6oXaOCR9WFWI6qZQI4Ua0j+/alT2XaXq3fAmxMx9pzKUMRkHsRaPPgt1fXOMJbU5jQjzgGxDmHikR6S6kDPh4A9p1tg3oM7pyPRmbTyouMYhr6qcFqdIKo4xdWc2QW0cihVWFWKBVuW67HujQxdDJMFA8plj9Q5pZ+pO/mijctK7IQwa56oM49oDUzKgmBBJxTRWRHuwCVSkSuwqqJxBNeqy742OXAyRqFAc+PU0Mdf9KhQZp9oge6ZqUi0u2VkIhEAI1lY7r6ysmPn5ebLwzMxMZ2JiQrFUG1cSj78NBoPq4uKi6fV6LKPg2vvy8nJS7tkywbX3RgsTh1+mSkn/1Cpoy9/icFRs5YdxysAvo92+xWfAcOJgA2VnzVElWdI3pFSSgoOjkSjy4xOJqWq0qwqjGxNH1oipgIrKf040sl5AuHdLdWmLesGpW6pbFOdFlGIo2BbmBRNzNaStdI7MpcirTOMO9wWEe7f1nJ1zxDc3jBtlNCdcRTsLgRAIwXjHRLaGztHJ1DUdu2Mt+UjV7qv3HNVOepA6fAFqv1vP2ASUBLQkqt/vl6l2JNDWONpIXiMDQTOQSlR7NBI5qr3lz0JMtbtVG8SBbWFOL1DkulS1t2d+oEvWnFkjEleEa06hGHnWynf2RDPLA+pu/nJx0oMc1c6dKwdJWNsHUt5ZkuxQ9hd22a+BokvnJao9+jBxVPvgcT5y8GGWsAipmVLJY1LKlTqnOIGkcqujM0LAUN9zCCjvjC5V7VQVVGYVVgWSKqxCdPvAes7OiTYhxbfJ6AXFeYsj6l0IhOB/qJ1zfhhJzd51bZy7wdUKD2tb1cY5PxB1t+NWrszhkPh7eStfK8VIqI3AFSW4KJLQLuIqpNpcktgWZB1T65wtyKNd9R1LKKterzdUKEtqG4H0bdGKZ+nS+UAz5aw1u9twK9cDDom/h7ex5hojYW36DpGEdg9XIY3oksS2HrM2vsd6qOgFRMtGpKFDueRnmaWqjdQPrmozmcbKb0QuySp1Q636Gq5xqBtV5FWA4saBIjS1yg1l1WFsQR75qu/4pK8Z28Mk030hEAIhGIxIoIvaMSY2si53qNfcgp3M3zMpbpJpbxwYbuOmftdW1I2qFXHAXqgoyc6rlk10Zv2VT1IVNp5qXySqAlv5z8aEQAg86SvZWbKzZGfJzpKdhUAINqR2vgTQ7/y3iSeFRbQZjInPAnYCvmbe+CzAh/Zv7cFUhnEHy04dt17Yl6j/vlRi2YsYXyA9GHD9KS7B3Tji+rhtTapQYR/Xbr+cOz/4G6NsHY4H3wC2ZtjZ5+Bbtw1uBhwCnMkk+A5wk0vwAWBLSRUkOwuBEIxYO0t2HiI71xvfs/5nKkd4KcnOkp0lO8uwLgQbKzvbf0r1c7fbJT3YBHgecBTwK+IontuUqsLZgCXABGA74CTEdjy3hGVIAsv+GeAAYBfgsHPtMJ47gGVOoAhs5vkSM1PosNcWAE9QBJfh0FYf+wHHEPud87cArgxVoXZ7M+AuHGin8PNmpzonUonFHdtOAfwG+B6/H8NzP8Yy05+ASZxs2fT2C+BzvPazMwXaimUbVXgb4P7MbhtgFrHNOf8Qlm14YOv5FeBUDJy/APd5Htuhv+vmT9eDFcDriYxsvXkFy5KRaLPNeYBLCeNrsUdujIXyH4DrAC8ATnbOnwZ4BnANVi36OL+LBIecc58AHge8l5pg1MftgE8BT2KjLgPu5cxQ3OMiwBf4+VzuFMef7kxLdhYCIcjIzqX62e5rl+ws2Vmys2Rnyc5CIASjzs7DrDuHtLN77MD3bNSb7WQV7JvudwBPd5qvR7ekxgNr/BbgHsBjeO4jLL8jNaBcDHgTcCfgOa/s76kRyRq/AbgV8CLRHtEhzRq/CtiNHoQO+9r4iE9gB9CXMHm8HzG2c4O9mN5W9cIDgEcTxldj8rUTkYO+B/b96IUR413YnZe7xq4HdwBOJ7KxPS7ATHWFb+x68BPepU5d9+PfcwCvAa6ijP1ecEnOx9RmM/H1IWMqlGuSH/Dv7phxKBItyQ3cJ1JSmxAIwcbJzsOsO4e0s2Rnyc6SnQuysyRXIRACIRACIRACIRCC5vGPAAMAsXZ/5/QHc+IAAAAASUVORK5CYII=) no-repeat
        }

        .register_con[_ngcontent-c0] li.xingzhi[_ngcontent-c0]:before {
            content: "\6027\8D28";
            background-position: 10px -468px
        }

        .register_con[_ngcontent-c0] li.xingzhi[_ngcontent-c0]:hover:before {
            background-position: 10px -498px
        }

        .register_con[_ngcontent-c0] li.address[_ngcontent-c0]:before {
            content: "\8054\7CFB\5730\5740";
            background-position: 10px -408px
        }

        .register_con[_ngcontent-c0] li.address[_ngcontent-c0]:hover:before {
            background-position: 10px -438px
        }

        .register_con[_ngcontent-c0] li.address[_ngcontent-c0] select[_ngcontent-c0] {
            cursor: pointer;
            height: 41px;
            background: none;
            border: none;
            border-right: 1px solid #e8e8e8;
            width: 87px;
            padding: 0 10px;
            font-size: 14px;
            font-family: Microsoft YaHei;
            color: #333
        }

        .register_con[_ngcontent-c0] li.address[_ngcontent-c0] select[_ngcontent-c0]:last-child {
            border: none
        }

        .register_con[_ngcontent-c0] li.userages[_ngcontent-c0] {
            height: 20px;
            margin: 0;
            font-size: 12px;
            position: relative;
            top: -20px
        }

        .register_con[_ngcontent-c0] li.userages[_ngcontent-c0] a[_ngcontent-c0] {
            color: #2980cc
        }

        .register_end[_ngcontent-c0] {
            text-align: center;
            padding: 160px 0
        }

        .register_end[_ngcontent-c0] p[_ngcontent-c0] {
            font-size: 22px;
            font-weight: 700;
            color: #333
        }

        .register_end[_ngcontent-c0] span[_ngcontent-c0] {
            color: #ccc;
            display: block;
            padding-top: 10px
        }

        .register_end[_ngcontent-c0] a[_ngcontent-c0] {
            color: #999;
            margin: 0 10px;
            position: relative;
            top: 1px
        }

        .register_end[_ngcontent-c0] a[_ngcontent-c0]:last-child {
            color: #269969
        }

        .register_end[_ngcontent-c0] a[_ngcontent-c0]:hover {
            color: #269969;
            text-decoration: underline
        }

        .floatsview[_ngcontent-c0] {
            top: 110%;
            left: 0;
            position: absolute;
            background: #333;
            background: rgba(0, 0, 0, .8);
            color: #fff;
            font-size: 12px;
            line-height: 18px;
            padding: 5px 10px;
            z-index: 999;
            white-space: normal;
            filter: alpha(opacity=0);
            -moz-opacity: 0;
            -khtml-opacity: 0;
            opacity: 0;
            transition: all .5s linear;
            -moz-transition: all .2s linear;
            -webkit-transition: all .2s linear;
            -o-transition: all .5s linear
        }

        .floatsview.in[_ngcontent-c0] {
            filter: alpha(opacity=1);
            -moz-opacity: 1;
            -khtml-opacity: 1;
            opacity: 1
        }

        .floatsview.in[_ngcontent-c0] + input[_ngcontent-c0] {
            border-color: #e64c2e;
            box-shadow: 0 0 5px 2px rgba(230, 76, 46, .2)
        }

        .floatsview[_ngcontent-c0] i[_ngcontent-c0] {
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-bottom: 4px solid #333;
            display: inline-block;
            position: absolute;
            top: -4px;
            left: 15px
        }

        .register_box[_ngcontent-c0] + .CopyRight[_ngcontent-c0] {
            color: rgba(0, 0, 0, .3)
        }

        .CopyRight[_ngcontent-c0] {
            position: fixed;
            bottom: 10px;
            text-align: center;
            width: 100%;
            color: hsla(0, 0%, 100%, .5);
            z-index: 1
        }

        .thin-scroll[_ngcontent-c0]::-webkit-scrollbar, .white-scroll[_ngcontent-c0]::-webkit-scrollbar {
            width: 6px;
            height: 4px
        }

        .thin-scroll[_ngcontent-c0]::-webkit-scrollbar-thumb, .thin-scroll[_ngcontent-c0]::-webkit-scrollbar-track {
            background-color: hsla(0, 0%, 100%, .2)
        }

        .white-scroll[_ngcontent-c0]::-webkit-scrollbar-thumb, .white-scroll[_ngcontent-c0]::-webkit-scrollbar-track {
            background-color: rgba(0, 0, 0, .2)
        }

        .Agreement[_ngcontent-c0] {
            position: absolute;
            top: 0;
            bottom: 10%;
            left: 50%;
            width: 600px;
            margin: 0 0 0 -300px;
            background: #fff;
            line-height: 24px;
            color: #666;
            z-index: 998;
            padding: 20px;
            overflow-y: auto;
            -webkit-transform: translateY(-100%);
            transform: translateY(-100%);
            transition: all .5s linear;
            -moz-transition: all .2s linear;
            -webkit-transition: all .2s linear;
            -o-transition: all .5s linear
        }

        .Agreement[_ngcontent-c0] h2[_ngcontent-c0] {
            text-align: center;
            font-size: 18px;
            padding: 10px 0 20px 0;
            color: #333
        }

        .Agreement[_ngcontent-c0] b[_ngcontent-c0] {
            display: block;
            margin: 30px 0 10px 0;
            color: #333
        }

        .Agreement.in[_ngcontent-c0] {
            top: 10%;
            -webkit-transform: translateY(0);
            transform: translateY(0)
        }

        .Agreement[_ngcontent-c0] a[_ngcontent-c0] {
            display: inline-block;
            font-weight: 700;
            width: 120px;
            height: 40px;
            background: #e64c2e;
            color: #fff;
            text-align: center;
            line-height: 40px;
            margin: 20px 0 0 220px
        }

        .bgblack[_ngcontent-c0] {
            position: fixed;
            background-color: rgba(0, 0, 0, .7);
            width: 100%;
            height: 0;
            z-index: 997;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0
        }

        .bgblack.in[_ngcontent-c0] {
            height: 100%
        }

        .login_App[_ngcontent-c0] {
            position: fixed;
            z-index: 2;
            bottom: 0;
            right: 0;
            background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDoyRDUyRENDOUYzMjkxMUU2QkUxMDg4MDI5RkM0Qjg2MCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDoyRDUyRENDQUYzMjkxMUU2QkUxMDg4MDI5RkM0Qjg2MCI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjJENTJEQ0M3RjMyOTExRTZCRTEwODgwMjlGQzRCODYwIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjJENTJEQ0M4RjMyOTExRTZCRTEwODgwMjlGQzRCODYwIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+EVyyVwAAAoNJREFUeNrs3UFKw0AUxvFkUBQXgku3XqAnELIQj+I1zL436M5ruPEMXsBtcS1CddGYgEgbWkysmfnem/8DUQKakB/vzZtpJpZN0xSETNyXgOhgtF81IEIY3Q9bIMvFjFsTOS7vnn8wujjilqSL06vbp/ZbtXkscFvSYaxeHqv+cUCEMAARwwBEDAMQMQxAxDAAiTTPGIoBSORJHyDGMAARw+iCpZMJBvCitxxChhjopgAxgAGIGAYgYhiAiGEAIoYBiBgGIGIYgIhhMFMfEd/LIS3GfNLzkCHDMeoY5wJECAMQMQzGkF8G8OViVsU+LxmSqJsCxAAGIGIYgIhhACKGAYgYRvZtb6zlEDJEdNIHiDGMLEGUMbIDUcfICsQCRjZdVlqMplh/vO3PiJPzvEDGPmv71736Lfpujs/34vXhevDvBe8YSpO+rMcQixhuQaxiuASxjOEOxDqGKxAPGF24eD1TN89or73+p78VrSV2mSFWZuBZgHjDMA3iEcMsiFcMkyCeMcyBeMcw1fYe0tqOaTvJEDLDFkhOGPIguWFIg+SIIQuSK4YkSM4YXUg95NDfRhajXU3d6ss+5ODl8wwXJQsMIRAwhMaQsRhTfJpHhpAZeiBgCJWs/jYy8RXm6K11SIBRkwcCIGAIgYAhNIYc+i70obVYaZnlkGsJU2PQTYmAgCEEAobQGLILg/+xmyhDyAwhEDCESpYCRuqWWCZDyAwhEDCESlaOGDFKXwDDeMkCQ6hkTY1RHp8VZdi4pGbdHgyA7MO4uJlX+14YGWPLcOz6nmKFIShkBjECBAwhEDCEQMAQAgFDCASMtLG1LboNng4RAgFDCAQMIRAwhOJLgAEAbjaQxFiwv+gAAAAASUVORK5CYII=) no-repeat 100% 100%;
            min-height: 100px
        }

        .login_App[_ngcontent-c0] p[_ngcontent-c0] {
            line-height: 22px;
            padding: 10px 15px;
            color: #666;
            background: linear-gradient(0deg, #fff, #eee, #fff);
            box-shadow: 0 0 20px rgba(0, 0, 0, .4);
            margin: 0 20px 80px 0;
            position: relative;
            z-index: 2;
            animation: app_code_move .5s linear infinite alternate;
            -webkit-animation: app_code_move .5s linear infinite alternate
        }

        .login_App[_ngcontent-c0] p[_ngcontent-c0] b[_ngcontent-c0] {
            color: #1981d9;
            float: right;
            font-weight: 400;
            text-align: right;
            position: relative;
            padding: 0 10px 0 0
        }

        .login_App[_ngcontent-c0] p[_ngcontent-c0] a.ccc[_ngcontent-c0]:hover {
            text-decoration: underline
        }

        .login_App[_ngcontent-c0] p[_ngcontent-c0] i.aaa[_ngcontent-c0] {
            position: absolute;
            bottom: -12px;
            right: 30px;
            display: inline-block;
            border-left: 12px solid transparent;
            border-top: 12px solid #fff;
            border-right: 0 solid #fff
        }

        .login_App[_ngcontent-c0] p[_ngcontent-c0] i.bbb[_ngcontent-c0] {
            font-size: 18px;
            font-weight: 400;
            position: absolute;
            margin: 0 0 0 5px;
            transform: rotate(90deg);
            -webkit-transform: rotate(90deg)
        }

        .login_App[_ngcontent-c0] a.ddd[_ngcontent-c0] {
            position: absolute;
            z-index: 3;
            right: 0;
            bottom: 0;
            width: 100px;
            height: 120px;
            display: inline-block
        }

        @keyframes app_code_move {
            0% {
                transform: translateY(4%);
                -webkit-transform: translateY(4%)
            }
            to {
                transform: translateY(-4%);
                -webkit-transform: translateY(-4%)
            }
        }

        @-webkit-keyframes app_code_move {
            0% {
                transform: translateY(4%);
                -webkit-transform: translateY(4%)
            }
            to {
                transform: translateY(-4%);
                -webkit-transform: translateY(-4%)
            }
        }

        .app_code_view[_ngcontent-c0] {
            position: fixed;
            z-index: 998;
            right: 0;
            bottom: 0;
            transform: translate(120%, 120%);
            -webkit-transform: translate(120%, 120%);
            width: 400px;
            height: 300px;
            box-shadow: 0 0 20px rgba(0, 0, 0, .5);
            background: #fff;
            padding: 30px;
            text-align: center
        }

        .app_code_view[_ngcontent-c0] h2[_ngcontent-c0] {
            padding-bottom: 40px
        }

        .app_code_view[_ngcontent-c0] img[_ngcontent-c0] {
            width: 120px
        }

        .app_code_view[_ngcontent-c0] div[_ngcontent-c0] {
            display: inline-block;
            margin: 0 20px
        }

        .app_code_view[_ngcontent-c0] span[_ngcontent-c0] {
            display: block;
            color: #666
        }

        .app_code_view.in[_ngcontent-c0] {
            right: 50%;
            bottom: 50%;
            margin: 0 -200px -150px 0;
            transform: translate(0);
            -webkit-transform: translate(0)
        }

        .app_code_view[_ngcontent-c0]   .close[_ngcontent-c0] {
            display: inline-block;
            float: right;
            margin: -15px -15px 0 0
        }

        .feedback[_ngcontent-c0] {
            position: fixed;
            z-index: 999;
            top: 40%;
            left: 50%;
            padding: 25px 30px;
            transform: translateX(-50%);
            -webkit-transform: translateX(-50%);
            background-color: rgba(0, 0, 0, .8);
            color: #fff;
            text-align: center;
            font-size: 16px;
            visibility: hidden;
            opacity: 0
        }

        .feedback.in[_ngcontent-c0] {
            visibility: inherit;
            opacity: 1
        }
    </style>
    <style>
        body[_ngcontent-c0], html[_ngcontent-c0] {
            padding: 0;
            margin: 0;
            font-family: Helvetica Neue, helvetica, lucida grande, lucida sans unicode, lucida, Microsoft YaHei, WenQuanYi Micro Hei, sans-serif;
            background: #f6f6f6;
            font-size: 14px
        }

        blockquote[_ngcontent-c0], dd[_ngcontent-c0], div[_ngcontent-c0], dl[_ngcontent-c0], dt[_ngcontent-c0], fieldset[_ngcontent-c0], form[_ngcontent-c0], h1[_ngcontent-c0], h2[_ngcontent-c0], h3[_ngcontent-c0], h4[_ngcontent-c0], h5[_ngcontent-c0], h6[_ngcontent-c0], img[_ngcontent-c0], input[_ngcontent-c0], label[_ngcontent-c0], li[_ngcontent-c0], ol[_ngcontent-c0], p[_ngcontent-c0], pre[_ngcontent-c0], table[_ngcontent-c0], td[_ngcontent-c0], textarea[_ngcontent-c0], th[_ngcontent-c0], ul[_ngcontent-c0] {
            margin: 0;
            padding: 0;
            border: 0 none
        }

        table[_ngcontent-c0] {
            border-collapse: collapse;
            border-spacing: 0
        }

        address[_ngcontent-c0], caption[_ngcontent-c0], cite[_ngcontent-c0], code[_ngcontent-c0], dfn[_ngcontent-c0], em[_ngcontent-c0], i[_ngcontent-c0], th[_ngcontent-c0], var[_ngcontent-c0] {
            font-style: normal;
            font-weight: 400
        }

        article[_ngcontent-c0], aside[_ngcontent-c0], details[_ngcontent-c0], figcaption[_ngcontent-c0], figure[_ngcontent-c0], footer[_ngcontent-c0], header[_ngcontent-c0], hgroup[_ngcontent-c0], menu[_ngcontent-c0], nav[_ngcontent-c0], section[_ngcontent-c0] {
            display: block
        }

        img[_ngcontent-c0] {
            vertical-align: top
        }

        ol[_ngcontent-c0], ul[_ngcontent-c0] {
            list-style-type: none
        }

        a[_ngcontent-c0] {
            text-decoration: none;
            cursor: pointer
        }

        [_ngcontent-c0]:focus {
            outline: none
        }

        .clear[_ngcontent-c0] {
            overflow: hidden;
            float: none;
            line-height: 0;
            font-size: 0
        }

        .clear[_ngcontent-c0], .clearfix[_ngcontent-c0]:after {
            clear: both;
            height: 0;
            display: block
        }

        .clearfix[_ngcontent-c0]:after {
            visibility: hidden;
            content: "."
        }

        [_ngcontent-c0]:first-child + html[_ngcontent-c0]   .clearfix[_ngcontent-c0], html[_ngcontent-c0]   .clearfix[_ngcontent-c0] {
            zoom: 1
        }

        *[_ngcontent-c0], [_ngcontent-c0]:after, [_ngcontent-c0]:before {
            -o-box-sizing: border-box;
            -ms-box-sizing: border-box;
            box-sizing: border-box
        }

        .hide[_ngcontent-c0] {
            display: none
        }

        .show[_ngcontent-c0] {
            display: block
        }

        .fl[_ngcontent-c0] {
            float: left
        }

        .fr[_ngcontent-c0] {
            float: right
        }

        .r3px[_ngcontent-c0] {
            border-radius: 3px;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px
        }

        .r5px[_ngcontent-c0] {
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px
        }

        .r10px[_ngcontent-c0] {
            border-radius: 10px
        }

        .flex[_ngcontent-c0] {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .flex-1[_ngcontent-c0] {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1
        }

        .flex-v[_ngcontent-c0] {
            -webkit-box-orient: vertical;
            -ms-flex-direction: column;
            flex-direction: column
        }

        input[type=checkbox][_ngcontent-c0], input[type=radio][_ngcontent-c0] {
            width: 14px;
            height: 14px;
            background: #fff;
            cursor: pointer;
            margin-right: 3px;
            position: relative;
            top: 2px
        }

        [disabled=disabled][_ngcontent-c0] {
            background: #f9f9f9 !important;
            color: #ccc !important
        }
        input[type=submit]{
            width: 300px;
            height: 42px;
            background: #e1b26f;
            text-align: center;
            cursor: pointer;
            color: #fff;
            border: none;
            text-shadow: 1px 1px 1px rgba(0, 0, 0, .1);
            font: 700 18px/42px 微软雅黑;
            margin: 40px 0 0 0;
            transition: all .5s linear;
            -moz-transition: all .2s linear;
            -webkit-transition: all .2s linear;
            -o-transition: all .5s linear;
            top: 0;
        }
    </style>
</head>
<body>
<div class="main">
    <div class="content" style="margin: 0;">
        <div _ngcontent-c0 class="Login_Bg">
            <form method="post" action="">
                <div _ngcontent-c0="" class="Login r10px">
                    <div _ngcontent-c0="" class="Logo"><a _ngcontent-c0="" href="javascript:void(0)">
                        <img _ngcontent-c0="" height="74" src="/admin/images/login_1.jpg" width="354">
                        <!--<img _ngcontent-c0="" height="74" src="/admin/images/login_1.jpg" width="354">-->
                    </a></div>
                    <div _ngcontent-c0="" class="Login_Form">
                        <div _ngcontent-c0="" class="List_Input">
                            <input _ngcontent-c0="" autocomplete="off" autofocus="true" maxlength="25"
                                   placeholder="请输入登录名" type="text" class="ng-pristine ng-invalid ng-touched" name="username">
                        </div>
                        <div _ngcontent-c0="" class="List_Input">
                            <input _ngcontent-c0="" autocomplete="off" maxlength="20" placeholder="请输入密码" type="password"
                                   class="ng-untouched ng-pristine ng-invalid" name="password">
                        </div>
                        <!---->
                        <input  class="r3px" type="submit" value="登录">
                        <!---->
                        <!---->
                        <!---->
                        <!---->
                        <div _ngcontent-c0="" class="prompt r3px ">请输入登录名或手机号
                        </div>
                        <!---->
                    </div>
                    <div _ngcontent-c0="" class="version" style="position:absolute; left:15px; bottom:15px; color:#999">
                        当前时间：<?php  echo date('Y-m-d H:i:s')?>
                    </div>
                    <div _ngcontent-c0="" class="link">
                        <!--&lt;!&ndash;&ndash;&gt;<a _ngcontent-c0="" class="app_code" href="javascript:void(0);"><i _ngcontent-c0=""></i>手机App下载</a>-->
                        <!--&lt;!&ndash;&ndash;&gt;<a _ngcontent-c0="" href="register.html">注册</a>&lt;!&ndash;&ndash;&gt;&lt;!&ndash;&ndash;&gt;|<a-->
                        <!--_ngcontent-c0="" href="javascript:void(0)">忘记密码？</a>-->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>