/* 리뷰목록 공통: 블로그 등급/방문수 표시 + 방문 추이 미니그래프 (2026-06-12 / 2026-06-15 추이그래프 추가)
   inc_review_search.php에서 로드 → blog_totalcount 스팬이 있는 모든 리뷰목록에서 동작.
   추이 그래프는 #mc-spark_<blogId> 자리가 있는 페이지(캠페인 진행 보드)에서만 그려짐(없으면 무시 → 타 페이지 무영향). */
function mcSparkSVG(counts){
	var w=116, h=30, p=3, n=counts.length;
	if(n < 2) return '';
	var max=Math.max.apply(null,counts), min=Math.min.apply(null,counts), rng=(max-min)||1;
	var pts=[];
	for(var i=0;i<n;i++){
		var x = p + (w-2*p) * i/(n-1);
		var y = h - p - (h-2*p) * (counts[i]-min)/rng;
		pts.push(x.toFixed(1)+','+y.toFixed(1));
	}
	var last = pts[pts.length-1].split(',');
	return '<svg width="'+w+'" height="'+h+'" viewBox="0 0 '+w+' '+h+'" preserveAspectRatio="none">'
		+ '<polyline points="'+pts.join(' ')+'" fill="none" stroke="#03c75a" stroke-width="1.6" stroke-linejoin="round" stroke-linecap="round"/>'
		+ '<circle cx="'+last[0]+'" cy="'+last[1]+'" r="2" fill="#03c75a"/>'
		+ '</svg>';
}
$(function(){
	$("[id*='blog_totalcount_']").each(function(){
		var blog_id = $(this).data('ref');
		if(!blog_id) return;
		$.ajax({
			type : "post",
			url  : nfor_path + "/admin/ajax_blog_count_chart.php",
			data : "blog_id=" + blog_id,
			success : function(data){
				// ★ blog_id가 URL(https://blog.naver.com/..)인 경우 jQuery $('#..') 셀렉터가 깨지므로 getElementById 사용
				function mcSet(prefix, html){ var e = document.getElementById(prefix + blog_id); if(e) e.innerHTML = html; }
				var arr = data.split("|:|");
				var total_count = arr[3];
				if(arr[0] == 'SUCCESS'){
					mcSet('blog_totalcount_', total_count);
					var grade = "브론즈";
					if(total_count < 3000)       grade = "브론즈";
					else if(total_count < 10000) grade = "실버";
					else if(total_count < 40000) grade = "골드";
					else if(total_count < 70000) grade = "플레티넘";
					else                         grade = "다이아몬드";
					mcSet('blog_totalcount2_', grade);

					// 방문 추이 미니그래프 (자리가 있을 때만)
					var spark = document.getElementById('mc-spark_'+blog_id);
					if(spark && arr[2]){
						var counts = arr[2].split(',').map(function(x){ return parseInt(x,10)||0; });
						var svg = mcSparkSVG(counts);
						if(svg){ spark.innerHTML = svg; spark.setAttribute('title', '최근 '+counts.length+'일 방문 추이'); }
					}
				} else {
					mcSet('blog_totalcount_', '0');
				}
			},
			error : function(){ console.log("blog count ajax failed"); }
		});
	});
});
