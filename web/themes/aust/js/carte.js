
$(function(){
	 $(".svgClass area").click(function(){
        	var pos = $(this).index();
        	
			
			$(".detailCarte").hide();
			$(".detailCarte").eq(pos).show();
		
        });
		
});

