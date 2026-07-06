<?php
include_once "html_head.php";
?>
<style>
.note_wrap{position:relative;  width:100%;padding:0px;  -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
.note_wrap .title{ padding: 20px 0; margin: 0 40px; font-size: 26px; font-weight: 700; color: #222; line-height: 1.5;border-bottom: 1px solid #333;  text-align: left;}
.note_wrap .inner{padding: 20px 0; margin: 0 40px; position:relative; }
.note_tab{overflow:hidden;}
.note_tab li{float:left; height:50px;  position:relative;}
.note_tab li a{display:block; position:relative; border: 1px solid #333; margin-left: -1px; height: 40px; padding:0px 30px; border: 1px solid #999;  line-height: 40px;text-align: center; background-color: #f2f2f2;}
.note_tab li .on{background: #fff; color: #000; border: 1px solid #333;  margin-left: -1px;}
.note_tab li:nth-child(1) a{margin-left:-0px;}
.close_btn{margin-left:7px;}
.close_btn{overflow: hidden; position: absolute; top: 30px; right:30px; width: 30px; height: 30px;  padding-top: 0px;  border: 5px solid #fafafa; background: #fafafa url(/skin/demo/img/btn_layer_close.png) no-repeat center; transform: rotate(0);transition: .5s;  border-radius: 60px;}
.close_btn:hover{transform: rotate(180deg); transition:.3s;}

.note_write{border-top:1px solid #333;padding: 0; margin-top:30px;}
.note_write table{width:100%;}
.note_write input{ position: relative; width:100%; height:40px; padding:0 10px;border:1px solid #d9d9d9; font-size: 16px; font-weight: 300;}
.note_write tr {border-bottom: 1px solid #e8e8e8;}
.note_write th{ width: 20%; padding-top: 0; font-weight: 500; color: #333;  padding: 10px 30px; line-height: 1.3em;  font-size: 14px; vertical-align: middle;}
.note_write td{  padding: 10px 30px; line-height: 1.3em;vertical-align: middle;}
.note_write textarea { position: relative;  z-index: 3; width: 100%; height: 350px; padding: 10px; border: 1px solid #d9d9d9;vertical-align: top;  font-size: 16px; color: #333;   line-height: 1.5; font-weight: 333; background: transparent;}


.note_receive_top{ padding:20px;background-color: #f3f3f3;  margin-top:20px}
.note_receive_top span{display:inline-block; margin-right:15px; color:#000; font-size:16px; }
.note_receive_top span + span{ margin-right:15px;}
/* 테이블 */
.data_table table{width:100%; border-top:1px solid #ebebeb;  font-size:15px; margin-top:20px;}
.data_table [class^="btn_"]{min-width:inherit; padding-left:1em; padding-right:1em;font-weight: 400;}
.data_table th{padding:15px 10px; border-bottom:1px solid #ebebeb; font-weight:500; color: #333; background-color:#fafafa}
.data_table tbody th,
.data_table td{padding:15px 10px; line-height:1.5em; border-bottom:1px solid #ebebeb; color:#666;font-weight: 300; text-align:center;}
.data_table tbody th{/* text-align:left; */} /* 정렬은 각 셀에서 적용.. align_center align_left 클래스 이용 */
.data_table tr>* +*{border-left:1px solid #ebebeb;}
.data_table tr:hover{background:#f2f2f2;}
.data_table .border_left{border-left:1px solid #ebebeb;}
.data_table.font_sl{font-size: 14px;}
.left_txt{text-align:left!important;}
.date{color:#888; font-size:14px;}

/* board view */
.note_view{border-top:1px solid #333; margin-top:20px;}
.note_view ul{display:table; width:100%; border-bottom:1px solid #d2d2d2;}

.note_view li.subj{background-color: #f2f2f2;position:relative; padding:21px 10px; line-height:1.3em;}
.note_view li.subj>*{ margin-right:15px;}

.note_view li.cont{ padding:20px 20px; line-height:22px; font-size: 15px; color:#333;}


</style>
<div class="note_wrap">
	<a href="javascript:window.close()" class="close_btn"></a>
	<div class="title">쪽지함</div>
	<div class="inner">