<?php
include_once "path.php";
include_once "$nfor[path]/html_head.php";
?>
<style>
/* Default Style */
.f24{font-size:24px;}
.txt_num{font-family: "montserrat"; font-weight:300;}
.report_wrap{ font-family: 'Spoqa Han Sans';}
.report_wrap li{padding:10px 0px; position:relative;}
.report_wrap .cp_subject{font-size:30px;   font-family: 'notokr-demilight'; display:block; line-height:50px;}
.report_wrap .cp_id{display:inline-block; padding:5px 10px; background-color:#666; color:#fff; border-radius:15px; font-family: "montserrat";}
.report_wrap .cp_subject b{font-weight:normal; font-size:14px; margin-left:15px;}
.report_wrap .sns_icon_wrap { margin-top:5px; display:inline-block}
.report_wrap .sns_icon_wrap span{border-radius:20px; margin-left:5px; display:inline-block}
.report_wrap .sns_icon_wrap .blog_icon { background-color:#fff; border:solid 1px #2ba406; color:#2ba406; margin:0px 1px; padding:3px 8px; font-size:13px; }
.report_wrap .sns_icon_wrap .instagram_icon { background-color:#fff; border:solid 1px #ff3478; color:#ff3478; margin:0px 1px; padding:3px 8px; font-size:13px; }
.report_wrap .sns_icon_wrap .youtube_icon { background-color:#fff; border:solid 1px #f41515; color:#f41515; margin:0px 1px; padding:3px 8px; font-size:13px; }
.report_wrap .cp_order{height:20px; width:20px; display:inline-block; position:relative; vertical-align:-10px;} 
.report_wrap .cp_order{width:40px; height:40px; background:#333}
.report_wrap .cp_order:before{content:''; height:4px; width:20px; background:#fff; display:block; position:absolute; top:10px; left:10px; box-shadow:0 8px #fff, 0 16px #fff;-webkit-box-shadow:0 8px #fff, 0 16px #fff;-moz-box-shadow:0 8px #fff, 0 16px #fff; }
.report_wrap .f_right{position: absolute; top:0px; right:10px; text-align:center;}
.report_wrap .f_right .cp_recruit{font-size:16px; letter-spacing:-1px;}
.report_wrap .f_right .cp_click{display:block; font-size:12px; background-color:#666; color:#fff; padding:5px 10px; border-radius:20px;margin-bottom:10px;}
.b_tb{padding:20px 0px;}
.b_tb table {width:100%; border-collapse:collapse; border-top:2px solid #6f6f6f; border-bottom:1px solid #ccc;}
.b_tb table > thead > tr > th {border-bottom:1px solid #ccc; border-left:1px solid #e4e4e4; background:#f9fafc; height:100px; padding:50px 10px; text-align:center; font-size:14px; font-weight:normal; color:#333; line-height:1.6;}
.b_tb table > tbody > tr > th {border-top:1px solid #e4e4e4; border-left:1px solid #e4e4e4; padding:20px 0 20px 0px; text-align:left; font-size:16px; font-weight:400; color:#333; line-height:1.6; text-align:center;}
.b_tb table > tbody > tr > td {border-top:1px solid #e4e4e4; border-left:1px solid #e4e4e4; padding:20px; text-align:center; font-size:16px; color:#666; line-height:1.6;}
.b_tb table > tbody > tr > th:first-child{border-left:none;}
.b_tb table > tbody > tr > td:first-child {border-left:none;}
.b_tb table > tbody > tr:first-child > th,
.b_tb table > tbody > tr:first-child > td {border-top:none;}
.blue {color: #3399ff !important; display:inline-block;}
.btn{text-align:center; width:100%;}
.print_btn{display:block; width:250px; padding:15px; background-color:#666; color:#fff; font-size:18px; margin:0px auto;}
.print_btn:hover{color:#fff;}
</style>

<div id="print_html"></div>

<script>
$("#print_html").html( $("#print_box",opener.document).html() );
</script>


<?php
include_once "$nfor[path]/html_tail.php";
?>